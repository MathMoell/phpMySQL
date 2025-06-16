<?php
session_start();
include("../config.php");

// Kontrolli, kas admin on sisse loginud
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Kustuta broneering, kui on saadetud kustutamise päring
if (isset($_GET['kustuta'])) {
    $broneering_id = intval($_GET['kustuta']);
    $stmt = mysqli_prepare($yhendus, "DELETE FROM piletid WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $broneering_id);
    mysqli_stmt_execute($stmt);
    header("Location: broneeringud.php");
    exit();
}

// Lae kõik broneeringud koos kasutaja ja seansi info ning koha real ja kohas
$paring = "
    SELECT p.id, p.rida, p.koht, p.staatus,
           k.eesnimi, k.perekonnanimi,
           f.nimi AS film,
           s.kuupaev, s.kellaaeg,
           sa.nimi AS saal
    FROM piletid p
    JOIN kasutajad k ON p.kasutaja_id = k.id
    JOIN seansid s ON p.seanss_id = s.id
    JOIN filmid f ON s.film_id = f.id
    JOIN saalid sa ON s.saal_id = sa.id
    ORDER BY s.kuupaev DESC, s.kellaaeg DESC, p.rida, p.koht
";
$tulemus = mysqli_query($yhendus, $paring);
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Broneeringute haldus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container py-4">
    <h1>Broneeringute haldus</h1>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Film</th>
                <th>Saal</th>
                <th>Kuupäev</th>
                <th>Kellaaeg</th>
                <th>Rida</th>
                <th>Koht</th>
                <th>Kasutaja</th>
                <th>Staatus</th>
                <th>Tegevus</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($r = mysqli_fetch_assoc($tulemus)): ?>
            <tr>
                <td><?= $r['id'] ?></td>
                <td><?= htmlspecialchars($r['film']) ?></td>
                <td><?= htmlspecialchars($r['saal']) ?></td>
                <td><?= htmlspecialchars($r['kuupaev']) ?></td>
                <td><?= htmlspecialchars(substr($r['kellaaeg'],0,5)) ?></td>
                <td><?= $r['rida'] ?></td>
                <td><?= $r['koht'] ?></td>
                <td><?= htmlspecialchars($r['eesnimi'] . ' ' . $r['perekonnanimi']) ?></td>
                <td><?= htmlspecialchars($r['staatus']) ?></td>
                <td>
                    <a href="broneeringud.php?kustuta=<?= $r['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Oled kindel, et soovid selle broneeringu kustutada?');">Kustuta</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="index.php" class="btn btn-secondary mt-3">Tagasi avalehele</a>
</div>
</body>
</html>
