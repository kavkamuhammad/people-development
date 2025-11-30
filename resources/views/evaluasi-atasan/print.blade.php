<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Evaluasi Atasan - {{ $evaluasiAtasan->nama_karyawan }}</title>
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
        .score-badge {
            background-color: #4472C4;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
        }
        .text-center {
            text-align: center;
        }
        .notes-section {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            border-left: 4px solid #ffc107;
        }
        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 20px;
        }
        .signature-line {
            border-top: 2px solid #000;
            margin-top: 60px;
            padding-top: 5px;
            font-weight: bold;
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
        <h1>EVALUASI KINERJA PASCA TRAINING</h1>
        <p>Penilaian oleh Atasan Langsung</p>
    </div>

    <!-- Employee & Training Info -->
    <div class="info-section">
        <table>
            <tr>
                <td width="150"><strong>Nama Karyawan</strong></td>
                <td width="250">: {{ $evaluasiAtasan->nama_karyawan }}</td>
                <td width="150"><strong>Materi Training</strong></td>
                <td>: {{ $evaluasiAtasan->materi_training }}</td>
            </tr>
            <tr>
                <td><strong>Departemen</strong></td>
                <td>: {{ $evaluasiAtasan->department }}</td>
                <td><strong>Tanggal Training</strong></td>
                <td>: {{ $evaluasiAtasan->tanggal_training->format('d F Y') }}</td>
            </tr>
            <tr>
                <td><strong>Job Level</strong></td>
                <td>: {{ $evaluasiAtasan->peserta->employee->jobLevel->name ?? '-' }}</td>
                <td><strong>Trainer</strong></td>
                <td>: {{ $evaluasiAtasan->training->trainer->nama_trainer ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Nama Atasan</strong></td>
                <td>: {{ $evaluasiAtasan->atasan->name ?? '-' }}</td>
                <td><strong>Tanggal Evaluasi</strong></td>
                <td>: {{ $evaluasiAtasan->created_at->format('d F Y') }}</td>
            </tr>
        </table>
    </div>

    <!-- Score Summary -->
    <table style="margin-bottom: 20px;">
        <tr>
            <th colspan="2">HASIL EVALUASI</th>
        </tr>
        <tr class="text-center">
            <td width="50%"><strong>Total Skor: {{ $evaluasiAtasan->total_skor }} / 20</strong></td>
            <td width="50%"><strong>Kategori: {{ $evaluasiAtasan->kategori }}</strong></td>
        </tr>
    </table>

    <!-- Evaluation Details -->
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Aspek Penilaian</th>
                <th width="10%">Skor</th>
                <th width="55%">Uraian</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td><strong>Peningkatan Keterampilan</strong></td>
                <td class="text-center"><span class="score-badge">{{ $evaluasiAtasan->peningkatan_keterampilan }} / 5</span></td>
                <td>{{ $evaluasiAtasan->uraian_peningkatan_keterampilan ?: 'Tidak ada uraian' }}</td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td><strong>Penerapan Ilmu/Pengetahuan</strong></td>
                <td class="text-center"><span class="score-badge">{{ $evaluasiAtasan->penerapan_ilmu }} / 5</span></td>
                <td>{{ $evaluasiAtasan->uraian_penerapan_ilmu ?: 'Tidak ada uraian' }}</td>
            </tr>
            <tr>
                <td class="text-center">3</td>
                <td><strong>Perubahan Perilaku Kerja</strong></td>
                <td class="text-center"><span class="score-badge">{{ $evaluasiAtasan->perubahan_perilaku }} / 5</span></td>
                <td>{{ $evaluasiAtasan->uraian_perubahan_perilaku ?: 'Tidak ada uraian' }}</td>
            </tr>
            <tr>
                <td class="text-center">4</td>
                <td><strong>Dampak pada Performa/Hasil Kerja</strong></td>
                <td class="text-center"><span class="score-badge">{{ $evaluasiAtasan->dampak_performa }} / 5</span></td>
                <td>{{ $evaluasiAtasan->uraian_dampak_performa ?: 'Tidak ada uraian' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Notes Section -->
    @if($evaluasiAtasan->catatan_atasan)
    <div class="notes-section">
        <strong>Catatan & Saran dari Atasan:</strong><br>
        {{ $evaluasiAtasan->catatan_atasan }}
    </div>
    @endif

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <div>Karyawan yang Dievaluasi</div>
            <div class="signature-line">{{ $evaluasiAtasan->nama_karyawan }}</div>
        </div>
        <div class="signature-box">
            <div>Atasan Penilai</div>
            <div class="signature-line">{{ $evaluasiAtasan->atasan->name ?? '-' }}</div>
        </div>
    </div>

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
