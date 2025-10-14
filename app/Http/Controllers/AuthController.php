<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Shopping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    protected function getDailyReportData($date)
    {
        $data = Shopping::select(
            DB::raw('HOUR(created_at) as hour'),
            DB::raw('SUM(total_price) as total_revenue'),
            DB::raw('COUNT(id) as total_transactions')
        )
            ->whereDate('created_at', $date)
            ->groupBy('hour')
            ->orderBy('hour', 'asc')
            ->get();

        $hourlyLabels = range(0, 23);
        $revenueData = array_fill(0, 24, 0);
        $transactionData = array_fill(0, 24, 0);

        foreach ($data as $item) {
            $hourIndex = (int) $item->hour;
            $revenueData[$hourIndex] = (float) $item->total_revenue;
            $transactionData[$hourIndex] = (int) $item->total_transactions;
        }  

        // Siapkan struktur data yang siap digunakan oleh Chart.js
        return [
            'labels' => array_map(fn($h) => sprintf('%02d:00', $h), $hourlyLabels),
            'datasets' => [
                [
                    'label' => 'Total Pendapatan (Rp)',
                    'data' => $revenueData,
                    'type' => 'line',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'yAxisID' => 'y1',
                ],
                [
                    'label' => 'Total Transaksi',
                    'data' => $transactionData,
                    'type' => 'bar',
                    'backgroundColor' => 'rgba(153, 102, 255, 0.6)',
                    'yAxisID' => 'y2',
                ]
            ],
            'date' => $date
        ];
    }

    // Route untuk API (dipanggil oleh AJAX)
    public function dailyReportApi(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $reportData = $this->getDailyReportData($date);
        return response()->json($reportData);
    }

    // Method dashboard yang dimodifikasi
    public function dashboard()
    {
        $categories = Category::all();

        // 1. Ambil data laporan harian untuk hari ini secara default
        $todayDate = now()->toDateString();
        $initialChartData = $this->getDailyReportData($todayDate);

        // 2. Kirim data ke view
        return view('admin.dashboard', compact('categories', 'initialChartData', 'todayDate'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
