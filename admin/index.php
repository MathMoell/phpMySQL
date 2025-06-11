<?php
include("../config.php");
session_start();
if (!isset($_SESSION['tuvastamine'])) {
    header('Location: ../login.php');
    exit();
}
mysqli_set_charset($yhendus, "utf8");
?>
<!doctype html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin leht</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Admin leht</h1>
    <a class="btn btn-outline-primary" href="../index.php">&larr; Tagasi avalehele</a>
  </div>

  <?php

  if (isset($_GET["muuda"], $_GET["id"])) {
      $id = intval($_GET["id"]);
      $paring = mysqli_prepare($yhendus, "SELECT * FROM sport2025 WHERE id = ?");
      mysqli_stmt_bind_param($paring, "i", $id);
      mysqli_stmt_execute($paring);
      $tulemus = mysqli_stmt_get_result($paring);
      $rida = mysqli_fetch_assoc($tulemus);
  }


  if (isset($_GET["salvesta_muudatus"], $_GET["id"])) {
      $id = intval($_GET["id"]);
      $fullname = $_GET["fullname"];
      $email = $_GET["email"];
      $age = intval($_GET["age"]);
      $gender = $_GET["gender"];
      $category = $_GET["category"];

      $paring = mysqli_prepare($yhendus, "UPDATE sport2025 SET fullname=?, email=?, age=?, gender=?, category=? WHERE id=?");
      mysqli_stmt_bind_param($paring, "ssissi", $fullname, $email, $age, $gender, $category, $id);
      mysqli_stmt_execute($paring);

      if (mysqli_stmt_affected_rows($paring) === 1) {
          header('Location: index.php?msg=uuendatud');
          exit();
      } else {
          echo "<div class='alert alert-danger'>Andmeid ei uuendatud.</div>";
      }
  }
  ?>


  <form action="index.php" method="get" class="mb-4">
    <input type="hidden" name="id" value="<?= $rida['id'] ?? '' ?>">
    <div class="row g-2">
      <div class="col-md-4"><input type="text" name="fullname" class="form-control" required placeholder="Nimi" value="<?= $rida['fullname'] ?? '' ?>"></div>
      <div class="col-md-4"><input type="email" name="email" class="form-control" required placeholder="E-mail" value="<?= $rida['email'] ?? '' ?>"></div>
      <div class="col-md-2"><input type="number" name="age" class="form-control" min="16" max="88" required placeholder="Vanus" value="<?= $rida['age'] ?? '' ?>"></div>
      <div class="col-md-2"><input type="text" name="gender" class="form-control" required placeholder="Sugu" value="<?= $rida['gender'] ?? '' ?>"></div>
      <div class="col-md-6 mt-2"><input type="text" name="category" class="form-control" required placeholder="Spordiala" value="<?= $rida['category'] ?? '' ?>"></div>
      <div class="col-md-3 mt-2">
        <input type="submit" value="Salvesta" name="<?= isset($_GET['muuda']) ? 'salvesta_muudatus' : 'salvesta' ?>" class="btn btn-<?= isset($_GET['muuda']) ? 'secondary' : 'success' ?> w-100">
      </div>
    </div>
  </form>

  <?php

  if (isset($_GET["salvesta"]) && !empty($_GET["fullname"])) {
      $fullname = $_GET["fullname"];
      $email = $_GET["email"];
      $age = intval($_GET["age"]);
      $gender = $_GET["gender"];
      $category = $_GET["category"];

      $paring = mysqli_prepare($yhendus, "INSERT INTO sport2025 (fullname,email,age,gender,category) VALUES (?,?,?,?,?)");
      mysqli_stmt_bind_param($paring, "ssiss", $fullname, $email, $age, $gender, $category);
      mysqli_stmt_execute($paring);

      echo (mysqli_stmt_affected_rows($paring) === 1)
          ? "<div class='alert alert-success'>Kirje edukalt lisatud</div>"
          : "<div class='alert alert-danger'>Kirjet ei lisatud</div>";
  }


  $uudiseid_lehel = 50;
  $leht = intval($_GET['page'] ?? 1);
  $start = ($leht - 1) * $uudiseid_lehel;

  if (!empty($_GET['otsi'])) {
      $s = mysqli_real_escape_string($yhendus, $_GET['otsi']);
      $cat = in_array($_GET['cat'] ?? 'fullname', ['fullname']) ? $_GET['cat'] : 'fullname';
      $paring = "SELECT * FROM sport2025 WHERE $cat LIKE '%$s%'";
      echo "<p>Otsing: <strong>" . htmlspecialchars($s) . "</strong></p>";
  } else {
      $paring = "SELECT * FROM sport2025 LIMIT $start, $uudiseid_lehel";
  }

  $tulemus = mysqli_query($yhendus, $paring);
  ?>


  <table class="table">
    <thead class="table-light">
      <tr>
        <th>ID</th><th>Nimi</th><th>Email</th><th>Vanus</th><th>Sugu</th><th>Spordiala</th><th>Reg aeg</th><th>Muuda</th><th>Kustuta</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($rida = mysqli_fetch_assoc($tulemus)): ?>
        <tr>
          <td><?= $rida['id'] ?></td>
          <td><?= htmlspecialchars($rida['fullname']) ?></td>
          <td><?= htmlspecialchars($rida['email']) ?></td>
          <td><?= $rida['age'] ?></td>
          <td><?= htmlspecialchars($rida['gender']) ?></td>
          <td><?= htmlspecialchars($rida['category']) ?></td>
          <td><?= $rida['reg_time'] ?></td>
          <td><a class='btn btn-sm btn-outline-secondary' href='?muuda&id=<?= $rida['id'] ?>'>Muuda</a></td>
          <td><a class='btn btn-sm btn-outline-danger' href='?kustuta&id=<?= $rida['id'] ?>' onclick="return confirm('Kustutada kirje?')">Kustuta</a></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <?php

  if (isset($_GET['kustuta'], $_GET['id'])) {
      $id = intval($_GET['id']);
      mysqli_query($yhendus, "DELETE FROM sport2025 WHERE id=$id");
      if (mysqli_affected_rows($yhendus) === 1) {
          header("Location: index.php?msg=Rida kustutatud");
          exit();
      } else {
          echo "<div class='alert alert-danger'>Kirjet ei kustutatud</div>";
      }
  }


  $kokku_ridu = mysqli_fetch_array(mysqli_query($yhendus, "SELECT COUNT(id) FROM sport2025"))[0];
  $lehti_kokku = ceil($kokku_ridu / $uudiseid_lehel);
  ?>


  <div class="mb-4">
    <?php for ($i = 1; $i <= $lehti_kokku; $i++): ?>
      <a href="?page=<?= $i ?>" class="btn btn-sm <?= ($i == $leht) ? 'btn-secondary fw-bold' : 'btn-outline-secondary' ?> m-1"><?= $i ?></a>
    <?php endfor; ?>
  </div>


  <?php if (isset($_GET['msg'])): ?>
    <div class='alert alert-success'><?= htmlspecialchars($_GET['msg']) ?></div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
