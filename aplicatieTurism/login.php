<?php
session_start();
include 'database.php';

$error_message = '';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM utilizatori WHERE email = '$email'";

    $result = mysqli_query($connect, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['parola'])) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['admin_level'] = $user['admin_level'];
            header("Location: main.php");
            exit;
        } else {
            $error_message = "Parola este incorectă!";
        }
    } else {
        $error_message = "Email-ul nu există în baza de date!";
    }

    if (!$result) {
        die("Eroare SQL: " . mysqli_error($connect));
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blueberry Games - Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            background: rgb(34, 193, 195);
            background: linear-gradient(0deg, rgba(34, 193, 195, 1) 0%, rgba(253, 187, 45, 1) 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(10px);
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
        }

        h2 {
            font-size: 20px;
            color: #333;
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: #34c1c3;
            outline: none;
        }

        button {
            background-color: #34c1c3;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #2ba7a3;
        }

        .neinregistrat {
            font-size: 14px;
            color: #555;
        }

        .neinregistrat a {
            color: #34c1c3;
            text-decoration: none;
        }

        .neinregistrat a:hover {
            text-decoration: underline;
        }

        .campuriObligatorii {
            font-size: 12px;
            color: #555;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bine ai venit! Completează formularul de login</h2>
        <?php if ($error_message): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
            <input type="password" name="password" class="form-control" placeholder="Parola" required>
            <div>
                <button type="submit" name="submit" class="btn btn-success btn-sm">Autentificare</button>
            </div>

            <div class="form-group">
                <p class="neinregistrat">Nu ai un cont creat? <a href="signup.php">Înregistrează-te</a></p>
            </div>

            <div class="form-group center">
                <p class="campuriObligatorii">toate câmpurile sunt obligatorii</p>
            </div>
        </form>
    </div>
</body>
</html>
