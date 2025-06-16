<?php
include "config.php";

$seanss_id = intval($_GET['seanss'] ?? $_POST['seanss_id'] ?? 0);
if (!$seanss_id) die("Seansi ID puudub!");

$paring = "
    SELECT seansid.*, filmid.nimi AS film, saalid.nimi AS saal, saalid.ridade_arv, saalid.kohti_reas
    FROM seansid
    JOIN filmid ON seansid.film_id = filmid.id
    JOIN saalid ON seansid.saal_id = saalid.id
    WHERE seansid.id = $seanss_id
    LIMIT 1
";
$tulemus = mysqli_query($yhendus, $paring);
$seanss = mysqli_fetch_assoc($tulemus);
if (!$seanss) die("Seanssi ei leitud!");

$broneeritud = [];
$broneeringu_paring = "SELECT rida, koht FROM piletid WHERE seanss_id = $seanss_id AND staatus IN ('broneeritud','ostetud')";
$broneeringu_tulemus = mysqli_query($yhendus, $broneeringu_paring);
while ($b = mysqli_fetch_assoc($broneeringu_tulemus)) {
    $broneeritud[$b['rida'].'_'.$b['koht']] = true;
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Broneeri istekohti</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .seat { width: 36px; height: 36px; margin: 2px; border-radius: 7px; border: 1px solid #ccc; display: inline-block; text-align: center; line-height: 36px; cursor: pointer; font-weight: bold;}
        .seat.free { background: #4caf50; color: white; }
        .seat.booked { background: #d32f2f; color: white; cursor: not-allowed; }
        .seat.selected { background: #ffeb3b; color: #333; }
        .screen { background: #333; color: #fff; padding: 8px 0; margin: 20px 0 30px 0; border-radius: 5px; text-align: center;}
    </style>
</head>
<body>
<div class="container py-4">
    <h2><?= htmlspecialchars($seanss['film']) ?> - <?= htmlspecialchars($seanss['saal']) ?></h2>
    <p><b><?= htmlspecialchars($seanss['kuupaev']) ?> kell <?= htmlspecialchars(substr($seanss['kellaaeg'],0,5)) ?></b></p>
    <div class="screen">EKRAAN</div>
    <form method="post" action="kinnitus.php">
        <input type="hidden" name="seanss_id" value="<?= $seanss_id ?>">
        <?php for ($rida = 1; $rida <= $seanss['ridade_arv']; $rida++): ?>
            <div>
            <?php for ($koht = 1; $koht <= $seanss['kohti_reas']; $koht++):
                $key = $rida . '_' . $koht;
                $klass = isset($broneeritud[$key]) ? 'seat booked' : 'seat free';
            ?>
                <span class="<?= $klass ?>" 
                      data-rida="<?= $rida ?>" 
                      data-koht="<?= $koht ?>"
                      <?php if (!isset($broneeritud[$key])): ?>
                          onclick="toggleSeat(this)"
                      <?php endif; ?>
                ><?= $koht ?></span>
            <?php endfor; ?>
            <span style="margin-left: 10px;">Rida <?= $rida ?></span>
            </div>
        <?php endfor; ?>
        <input type="hidden" name="kohad" id="kohad" value="">
        <div class="mt-4">
            <label>Eesnimi: <input type="text" name="eesnimi" required></label>
            <label class="ms-3">Perekonnanimi: <input type="text" name="perekonnanimi" required></label>
            <label class="ms-3">Isikukood: <input type="text" name="isikukood" required pattern="\d{11}"></label>
            <label class="ms-3">E-mail: <input type="email" name="email" required></label>
        </div>
        <button type="submit" class="btn btn-success mt-4">Broneeri valitud kohad</button>
    </form>
</div>
<script>
    let selected = [];
    function toggleSeat(el) {
        const rida = el.getAttribute('data-rida');
        const koht = el.getAttribute('data-koht');
        const key = rida + '_' + koht;
        if (el.classList.contains('selected')) {
            el.classList.remove('selected');
            selected = selected.filter(k => k !== key);
        } else {
            el.classList.add('selected');
            selected.push(key);
        }
        document.getElementById('kohad').value = selected.join(',');
    }
</script>
</body>
</html>
