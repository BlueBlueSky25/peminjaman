<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Alat - {{ date('d/m/Y', strtotime($tanggal)) }}</title>
    <style>
        @media print {
            @page {
                margin: 1.5cm;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            max-width: 210mm;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 22px;
        }
        
        .header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: normal;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }
        
        .info-box {
            background: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .info-box table {
            width: 100%;
        }
        
        .info-box td {
            padding: 5px 0;
            font-size: 14px;
        }
        
        .summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .summary-box {
            border: 2px solid #ddd;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
        }
        
        .summary-box.blue { border-color: #2563eb; }
        .summary-box.green { border-color: #16a34a; }
        .summary-box.red { border-color: #dc2626; }
        
        .summary-box .label {
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .summary-box .value {
            font-size: 32px;
            font-weight: bold;
        }
        
        .summary-box.blue .value { color: #2563eb; }
        .summary-box.green .value { color: #16a34a; }
        .summary-box.red .value { color: #dc2626; }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section h3 {
            background: #333;
            color: white;
            padding: 10px 15px;
            margin-bottom: 15px;
            font-size: 16px;
            border-radius: 3px;
        }
        
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table.data-table th,
        table.data-table td {
            border: 1px solid #ddd;
            padding: 10px 8px;
            text-align: left;
            font-size: 12px;
        }
        
        table.data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        table.data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-center {
            text-align: center;
        }
        
        .footer {
            margin-top: 50px;
            page-break-inside: avoid;
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }
        
        .signature {
            text-align: center;
        }
        
        .signature .role {
            margin-bottom: 60px;
            font-weight: bold;
        }
        
        .signature .name {
            text-decoration: underline;
            font-weight: bold;
        }
        
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .print-btn:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn no-print">
        🖨️ Cetak Laporan
    </button>

    <div class="header">
        <h1>SISTEM PEMINJAMAN ALAT</h1>
        <h2>LAPORAN PEMINJAMAN HARIAN</h2>
        <p>Tanggal: {{ date('d F Y', strtotime($tanggal)) }}</p>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td width="150"><strong>Tanggal Laporan</strong></td>
                <td>: {{ date('d F Y', strtotime($tanggal)) }}</td>
            </tr>
            <tr>
                <td><strong>Waktu Cetak</strong></td>
                <td>: {{ date('d F Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td><strong>Dicetak oleh</strong></td>
                <td>: {{ session('username', 'Administrator') }}</td>
            </tr>
        </table>
    </div>

    <div class="summary">
        <div class="summary-box blue">
            <div class="label">Total Peminjaman</div>
            <div class="value">{{ $totalPeminjamanHariIni }}</div>
        </div>
        <div class="summary-box green">
            <div class="label">Total Pengembalian</div>
            <div class="value">{{ $totalPengembalianHariIni }}</div>
        </div>
        <div class="summary-box red">
            <div class="label">Total Denda</div>
            <div class="value" style="font-size: 24px;">Rp {{ number_format($totalDendaHariIni, 0, ',', '.') }}</div>
        </div>
    </div>

    <div>