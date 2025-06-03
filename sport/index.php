<?php include("config.php"); ?>
<!doctype html>
<html lang="et">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HKHK spordipäev 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  </head>
  <body>
    <div class="container py-4">
      <h1>HKHK spordipäev 2025</h1>

<?php
// Kontrolli, kas muudame kirjet
$muuda = false;
$edit_data = [];

if (isset($_GET["id"])) {
    $edit_id = (int)$_GET["id"];
    $stmt = $yhendus->prepare("SELECT * FROM sport2025 WHERE id=?");
    $stmt->bindValue(1, $edit_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $edit_data = $result->fetchArray(SQLITE3_ASSOC);
    if ($edit_data) {
        $muuda = true;
    }
}

// Andmete salvestamine või muutmine
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["fullname"])) {
    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $age = (int)$_POST["age"];
    $gender = trim($_POST["gender"]);
    $category = trim($_POST["category"]);

    if (isset($_POST["edit_id"])) {
        $id = (int)$_POST["edit_id"];
        $stmt = $yhendus->prepare("UPDATE sport2025 SET fullname=?, email=?, age=?, gender=?, category=? WHERE id=?");
        $stmt->bindValue(1, $fullname);
        $stmt->bindValue(2, $email);
        $stmt->bindValue(3, $age);
        $stmt->bindValue(4, $gender);
        $stmt->bindValue(5, $category);
        $stmt->bindValue(6, $id);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Andmed muudetud</div>";
        } else {
            echo "<div class='alert alert-danger'>Viga muutmisel</div>";
        }
    } else {
        $stmt = $yhendus->prepare("INSERT INTO sport2025 (fullname, email, age, gender, category) VALUES (?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $fullname);
        $stmt->bindValue(2, $email);
        $stmt->bindValue(3, $age);
        $stmt->bindValue(4, $gender);
        $stmt->bindValue(5, $category);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Kirje edukalt lisatud</div>";
        } else {
            echo "<div class='alert alert-danger'>Kirjet ei lisatud</div>";
        }
    }
}
?>

<!-- 📝 Vorm -->
<form action="index.php" method="post" class="mb-4">
    <?php if ($muuda): ?>
        <input type="hidden" name="edit_id" value="<?php echo $edit_data["id"]; ?>">
    <?php endif; ?>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nimi:</label>
            <input type="text" name="fullname" required maxlength="50" class="form-control"
                value="<?php echo $muuda ? htmlspecialchars($edit_data['fullname']) : ''; ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Email:</label>
            <input type="email" name="email" maxlength="50" class="form-control"
                value="<?php echo $muuda ? htmlspecialchars($edit_data['email']) : ''; ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Vanus:</label>
            <input type="number" name="age" min="16" max="88" step="1" class="form-control"
                value="<?php echo $muuda ? htmlspecialchars($edit_data['age']) : ''; ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Sugu:</label>
            <input type="text" name="gender" maxlength="10" class="form-control"
                value="<?php echo $muuda ? htmlspecialchars($edit_data['gender']) : ''; ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Spordiala:</label>
            <input type="text" name="category" maxlength="30" class="form-control"
                value="<?php echo $muuda ? htmlspecialchars($edit_data['category']) : ''; ?>">
        </div>
        <div class="col-12">
            <input type="submit" value="<?php echo $muuda ? 'Muuda' : 'Salvesta'; ?>" class="btn btn-primary mt-2">
        </div>
    </div>
</form>

<!-- 🔍 Otsing -->
<form action="index.php" method="get" class="py-2">
    <input type="text" name="otsi" placeholder="Otsi..." class="form-control d-inline-block w-25">
    <select name="cat" class="form-select d-inline-block w-25">
        <option value="fullname">Nimi</option>
        <option value="category">Spordiala</option>
    </select>
    <input type="submit" value="Otsi" class="btn btn-secondary">
</form>

<!-- 📋 Tabel -->
<table class="table table-striped">
    <tr>
        <th>ID</th>
        <th>Nimi</th>
        <th>Email</th>
        <th>Vanus</th>
        <th>Sugu</th>
        <th>Spordiala</th>
        <th>Registreeritud</th>
        <th>Muuda</th>
    </tr>

<?php
if (isset($_GET["otsi"]) && !empty(trim($_GET["otsi"]))) {
    $s = trim($_GET["otsi"]);
    $cat = $_GET["cat"];
    $lubatud_veerud = ['fullname', 'category'];
    if (!in_array($cat, $lubatud_veerud)) {
        $cat = 'fullname';
    }
    $paring = "SELECT * FROM sport2025 WHERE $cat LIKE '%' || :s || '%' ORDER BY reg_time DESC";
    $stmt = $yhendus->prepare($paring);
    $stmt->bindValue(':s', $s, SQLITE3_TEXT);
    $tulemus = $stmt->execute();
    echo "<p>Otsing: " . htmlspecialchars($s) . "</p>";
} else {
    $tulemus = $yhendus->query("SELECT * FROM sport2025 ORDER BY reg_time DESC LIMIT 50");
}

while ($rida = $tulemus->fetchArray(SQLITE3_ASSOC)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($rida['id']) . "</td>";
    echo "<td>" . htmlspecialchars($rida['fullname']) . "</td>";
    echo "<td>" . htmlspecialchars($rida['email']) . "</td>";
    echo "<td>" . htmlspecialchars($rida['age']) . "</td>";
    echo "<td>" . htmlspecialchars($rida['gender']) . "</td>";
    echo "<td>" . htmlspecialchars($rida['category']) . "</td>";
    echo "<td>" . htmlspecialchars($rida['reg_time']) . "</td>";
    echo "<td><a class='btn btn-success btn-sm' href='index.php?id=" . $rida['id'] . "'>Muuda</a></td>";
    echo "</tr>";
}
?>
</table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  </body>
</html>
