<?php
session_start();
include "database.php";

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$user_email = $_SESSION['email'];
$query = "SELECT username, admin_level FROM utilizatori WHERE email = '$user_email'";
$result = mysqli_query($connect, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: login.php");
    exit;
}

$esteAdmin = $user['admin_level'];
$username = $user['username']; 
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blueberry T & E - Main</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: rgb(34, 193, 195);
            background: linear-gradient(0deg, rgba(34, 193, 195, 1) 0%, rgba(253, 187, 45, 1) 100%);
            color: white;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            overflow: hidden
        }

        .navbar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 220px;
            background-color: #282828;
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

        .navbar .user-name {
            background-color:rgb(34, 193, 195);
            padding: 10px 15px;
            border-radius: 50px;
            font-size: 18px;
            text-align: center;
            margin: 10px;
            font-weight: bold;
        }

        .content {
            margin-left: 250px; 
            padding: 50px;
            text-align: center;
            width: 100%;
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
            background-color:rgb(34, 193, 195);
        }

        .recenzii a {
            margin: 10px;
            color: white;
            font-size: 24px;
            text-decoration: none;
        }

        .recenzii a:hover {
            color:rgb(34, 193, 195);
        }

        .informatii-container {
            margin-top: 30px;
            background-color: #282828;
            padding: 40px;
            border-radius: 15px;
        }

        .informatii-container h2 {
            margin-bottom: 20px;
            color: rgb(34, 193, 195);
        }

        .informatii-container p {
            margin-bottom: 30px;
        }

        .informatii-container .btn {
            background-color: rgb(34, 193, 195);
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="user-name"><?php echo $username; ?></div>
        
        <?php if ($esteAdmin == 1) { ?>
            <a href="administrare.php">Admin</a>
        <?php } ?>

        <a href="profil.php">Profil</a>
        <a href="oferte.php">Oferte</a>
        <a href="locatii.php">Locații</a>
        <a href="mesajeprivate.php">Mesaje!</a>
        <a href="turism.php">Info</a>
        <a href="logout.php">Deconectează-te</a>
    </div>
    
    <div class="content">
        <div class="informatii-container">
            <h2>Bine ai venit pe forumul nostru, Blueberry - Turism & Events!</h2>
            <p>Verificați ofertele locațiilor pentru a descoperii evenimente gratuite!</p>
            <p>Descoperă ofertele noastre și pleacă într-o vacanță de neuitat alături de persoanele iubite!</p>
            <a href="locatii.php" class="btn btn-custom">Vezi locatii!</a>
        </div>
        
        <div class="recenzii">
            <h4>Lasă-ne feedback și/sau verifică recenziile clienților noștrii! <target="_blank"></h4>
            <a href="adauga_recenzie.php" class="btn btn-success">Adaugă o recenzie!</a>
            <a href="recenzie.php" class="btn btn-success">Verifică recenziile!</a>
            <a href="turism.php" class="btn btn-success">Info</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
