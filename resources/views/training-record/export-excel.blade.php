<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Training Record - {{ $training->materiTraining->nama_materi ?? 'Training' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4472C4;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 5px 0;
            color: #2C3E50;
        }
        .header p {
            margin: 3px 0;
            color: #555;
        }
        .info-section {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .info-section table {
            border: none;
            margin: 0;
        }
        .info-section td {
            border: none;
            padding: 5px;
        }
        .status-lulus {
            background-color: #d4edda;
            color: #155724;
            font-weight: bold;
            text-align: center;
        }
        .status-tidak-lulus {
            background-color: #f8d7da;
            color: #721c24;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>TRAINING RECORD KARYAWAN</h1>
        <p>Hasil Evaluasi dan Penilaian Training</p>
    </div>

    <!-- Training Info -->
    <div class="info-section">
        <table>
            <tr>
                <td width="150"><strong>Materi Training</strong></td>
                <td>: {{ $training->materiTraining->nama_materi ?? '-' }}</td>
                <td width="150"><strong>Tanggal Training</strong></td>
                <td>: {{ $training->tanggal_training->format('d F Y') }}</td>
            </tr>
            <tr>
                <td><strong>Trainer</strong></td>
                <td>: {{ $training->trainer->nama_trainer ?? '-' }}</td>
                <td><strong>Total Peserta</strong></td>
                <td>: {{ $pesertaData->count() }} orang</td>
            </tr>
        </table>
    </div>

    <!-- Summary Stats -->
    <table style="margin-bottom: 20px;">
        <tr>
            <th width="25%">Total Peserta</th>
            <th width="25%">Lulus</th>
            <th width="25%">Tidak Lulus</th>
            <th width="25%">Rata-rata Point</th>
        </tr>
        <tr class="text-center">
            <td><strong>{{ $pesertaData->count() }}</strong></td>
            <td><strong style="color: #28a745;">{{ $pesertaData->where('status', 'Lulus')->count() }}</strong></td>
            <td><strong style="color: #dc3545;">{{ $pesertaData->where('status', 'Tidak Lulus')->count() }}</strong></td>
            <td><strong>{{ number_format($pesertaData->avg('total_point'), 1) }}</strong></td>
        </tr>
    </table>

    <!-- Data Table -->
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Nama Karyawan</th>
                <th width="12%">Departemen</th>
                <th width="10%">Pemahaman<br>Peserta</th>
                <th width="10%">Skor<br>Evaluasi</th>
                <th width="10%">Total<br>Point</th>
                <th width="10%">Status</th>
                <th width="28%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesertaData as $index => $peserta)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $peserta['nama_karyawan'] }}</strong></td>
                <td>{{ $peserta['department'] }}</td>
                <td class="text-center"><strong>{{ $peserta['pemahaman_peserta'] }}</strong></td>
                <td class="text-center"><strong>{{ $peserta['skor_evaluasi'] }}</strong></td>
                <td class="text-center"><strong style="font-size: 14px;">{{ $peserta['total_point'] }}</strong></td>
                <td class="{{ $peserta['status'] === 'Lulus' ? 'status-lulus' : 'status-tidak-lulus' }}">
                    {{ $peserta['status'] }}
                </td>
                <td>{{ $peserta['catatan'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>


    <!-- Footer -->
    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
