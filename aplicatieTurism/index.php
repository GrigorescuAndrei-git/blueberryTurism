<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blueberry T & E - Index</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: rgb(34, 193, 195);
            background: linear-gradient(0deg, rgba(34, 193, 195, 1) 0%, rgba(253, 187, 45, 1) 100%);
            color: white;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .main-content {
            display: flex;
            justify-content: space-between;
            width: 100%;
            height: 100%;
        }
        .navbar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 220px;
            background-color:rgb(58, 58, 58);
            display: flex;
            flex-direction: column;
            padding-top: 20px;
            z-index: 1000;
        }
        .navbar a {
            color: white;
            padding: 15px;
            text-align: center;
            text-decoration: none;
            font-size: 18px;
            border-radius: 25px;
            margin: 10px 0;
            transition: background-color 0.3s ease, transform 0.2s;
        }
        .navbar a:hover {
            background-color:rgb(34, 193, 195);
            transform: scale(1.05);
        }
        .content {
            margin-left: 250px; 
            padding: 50px;
            text-align: center;
            width: 100%;
            height: 100%;
        }
        .content h1 {
            font-size: 3em;
            margin-bottom: 30px;
        }
        .content p {
            font-size: 1.2em;
            margin-bottom: 50px;
        }
        .btn-custom {
            background-color:rgb(34, 193, 195);
            color: white;
            padding: 12px 30px;
            font-size: 18px;
            border-radius: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-custom:hover {
            background-color:rgb(179, 255, 0);
        }
        .socials a {
            margin: 10px;
            color: white;
            font-size: 24px;
            text-decoration: none;
        }
        .socials a:hover {
            color:rgb(78, 65, 55);
        }
        .informatii-container {
            margin-top: 30px;
            background-color: #282828;
            padding: 40px;
            border-radius: 15px;
        }
        .informatii-container h2 {
            margin-bottom: 20px;
            color:rgb(34, 193, 195);
        }
        .informatii-container p {
            margin-bottom: 30px;
        }
        .informatii-container .btn {
            background-color:rgb(34, 193, 195);
        }
    </style>
</head>
<body>

    <div class="main-content">
        <div class="navbar">
            <a class = "btn btn-dark btn-sm" href="login.php">Autentifică-te</a>
            <a class = "btn btn-dark btn-sm" href="signup.php">Înregistrează-te</a>
        </div>

        <div class="content">
        <div class="informatii-container">
            <h2>Bine ai venit pe forumul nostru, Blueberry Turism & Events!</h2>
            <p>Acesta este pagina noastră de întâmpinare, pentru a descoperii ofertele noastre, te rugăm autentifică-te!</p>
            <a href="login.php" class="btn btn-custom">Autentifică-te</a>
            <br>
            <br>
            <p>În cazul în care nu ai un cont înregistrat, apasă pe butonul de mai jos.</p>
            <a href="signup.php" class="btn btn-custom">Înregistrează-te</a>
        </div>

            <div class="socials">
                <h4>Urmărește-ne pe Social Media</h4>
                <a href="https://www.youtube.com/@Coacaze6" target="_blank"><i class="fab fa-youtube"></i></i> Youtube</a>
                <a href="https://www.instagram.com/andy.grigorescu/" target="_blank"><i class="fab fa-instagram"></i> Instagram</a>
            </div>
        </div>
    </div>
</body>
</html>