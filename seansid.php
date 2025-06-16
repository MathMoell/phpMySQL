<?php
include "config.php";

// Leia kõik aktiivsed seansid koos filmi ja saali nimega
$paring = "
    SELECT seansid.id, filmid.nimi AS film, saalid.nimi AS saal, seansid.kuupaev, seansid.kellaaeg
    FROM seansid
    JOIN filmid ON seansid.film_id = filmid.id
    JOIN saalid ON seansid.saal_id = saalid.id
    WHERE seansid.aktiivne = 1
    ORDER BY seansid.kuupaev, seansid.kellaaeg
";
$tulemus = mysqli_query($yhendus, $paring);
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Vali seanss</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container py-4">
    <h2>Vali seanss</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Film</th>
                <th>Saal</th>
                <th>Kuupäev</th>
                <th>Kellaaeg</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php while ($r = mysqli_fetch_assoc($tulemus)): ?>
            <tr>
                <td><?= htmlspecialchars($r['film']) ?></td>
                <td><?= htmlspecialchars($r['saal']) ?></td>
                <td><?= htmlspecialchars($r['kuupaev']) ?></td>
                <td><?= htmlspecialchars(substr($r['kellaaeg'],0,5)) ?></td>
                <td>
                    <a href="broneeri.php?seanss=<?= $r['id'] ?>" class="btn btn-primary btn-sm">Vali kohad</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
