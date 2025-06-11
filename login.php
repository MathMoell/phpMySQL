<?php
include("config.php");
session_start();
if (isset($_SESSION['tuvastamine'])) {
    header('Location: /admin');
    exit();
}
$veateade = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kasutaja = $_POST['user'] ?? '';
    $parool = $_POST['password'] ?? '';
    if (!empty($kasutaja) && !empty($parool)) {
        $stmt = $yhendus->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $kasutaja);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $rida = $result->fetch_assoc();
            if (password_verify($parool, $rida['password'])) {
                $_SESSION['tuvastamine'] = $kasutaja;
                header('Location: /admin');
                exit();
            } else {
                $veateade = "Vale parool.";
            }
        } else {
            $veateade = "Kasutajat ei leitud.";
        }
        $stmt->close();
    } else {
        $veateade = "Palun täida kõik väljad.";
    }
}
?>
<!doctype html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin login leht</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f0f4f8;
    }
    .login-container {
      max-width: 400px;
      margin: 5% auto;
      background: white;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    .btn-color {
      background-color: #0e1c36;
      color: #fff;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="login-container">
      <h3 class="text-center mb-4">Admin login leht</h3>
      <?php if (!empty($veateade)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($veateade) ?></div>
      <?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label for="user" class="form-label">Kasutaja</label>
          <input type="text" class="form-control" id="user" name="user" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Parool</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="remember" name="remember">
          <label class="form-check-label" for="remember">Mäleta mind</label>
        </div>
        <button type="submit" class="btn btn-color w-100">Login</button>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
