<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include("includes/db.php");

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM reservations WHERE id = $id");
$reservation = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = $_POST['name'];
    $email  = $_POST['email'];
    $phone  = $_POST['phone'];
    $guests = (int)$_POST['guests'];
    $date   = $_POST['date'];
    $time   = $_POST['time'];
    $notes  = $_POST['notes'];

    if ($guests < 1 || $guests > 10) {
        $error = "Броят гости трябва да е между 1 и 10!";
    } elseif (!preg_match('/^\d{10}$/', $phone)) {
        $error = "Телефонът трябва да съдържа точно 10 цифри!";
    } else {
        $stmt = $conn->prepare("UPDATE reservations SET name=?, email=?, phone=?, guests=?, date=?, time=?, notes=? WHERE id=?");
        $stmt->bind_param("sssisssi", $name, $email, $phone, $guests, $date, $time, $notes, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Редактиране на резервация</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>Редактиране на резервация</h2>

<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST" novalidate id="editForm">
    <div class="mb-3">
        <label>Име</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($reservation['name']) ?>" required pattern="^[А-Яа-яA-Za-z\s]+$" title="Името трябва да съдържа само букви">
    </div>
    <div class="mb-3">
        <label>Имейл</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($reservation['email']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Телефон</label>
        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($reservation['phone']) ?>" maxlength="10" pattern="\d{10}" required>
        <p id="phoneError" style="color:red;"></p>
    </div>
    <div class="mb-3">
        <label>Гости</label>
        <input type="number" name="guests" id="guests" class="form-control" value="<?= htmlspecialchars($reservation['guests']) ?>" min="1" max="10" required>
        <p id="guestsError" style="color:red;"></p>
    </div>
    <div class="mb-3">
        <label>Дата</label>
        <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($reservation['date']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Час</label>
        <input type="time" name="time" class="form-control" value="<?= htmlspecialchars($reservation['time']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Бележки</label>
        <textarea name="notes" class="form-control" maxlength="250"><?= htmlspecialchars($reservation['notes']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Запази</button>
    <a href="dashboard.php" class="btn btn-secondary">Отказ</a>
</form>

<script>

const guestsInput = document.getElementById('guests');
const guestsError = document.getElementById('guestsError');
const phoneInput = document.querySelector('input[name="phone"]');
const phoneError = document.getElementById('phoneError');
const form = document.getElementById('editForm');

guestsInput.addEventListener('input', () => {
    if (guestsInput.value < 1 || guestsInput.value > 10) {
        guestsError.textContent = "Броят гости трябва да е между 1 и 10!";
    } else {
        guestsError.textContent = "";
    }
});

phoneInput.addEventListener('input', () => {
    if (!/^\d{0,10}$/.test(phoneInput.value)) {
        phoneError.textContent = "Телефонът трябва да съдържа до 10 цифри!";
    } else {
        phoneError.textContent = "";
    }
});

form.addEventListener('submit', (e) => {
    if (guestsInput.value < 1 || guestsInput.value > 10 || !/^\d{10}$/.test(phoneInput.value)) {
        e.preventDefault();
    }
});
</script>

</body>
</html>

