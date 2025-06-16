<?php
session_start();
include("../config.php");

if (isset($_POST['submit'])) {
    $kasutajanimi = trim($_POST['kasutajanimi']);
    $parool = $_POST['parool'];

    $paring = "SELECT * FROM adminid WHERE kasutajanimi = ?";
    $stmt = mysqli_prepare($yhendus, $paring);
    mysqli_stmt_bind_param($stmt, "s", $kasutajanimi);
    mysqli_stmt_execute($stmt);
    $tulemus = mysqli_stmt_get_result($stmt);

    if ($admin = mysqli_fetch_assoc($tulemus)) {
        if (password_verify($parool, $admin['parool'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_kasutajanimi'] = $admin['kasutajanimi'];
            header("Location: index.php");
            exit();
        } else {
            $viga = "Vale parool.";
        }
    } else {
        $viga = "Sellist admini ei leitud.";
    }
}
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
        }
        .login-card {
            max-width: 380px;
            margin: auto;
            margin-top: 10vh;
            background: rgba(255, 255, 255, 0.9);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.25);
        }
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 30px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #764ba2, #667eea);
            box-shadow: 0 5px 15px rgba(118,75,162,0.6);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h3 class="mb-4 text-center text-primary">Admin Login</h3>
        <?php if (!empty($viga)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($viga); ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="mb-3">
                <label for="kasutajanimi" class="form-label">Kasutajanimi</label>
                <input type="text" id="kasutajanimi" name="kasutajanimi" class="form-control" required autofocus>
            </div>
            <div class="mb-4">
                <label for="parool" class="form-label">Parool</label>
                <input type="password" id="parool" name="parool" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary w-100">Logi sisse</button>
        </form>
    </div>
</body>
</html>
