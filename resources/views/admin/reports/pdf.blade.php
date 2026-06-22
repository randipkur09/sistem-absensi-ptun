<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .subtitle { font-size: 14px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN ABSENSI PEGAWAI</div>
        <div class="title">PTUN BANDAR LAMPUNG</div>
        <div class="subtitle">Periode: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="25%">Nama Pegawai</th>
                <th width="10%">Tipe</th>
                <th class="text-center" width="10%">Jam Masuk</th>
                <th class="text-center" width="10%">Jam Pulang</th>
                <th class="text-center" width="10%">Status</th>
                <th width="15%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $index => $att)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $att->tanggal->format('d/m/Y') }}</td>
                <td>{{ $att->user->name ?? '-' }}</td>
                <td>{{ ucfirst($att->user->employee_type ?? '-') }}</td>
                <td class="text-center">{{ $att->jam_masuk ?? '-' }}</td>
                <td class="text-center">{{ $att->jam_pulang ?? '-' }}</td>
                <td class="text-center">{{ ucfirst($att->status) }}</td>
                <td>{{ $att->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Bandar Lampung, {{ now()->format('d/m/Y') }}</p>
        <br><br><br>
        <p><strong>Admin PTUN Bandar Lampung</strong></p>
    </div>
</body>
</html>
