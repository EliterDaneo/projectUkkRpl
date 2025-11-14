<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
        }

        .info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
            font-size: 10pt;
        }

        th {
            background-color: #f2f2f2;
        }

        .total-box {
            border: 2px solid #000;
            padding: 10px;
            margin-top: 20px;
            text-align: right;
        }

        .rupiah {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Tanggal Laporan: {{ $date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kasir (User ID)</th>
                <th>Produk</th>
                <th>Qty</th>
                <th>PPN (%)</th>
                <th>Diskon</th>
                <th>Harga Final (per item)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $transaction->user_id }}</td>
                    <td>{{ $transaction->product->name ?? 'N/A' }}</td>
                    <td class="rupiah">{{ $transaction->qty }}</td>
                    <td class="rupiah">{{ $transaction->ppn }}</td>
                    <td class="rupiah">{{ $transaction->discount }}</td>
                    <td class="rupiah">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <strong>Total Omzet (Termasuk PPN):</strong>
        <span style="font-size: 18px;">Rp {{ number_format($total_omzet, 0, ',', '.') }}</span>
    </div>
</body>

</html>
