<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Главна страница</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #003580;
            font-family: Arial, Helvetica, sans-serif;
            color: #ffffff;
            padding: 20px;
        }
        .container { max-width: 1300px; }
        h1, p.lead { color: #ffffff; }
        .row { align-items: stretch; }
        .hotel-card {
            display: block;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.18);
            transition: transform 0.18s ease;
            background: #ffffff;
            color: inherit;
            text-decoration: none;
        }
        .hotel-card:hover { transform: translateY(-6px); }
        .hotel-img-wrapper {
            border-radius: 12px;
            padding: 6px;
            background: linear-gradient(180deg, rgba(254,187,2,0.2), transparent 60%);
            box-shadow: 0 0 18px rgba(254,187,2,0.25);
            position: relative; 
        }
        .hotel-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 8px;
            display: block;
        }
        .card-body { padding: 16px; }
        h3 { margin: 0 0 6px; color: #111; font-size: 1.25rem; }
        .contact { font-size: 0.95rem; color: #555; margin: 0; }
        .price {
            color: #ff5a5f; 
            font-weight: bold;
            font-size: 1rem;
        }
        @media (max-width:767px) {
            .hotel-img { height: 160px; }
            .col-md-6 { flex: 0 0 100%; max-width: 100%; }
        }

       
        .bonus-boxes {
            position: absolute;
            top: 10px;
            left: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        .bonus-box {
            background-color: #ffffff;
            border: 2px solid #fecb02;
            color: #111;
            padding: 4px 8px;
            font-size: 0.75rem;
            font-weight: bold;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            text-align: center;
        }
        .destination {
    font-size: 0.65rem;      /* размер на текста */
    font-weight: 500;         /* дебелина на шрифта, 600 е semi-bold */
    color: #111;              /* тъмен цвят за контраст върху бял фон */
    text-align: right;         /* подравняване в ляво */
    margin: 10px 0 0 0;        /* разстояние отгоре */
    font-family: Arial, Helvetica, sans-serif; /* промяна на шрифта */
}
    </style>
</head>
<body>
<div class="container mt-5 text-center">
    <h1 class="mb-4">Добре дошли в Резиденция Sunnymoon!</h1>
    <p class="lead mb-5">Изберете хотел или вижте информация</p>

    <div class="row justify-content-center g-4">
        
        <div class="col-md-6 col-lg-5">
            <a href="insidehotelA.html" class="hotel-card">
                <div class="hotel-img-wrapper">
                    <img src="https://m.bgvakancia.com/common_images/offers/782/bcd3abe99e67eb9695c5652919e04cd5.jpg" class="hotel-img" alt="Хотел А">
                    <div class="bonus-boxes">
                        <div class="bonus-box">Безплатен Wi-Fi</div>
                        <div class="bonus-box">Закуска и вечеря включена</div>
                        <div class="bonus-box">Отлични отзиви</div>
                        <div class="bonus-box">Паркинг</div>
                    </div>
                </div>
                <div class="card-body">
                    <h3>Хотел А</h3>
                    <p class="contact mb-2">Телефон: +359 888 111 222</p>
                    <p class="contact mb-1">Имейл: info@hotelA.bg</p>
                    <p class="price mb-0">Цени на нощувка от 80 лева</p>
                     <p class="destination mb-0"> На 650 метра от морето 🏖️</p>
                </div>
            </a>
        </div>

        
        <div class="col-md-6 col-lg-5">
            <a href="insidehotelB.html" class="hotel-card">
                <div class="hotel-img-wrapper">
                    <img src="https://cf.bstatic.com/xdata/images/hotel/max1024x768/709567033.jpg?k=2fdbeca07910c7acc7192b8a35b959f193484b0355da09446a40ea4ac215212a&o=" class="hotel-img" alt="Хотел Б">
                    <div class="bonus-boxes">
                        <div class="bonus-box">Безплатен Wi-Fi</div>
                        <div class="bonus-box">Закуска включена</div>
                        <div class="bonus-box">Отлични отзиви</div>
                        <div class="bonus-box">Паркинг</div>
                    </div>
                </div>
                <div class="card-body">
                    <h3>Хотел Б</h3>
                    <p class="contact mb-1">Телефон: +359 888 333 444</p>
                    <p class="contact mb-0">Имейл: info@hotelB.bg</p>
                    <p class="price mb-0">Цени на нощувка от 70 лева</p>
                    <p class="destination mb-0"> На 750 метра от морето 🏖️</p>
                </div>
            </a>
        </div>
    </div>
</div>
</body>
</html>
