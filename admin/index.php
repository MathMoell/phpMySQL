<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Paneel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            color: white;
            padding-top: 50px;
        }
        .container {
            max-width: 900px;
            background: rgba(255,255,255,0.15);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        h1 {
            font-weight: 700;
        }
        a.btn {
            border-radius: 25px;
            font-weight: 600;
            padding: 0.75rem 1.75rem;
            transition: all 0.3s ease;
        }
        a.btn-primary {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            color: white;
        }
        a.btn-primary:hover {
            background: linear-gradient(45deg, #20c997, #28a745);
            box-shadow: 0 5px 15px rgba(32,201,151,0.6);
            color: white;
        }
        a.btn-secondary {
            background: rgba(255,255,255,0.9);
            color: #333;
            border: none;
        }
        a.btn-secondary:hover {
            background: white;
            color: #333;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <h1>Tere tulemast, <?php echo htmlspecialchars($_SESSION['admin_kasutajanimi']); ?>!</h1>
        <p class="mt-4">
            <a href="filmid.php" class="btn btn-primary me-3">
                <i class="fas fa-film"></i> Halda filme
            </a>
            <a href="broneeringud.php" class="btn btn-primary me-3">
                <i class="fas fa-ticket-alt"></i> Halda broneeringuid
            </a>
            <a href="logout.php" class="btn btn-secondary">
                <i class="fas fa-sign-out-alt"></i> Logi välja
            </a>
        </p>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
