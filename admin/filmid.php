<?php
session_start();
include("../config.php");

// Kontrollime, kas admin on sisse loginud
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Kustuta film
if (isset($_GET['kustuta'])) {
    $film_id = intval($_GET['kustuta']);
    $stmt = mysqli_prepare($yhendus, "DELETE FROM filmid WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $film_id);
    mysqli_stmt_execute($stmt);
    header("Location: filmid.php");
    exit();
}

// Lisa või muuda film vormi kaudu
$viga = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nimi = trim($_POST['nimi']);
    $kestus = intval($_POST['kestus']);
    $zanr = trim($_POST['zanr']);
    $kirjeldus = trim($_POST['kirjeldus']);
    $vanusepiirang = trim($_POST['vanusepiirang']);
    $poster_url = trim($_POST['poster_url']);
    $aktiivne = isset($_POST['aktiivne']) ? 1 : 0;

    if ($nimi === "" || $kestus <= 0 || $zanr === "") {
        $viga = "Palun täida kõik nõutud väljad (nimi, kestus, žanr).";
    } else {
        if ($id) {
            // Uuenda olemasolev film
            $stmt = mysqli_prepare($yhendus, "UPDATE filmid SET nimi=?, kestus=?, zanr=?, kirjeldus=?, vanusepiirang=?, poster_url=?, aktiivne=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, "sissssii", $nimi, $kestus, $zanr, $kirjeldus, $vanusepiirang, $poster_url, $aktiivne, $id);
            mysqli_stmt_execute($stmt);
        } else {
            // Lisa uus film
            $stmt = mysqli_prepare($yhendus, "INSERT INTO filmid (nimi, kestus, zanr, kirjeldus, vanusepiirang, poster_url, aktiivne) VALUES (?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sissssi", $nimi, $kestus, $zanr, $kirjeldus, $vanusepiirang, $poster_url, $aktiivne);
            mysqli_stmt_execute($stmt);
        }
        header("Location: filmid.php");
        exit();
    }
}

// Kui muudetakse, laeme andmed
$muudetav_film = null;
if (isset($_GET['muuda'])) {
    $film_id = intval($_GET['muuda']);
    $stmt = mysqli_prepare($yhendus, "SELECT * FROM filmid WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $film_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $muudetav_film = mysqli_fetch_assoc($result);
}

// Kõik filmid
$filmide_paring = "SELECT * FROM filmid ORDER BY id DESC";
$filmide_tulemus = mysqli_query($yhendus, $filmide_paring);
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8" />
    <title>Admin - Filmide haldus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container py-4">
    <h1 class="mb-4">Filmide haldus</h1>

    <?php if ($viga): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($viga); ?></div>
    <?php endif; ?>

    <form method="post" class="mb-5">
        <input type="hidden" name="id" value="<?php echo $muudetav_film['id'] ?? ''; ?>">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="nimi" class="form-label">Filmi nimi *</label>
                <input type="text" id="nimi" name="nimi" class="form-control" required value="<?php echo htmlspecialchars($muudetav_film['nimi'] ?? ''); ?>">
            </div>
            <div class="col-md-2">
                <label for="kestus" class="form-label">Kestus (min) *</label>
                <input type="number" id="kestus" name="kestus" class="form-control" min="1" required value="<?php echo htmlspecialchars($muudetav_film['kestus'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <label for="zanr" class="form-label">Žanr *</label>
                <input type="text" id="zanr" name="zanr" class="form-control" required value="<?php echo htmlspecialchars($muudetav_film['zanr'] ?? ''); ?>">
            </div>
        </div>

        <div class="mt-3">
            <label for="kirjeldus" class="form-label">Kirjeldus</label>
            <textarea id="kirjeldus" name="kirjeldus" class="form-control" rows="3"><?php echo htmlspecialchars($muudetav_film['kirjeldus'] ?? ''); ?></textarea>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <label for="vanusepiirang" class="form-label">Vanusepiirang</label>
                <input type="text" id="vanusepiirang" name="vanusepiirang" class="form-control" value="<?php echo htmlspecialchars($muudetav_film['vanusepiirang'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label for="poster_url" class="form-label">Poster URL</label>
                <input type="url" id="poster_url" name="poster_url" class="form-control" value="<?php echo htmlspecialchars($muudetav_film['poster_url'] ?? ''); ?>">
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <div class="form-check mt-4">
                    <input class="form-check-input" type="checkbox" id="aktiivne" name="aktiivne" <?php echo (isset($muudetav_film['aktiivne']) && $muudetav_film['aktiivne']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="aktiivne">Aktiivne</label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-4"><?php echo $muudetav_film ? 'Salvesta muudatused' : 'Lisa film'; ?></button>
        <?php if ($muudetav_film): ?>
            <a href="filmid.php" class="btn btn-secondary mt-4 ms-2">Lisa uus film</a>
        <?php endif; ?>
    </form>

    <h2>Filmide nimekiri</h2>
    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nimi</th>
                <th>Kestus (min)</th>
                <th>Žanr</th>
                <th>Vanusepiirang</th>
                <th>Aktiivne</th>
                <th>Tegevused</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($film = mysqli_fetch_assoc($filmide_tulemus)): ?>
            <tr>
                <td><?php echo $film['id']; ?></td>
                <td><?php echo htmlspecialchars($film['nimi']); ?></td>
                <td><?php echo $film['kestus']; ?></td>
                <td><?php echo htmlspecialchars($film['zanr']); ?></td>
                <td><?php echo htmlspecialchars($film['vanusepiirang']); ?></td>
                <td><?php echo $film['aktiivne'] ? 'Jah' : 'Ei'; ?></td>
                <td>
                    <a href="filmid.php?muuda=<?php echo $film['id']; ?>" class="btn btn-sm btn-warning">Muuda</a>
                    <a href="filmid.php?kustuta=<?php echo $film['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Oled kindel, et soovid kustutada filmi?');">Kustuta</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <a href="index.php" class="btn btn-secondary mt-3">Tagasi avalehele</a>
</div>
</body>
</html>
