<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport de Vente</title>
    <style>
        body { background:#f8f9fa; font-family: Arial, sans-serif; }
        .table-container { margin:30px auto; max-width:90%; background:white; padding:20px; }
        table { border-collapse: collapse; width: 80%; margin:20px auto; }
        table th, table td { border:1px solid #dee2e6; padding:10px; text-align:center; }
        table th { background:rgb(144,117,48); color:white; text-transform:uppercase; }
        table td { color:rgba(76,64,49,0.73); font-size:14px; }
        table tbody tr:nth-child(even) { background:#f8f9fa; }
        table tbody tr:hover { background:#e9ecef; }
        h3,h5 { text-align:center; color:#495057; font-weight:bold; }
        img { width:100%; height:auto; }
        .table1 { margin:0 0 40px 15%; }
        .table2 { margin:0 0 40px 32%; }
    </style>
</head>
<body>
<img src="{{ public_path('images/chcl.PNG') }}" alt="Logo du CHCL">
<h5>Rapport de vente du <span style="color:red;">{{ \Carbon\Carbon::parse($date_deb)->translatedFormat('l d F Y') }}</span>
    au <span style="color:red;">{{ \Carbon\Carbon::parse($date_fin)->translatedFormat('l d F Y') }}</span></h5>
<hr>

<table class="table1">
    <thead>
    <tr>
        <th>Nom Client</th>
        <th>Nom Plat</th>
        <th>Cuisson</th>
        <th>Prix</th>
        <th>Date Vente</th>
    </tr>
    </thead>
    <tbody>
    @forelse($ventes as $vente)
        <tr>
            <td>{{ $vente->client->nom_client }}</td>
            <td>{{ $vente->plat->nom_plat }}</td>
            <td>{{ $vente->plat->cuisson_plat }}</td>
            <td>{{ number_format($vente->plat->prix_plat, 2) }} HTG</td>
            <td>{{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y') }}</td>
        </tr>
    @empty
        <tr><td colspan="5">Aucune vente trouvée pour cette période.</td></tr>
    @endforelse
    </tbody>
</table>

<table class="table2">
    <thead>
    <tr>
        <th>Total Plat</th>
        <th>Total Montant</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{ $total_plat }}</td>
        <td>{{ number_format($total_montant, 2) }} HTG</td>
    </tr>
    </tbody>
</table>
</body>
</html>
