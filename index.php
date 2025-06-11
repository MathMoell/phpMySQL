<?php include("config.php"); ?>
<!doctype html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>i dont like the jews</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3">HKHK Spordipäev 2025</h1>
      <a href="login.php" class="btn btn-secondary">Admin login</a>
    </div>

    <form action="index.php" method="get" class="row g-2 mb-4">
      <div class="col-sm-5">
        <input type="text" name="otsi" class="form-control" placeholder="otsi...">
      </div>
      <div class="col-sm-3">
        <select name="cat" class="form-select">
          <option value="fullname">Nimi</option>
          <option value="category">Spordiala</option>
        </select>
      </div>
      <div class="col-sm-2">
        <button type="submit" class="btn btn-outline-secondary w-100">Otsi</button>
      </div>
    </form>
    <?php
      $uudiseid_lehel = 50;
      $leht = isset($_GET['page']) ? (int)$_GET['page'] : 1;
      $start = ($leht - 1) * $uudiseid_lehel;

      $otsing = isset($_GET['otsi']) ? trim($_GET['otsi']) : '';
      $cat = isset($_GET['cat']) ? $_GET['cat'] : 'fullname';
      $lubatud_kategooriad = ['fullname', 'category'];
      if (!in_array($cat, $lubatud_kategooriad)) {
        $cat = 'fullname';
      }
      if (!empty($otsing)) {
        echo "<p><strong>Otsing:</strong> " . htmlspecialchars($otsing) . "</p>";
        $stmt = $yhendus->prepare("SELECT COUNT(id) FROM sport2025 WHERE $cat LIKE ?");
        $search_term = "%$otsing%";
        $stmt->bind_param("s", $search_term);
      } else {
        $stmt = $yhendus->prepare("SELECT COUNT(id) FROM sport2025");
      }
      $stmt->execute();
      $stmt->bind_result($kokku_ridu);
      $stmt->fetch();
      $stmt->close();
      $lehti_kokku = ceil($kokku_ridu / $uudiseid_lehel);
      if (!empty($otsing)) {
        $stmt = $yhendus->prepare("SELECT * FROM sport2025 WHERE $cat LIKE ? LIMIT ?, ?");
        $stmt->bind_param("sii", $search_term, $start, $uudiseid_lehel);
      } else {
        $stmt = $yhendus->prepare("SELECT * FROM sport2025 LIMIT ?, ?");
        $stmt->bind_param("ii", $start, $uudiseid_lehel);
      }
      $stmt->execute();
      $result = $stmt->get_result();
    ?>
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nimi</th>
          <th>Email</th>
          <th>Vanus</th>
          <th>Sugu</th>
          <th>Spordiala</th>
          <th>Registreeritud</th>
        </tr>
      </thead>
      <tbody>
        <?php while($rida = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $rida['id'] ?></td>
            <td><?= htmlspecialchars($rida['fullname']) ?></td>
            <td><?= htmlspecialchars($rida['email']) ?></td>
            <td><?= $rida['age'] ?></td>
            <td><?= $rida['gender'] ?></td>
            <td><?= $rida['category'] ?></td>
            <td><?= $rida['reg_time'] ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <nav aria-label="Lehe navigeerimine">
      <ul class="pagination">
        <?php if ($leht > 1): ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $leht - 1 ?>&otsi=<?= urlencode($otsing) ?>&cat=<?= $cat ?>">Eelmine</a>
          </li>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $lehti_kokku; $i++): ?>
          <li class="page-item <?= $i == $leht ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>&otsi=<?= urlencode($otsing) ?>&cat=<?= $cat ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
        <?php if ($leht < $lehti_kokku): ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $leht + 1 ?>&otsi=<?= urlencode($otsing) ?>&cat=<?= $cat ?>">Järgmine</a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
    <?php
      $stmt->close();
      $yhendus->close();
    ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
