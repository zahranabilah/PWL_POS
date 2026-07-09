<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: "Times New Roman", Times, serif; font-size: 12px; }
        .header-table { width: 100%; margin-bottom: 20px; }
        .header-table td { vertical-align: top; }
        .header-title { font-size: 14px; font-weight: bold; }
        .header-subtitle { font-size: 10px; }
        .report-title { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 15px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #000; padding: 6px; }
        .table th { background: #f2f2f2; text-align: left; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td width="20%">
                <img src="{{ public_path('polinema-bw.png') }}" width="100" alt="Logo">
            </td>
            <td width="80%">
                <div class="header-title">LAPORAN DATA LEVEL</div>
                <div class="header-subtitle">Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi</div>
                <div class="header-subtitle">Politeknik Negeri Malang</div>
                <div class="header-subtitle">Jl. Soekarno-Hatta No. 9, Malang</div>
            </td>
        </tr>
    </table>

    <div class="report-title">DAFTAR LEVEL</div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Level</th>
                <th>Nama Level</th>
            </tr>
        </thead>
        <tbody>
        @foreach($levels as $index => $level)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $level->level_kode }}</td>
                <td>{{ $level->level_nama }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
