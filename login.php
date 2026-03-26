<?php
session_start();
include("includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && $admin['password'] === $password) {
        $_SESSION['admin'] = $admin['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Невалидно потребителско име или парола!";
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Админ Вход</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #003580; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
        }
        .login-box {
            background: #fff;
            border: 2px solid #febb02;
            border-radius: 12px;
            padding: 30px 25px;
            width: 350px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            color: #333;
            text-align: center;
        }
        .login-box h2 {
            margin-bottom: 20px;
            color: #003580;
            font-weight: 600;
        }
        .form-control {
            border-radius: 8px;
        }
        .btn-primary {
            background-color: #0071c2;
            border: none;
            border-radius: 8px;
        }
        .btn-primary:hover {
            background-color: #005fa3;
        }
        p.error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

    <div class="login-box">
        <h2>Вход за администратор</h2>

        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST">
            <input type="text" name="username" class="form-control mb-3" placeholder="Потребител" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Парола" required>
            <button type="submit" class="btn btn-primary w-100">Вход</button>
        </form>
    </div>

</body>
</html>
