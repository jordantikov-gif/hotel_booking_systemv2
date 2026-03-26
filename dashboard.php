<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include("includes/db.php");

$result = $conn->query("
    SELECT 
        MIN(id) AS id,
        hotel, name, email, phone, guests, time, price, notes, created_at,
        GROUP_CONCAT(DATE_FORMAT(date, '%d.%m.%Y') ORDER BY date ASC SEPARATOR ', ') AS dates
    FROM reservations
    GROUP BY hotel, name, email, phone, created_at
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Админ панел</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #003580; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
        }
        .container {
            max-width: 1300px;
        }
        .dashboard-box {
            background: #fff;
            border: 2px solid #febb02;   
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            color: #333;
        }
        h2, h3 {
            font-weight: 600;
            color: #fff;
        }
        table {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
        }
        thead {
            background-color: #0071c2; 
            color: white;
        }
        thead th {
            padding: 14px;
            font-size: 15px;
        }
        tbody td {
            padding: 12px;
            vertical-align: middle;
            color: #333;
        }
        tbody tr:nth-child(even) {
            background-color: #f9fbfd;
        }
        tbody tr:hover {
            background-color: #eef6ff;
        }
        .btn-sm {
            border-radius: 6px;
            font-size: 14px;
            padding: 6px 10px;
            margin: 2px;
        }
        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="header-row">
        <h2>Добре дошъл, <?php echo $_SESSION['admin']; ?>!</h2>
        <a href="logout.php" class="btn btn-secondary">Изход</a>
    </div>

    <div class="dashboard-box">
        <h3 class="mb-3">Списък с резервации</h3>
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Хотел</th>
                    <th>Име</th>
                    <th>Имейл</th>
                    <th>Телефон</th>
                    <th>Гости</th>
                    <th>Избрани дати</th>
                    <th>Час</th>
                    <th>Цена</th>
                    <th>Бележки</th>
                    <th>Създадена на</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['hotel']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['guests']) ?></td>
                    <td><?= htmlspecialchars($row['dates']) ?></td>
                    <td><?= htmlspecialchars($row['time']) ?></td>
                    <td><?= htmlspecialchars($row['price']) ?> лв</td>
                    <td><?= htmlspecialchars($row['notes']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Редактирай</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Изтрий</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
