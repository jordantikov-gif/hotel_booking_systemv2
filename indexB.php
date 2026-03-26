<?php
include("includes/db.php");

// Максимален капацитет за всеки ден
$capacityPerDay = [
    1 => 5, 2 => 8, 3 => 0, 4 => 6, 5 => 7, 6 => 4,
    7 => 9, 8 => 2, 9 => 10, 10 => 5, 11 => 7, 12 => 3,
    13 => 6, 14 => 8, 15 => 0, 16 => 9, 17 => 4, 18 => 10,
    19 => 7, 20 => 5, 21 => 6, 22 => 3, 23 => 8, 24 => 4,
    25 => 10, 26 => 6, 27 => 5, 28 => 7, 29 => 0, 30 => 9, 31 => 8
];

$hotel = "Хотел Б"; // Можеш да сменяш

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name   = $_POST['name'];
    $email  = $_POST['email'];
    $phone  = $_POST['phone'];
    $guests = (int)$_POST['guests'];
    $time   = $_POST['time'];
    $notes  = $_POST['notes'];
    $dates  = $_POST['dates'] ?? '';
    $price  = $_POST['price'] ?? 0;

    if ($guests < 1 || $guests > 10) {
        $error = "Броят гости трябва да е между 1 и 10!";
    } else {
        $datesArray = explode(',', $dates);
        foreach ($datesArray as $d) {
            $stmt = $conn->prepare(
                "INSERT INTO reservations (hotel, name, email, phone, guests, date, time, notes, price)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("sssissssd", $hotel, $name, $email, $phone, $guests, $d, $time, $notes, $price);
            if (!$stmt->execute()) {
                $error = "Грешка при записване: " . $stmt->error;
            }
            $stmt->close();
        }
        $success = "Резервацията за $hotel е успешна!";
    }
}

// Актуализиране на капацитета според резервациите само за този хотел
$currentCapacity = $capacityPerDay;

$stmt = $conn->prepare("
    SELECT DAY(date) AS day_number, SUM(guests) AS booked
    FROM reservations
    WHERE MONTH(date)=10 AND YEAR(date)=2025 AND hotel = ?
    GROUP BY DAY(date)
");
$stmt->bind_param("s", $hotel);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $day = (int)$row['day_number'];
    $booked = (int)$row['booked'];
    if (isset($currentCapacity[$day])) {
        $currentCapacity[$day] -= $booked;
        if ($currentCapacity[$day] < 0) $currentCapacity[$day] = 0;
    }
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
<meta charset="UTF-8">
<title>Резервация - <?php echo $hotel; ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color: #003580; font-family: Arial, Helvetica, sans-serif; color: #fff; padding: 20px; }
.form-container { width: 460px; background: #fff; padding: 25px 20px 30px 20px; border-radius: 12px; box-shadow: 0 6px 15px rgba(0,0,0,0.3); color: #333; }
.calendar-header { display: flex; justify-content: space-between; align-items: center; margin: 15px 0; }
.calendar-header button { background: #003580; color: #fff; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.95rem; }
.calendar { display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; margin-top: 10px; }
.day { padding: 8px; text-align: center; border: 1px solid #ddd; border-radius: 8px; cursor: pointer; background: #fff; transition: 0.2s; height: 55px; display: flex; flex-direction: column; justify-content: center; font-size: 0.9rem; }
.day span { font-size: 0.75rem; margin-top: 3px; }
.day:hover { background-color: #ffeb99; }
.day.selected { background-color: #3498db; color: white; }
.day.booked { background-color: #e74c3c; color: white; cursor: not-allowed; }
#totalPrice { font-size: 1.1rem; color: #007bff; margin-top: 12px; text-align: center; }
</style>
</head>
<body class="d-flex justify-content-center align-items-start vh-100 pt-5">
<div class="form-container">
<h2 class="mb-3 text-center">Направи резервация - <?php echo $hotel; ?></h2>
<?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST">
<input type="text" name="name" class="form-control mb-2" placeholder="Име" required pattern="^[А-Яа-яA-Za-z\s]+$">
<input type="email" name="email" class="form-control mb-2" placeholder="Имейл" required>
<input type="text" name="phone" class="form-control mb-2" placeholder="Телефон" maxlength="10" pattern="\d{10}" required>
<input type="number" name="guests" class="form-control mb-2" placeholder="Брой гости" min="1" max="10" required>
<input type="time" name="time" class="form-control mb-2">
<label class="form-label mt-2">Избери дати</label>
<div class="calendar-header">
<button type="button" id="prevM">◀</button>
<h4 id="monthLabel"></h4>
<button type="button" id="nextM">▶</button>
</div>
<div id="calendar" class="calendar"></div>
<input type="hidden" name="dates" id="dates">
<input type="hidden" name="price" id="price">
<textarea name="notes" class="form-control mb-3 mt-2" placeholder="Бележки" maxlength="250"></textarea>
<button type="submit" class="btn btn-primary w-100">Резервирай</button>
<p id="totalPrice"></p>
</form>
</div>

<script>
const calendarEl = document.getElementById("calendar");
const hiddenDates = document.getElementById("dates");
let selectedDates = [];
let currentCapacity = <?php echo json_encode($currentCapacity); ?>;
const pricePerNight = 80;
const guestsInput = document.querySelector('input[name="guests"]');
const totalPriceEl = document.getElementById('totalPrice');

const currentMonth = 9; // октомври
const currentYear = 2025;
const monthNames = ["Януари","Февруари","Март","Април","Май","Юни","Юли","Август","Септември","Октомври","Ноември","Декември"];

function pad(n){ return n.toString().padStart(2,'0'); }
function formatDate(d,m,y){ return y + "-" + pad(m+1) + "-" + pad(d); }

function updatePrice() {
    const guests = parseInt(guestsInput.value) || 0;
    const nights = selectedDates.length;
    if (guests>0 && nights>0) {
        const total = guests*nights*pricePerNight;
        totalPriceEl.textContent = `Обща цена: ${total} лева`;
        document.getElementById("price").value = total;
    } else {
        totalPriceEl.textContent = '';
        document.getElementById("price").value = 0;
    }
}

guestsInput.addEventListener('input', updatePrice);

function renderCalendar(year, month) {
    calendarEl.innerHTML = "";
    const daysInMonth = new Date(year, month+1, 0).getDate();
    document.getElementById("monthLabel").innerText = monthNames[month]+" "+year;

    for (let d=1; d<=daysInMonth; d++) {
        const div = document.createElement("div");
        div.classList.add("day");
        let free = currentCapacity[d] || 0;

        if (free<=0) {
            div.classList.add("booked");
            div.innerHTML = d + "<span>X</span>";
        } else {
            div.innerHTML = d + "<span>"+free+" места</span>";
            div.addEventListener("click", ()=>{
                const guests = parseInt(guestsInput.value)||0;
                if(guests<=0){ alert("Моля, въведете брой гости"); return; }
                if(guests>free){ alert("Няма достатъчно свободни места за този ден"); return; }

                currentCapacity[d] -= guests;
                free = currentCapacity[d];

                if(free<=0){
                    div.classList.add("booked");
                    div.classList.remove("selected");
                    div.innerHTML = d + "<span>X</span>";
                } else {
                    div.innerHTML = d + "<span>"+free+" места</span>";
                    div.classList.add("selected");
                }

                const dateStr = formatDate(d, month, year);
                if(!selectedDates.includes(dateStr)) selectedDates.push(dateStr);
                hiddenDates.value = selectedDates.join(",");
                updatePrice();
            });
        }
        calendarEl.appendChild(div);
    }
}

document.getElementById("prevM").onclick = ()=>false;
document.getElementById("nextM").onclick = ()=>false;

renderCalendar(currentYear,currentMonth);
</script>
</body>
</html>
