<?php
include "database.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'] ?? '';
    $surname = trim($_POST['nume'] ?? '');
    $name = trim($_POST['prenume'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $data_nasterii = $_POST['data_nasterii'] ?? '';
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $sex = ($_POST['sex'] ?? '');
    $age = ($_POST['age'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $numarTelefon = ($_POST['numarTelefon'] ?? '');
    //var_dump($password); //

    $email_check = "SELECT * FROM utilizatori WHERE email = '$email'";
    $username_check = "Select * FROM utilizatori WHERE username = '$username'";

    $email_result = mysqli_query($connect, $email_check);
    $username_result = mysqli_query($connect, $username_check);

    if (mysqli_num_rows($email_result) > 0) {
        echo "Email-ul este deja folosit";
        exit;
    }

    if (mysqli_num_rows($username_result) > 0) {
        echo "Username-ul este deja folosit";
        exit;
    }

    if ($password !== $confirm_password) {
        echo "Parolele trebuia sa coincida";
        exit;
    }

    $parola_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO utilizatori (username, nume, prenume, email, dataNasterii, numarTelefon, parola, dataInregistrare, sex, varsta) VALUES ('$username', '$surname', '$name', '$email', '$data_nasterii', '$numarTelefon', '$parola_hash', 
    current_timestamp(), '$sex', '$age')";

    //var_dump($_POST); //

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email invalid!";
        exit;
    }

    //var_dump($_POST); //

    if (empty($password)) {
        echo "Parola nu poate fi goală!";
        exit;
    }

    if (mysqli_query($connect, $sql)) {
        header("Location: login.php");
        echo "Ai fost înregistrat cu succes!";
    } else {
        echo 'Eroare la înregistrare: ' . mysqli_error($connect);
    }
}

?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>T & E - Register</title>
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

        select.form-control {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
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

        .dejaConectat {
            font-size: 14px;
            color: #555;
        }

        .dejaConectat a {
            color: #34c1c3;
            text-decoration: none;
        }

        .dejaConectat a:hover {
            text-decoration: underline;
        }

        .campuriObligatorii {
            font-size: 12px;
            color: #555;
        }

        .center {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bine ai venit! Completează formularul de înregistrare</h2>
        <form action="signup.php" method="post">
            <input type="text" name="username" class="form-control" placeholder="Nume de utilizator" required>
            <input type="text" name="nume" class="form-control" placeholder="Nume de familie" required>
            <input type="text" name="prenume" class="form-control" placeholder="Prenume" required>
            <input type="email" name="email" class="form-control" placeholder="Email" required>
            <input type="password" name="password" class="form-control" placeholder="Parola" required>
            <input type="password" name="confirm_password" class="form-control" placeholder="Confirmă parola" required>
            <input type="date" name="data_nasterii" class="form-control" placeholder="Data nașterii" required>
            <input type="text" name="numarTelefon" id = "number" class="form-control" placeholder="Numar de telefon" 
            pattern = "\d{10}" maxlenght="10" title = "Numarul telefon trebuie sa aiba minimum si maximum 10 cifre" required>

            <div class="mb-3">
                <label for="sex" class="form-label"></label>
                <select name="sex" class="form-control" required>
                    <option value="" disabled selected>Selectează*</option>
                    <option value="masculin">Masculin</option>
                    <option value="feminin">Feminin</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-success btn-sm">Înregistrează-te</button>
            </div>

            <div class="form-group">
                <p class="dejaConectat">Ai deja un cont creat? <a href="login.php">Autentifică-te</a></p>
            </div>

            <div class="form-group center">
                <p class="campuriObligatorii">câmpurile notate cu * sunt obligatorii</p>
            </div>
        </form>
    </div>
</body>
</html>