<?php
include "config.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['kohad']) || empty($_POST['seanss_id'])) {
    die("Vigane päring (kohad või seansi id puudub).");
}

$seanss_id = intval($_POST['seanss_id']);
$eesnimi = trim($_POST['eesnimi']);
$perekonnanimi = trim($_POST['perekonnanimi']);
$isikukood = trim($_POST['isikukood']);
$email = trim($_POST['email']);
$kohad = explode(',', $_POST['kohad']);

// Kontrolli kasutajat või loo uus
$stmt = mysqli_prepare($yhendus, "SELECT id FROM kasutajad WHERE isikukood=?");
mysqli_stmt_bind_param($stmt, "s", $isikukood);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if ($row = mysqli_fetch_assoc($res)) {
    $kasutaja_id = $row['id'];
} else {
    $stmt = mysqli_prepare($yhendus, "INSERT INTO kasutajad (eesnimi, perekonnanimi, isikukood, email) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $eesnimi, $perekonnanimi, $isikukood, $email);
    mysqli_stmt_execute($stmt);
    $kasutaja_id = mysqli_insert_id($yhendus);
}

// Lisa piletid
$onnestus = 0;
foreach ($kohad as $koht) {
    if (preg_match('/^(\d+)_(\d+)$/', $koht, $m)) {
        $rida = intval($m[1]);
        $koht_nr = intval($m[2]);
        // Kontrolli, kas koht on juba broneeritud
        $kontroll = mysqli_query($yhendus, "SELECT id FROM piletid WHERE seanss_id=$seanss_id AND rida=$rida AND koht=$koht_nr AND staatus IN ('broneeritud','ostetud')");
        if (mysqli_num_rows($kontroll) == 0) {
            $stmt = mysqli_prepare($yhendus, "INSERT INTO piletid (kasutaja_id, seanss_id, rida, koht, staatus) VALUES (?, ?, ?, ?, 'broneeritud')");
            mysqli_stmt_bind_param($stmt, "iiii", $kasutaja_id, $seanss_id, $rida, $koht_nr);
            mysqli_stmt_execute($stmt);
            $onnestus++;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Broneeringu kinnitus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container py-5">
    <?php if ($onnestus > 0): ?>
        <div class="alert alert-success">
            <h2>Broneering õnnestus!</h2>
            <p>Broneerisid <?= $onnestus ?> istekohta.<br>
            <a href="seansid.php" class="btn btn-primary mt-3">Broneeri veel</a>
            </p>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            <h2>Broneerimine ebaõnnestus</h2>
            <p>Valitud kohad olid juba broneeritud või tekkis viga.<br>
            <a href="seansid.php" class="btn btn-primary mt-3">Proovi uuesti</a>
            </p>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
