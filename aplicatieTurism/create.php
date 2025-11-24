<?php
session_start();
include 'database.php';

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($connect, $_POST['name']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $number = mysqli_real_escape_string($connect, $_POST['number']);
    $gender = mysqli_real_escape_string($connect, $_POST['gender']);
    // $date = mysqli_real_escape_string($connect, $_POST['date']);
    $password = mysqli_real_escape_string($connect, $_POST['text']);
    $age = mysqli_real_escape_string($connect, $_POST['age']);
    $address = isset($_POST['address']) ? mysqli_real_escape_string($connect, $_POST['address']) : null;
    $admin_level = isset($_POST['admin_level']) ? mysqli_real_escape_string($connect, $_POST['admin_level']) : 0;

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO utilizatori (username, email, numarTelefon, sex, dataInregistrare, parola, adresa, admin_level, varsta)
            VALUES ('$username', '$email', '$number', '$gender', current_timestamp(), '$hashed_password', '$address', '$admin_level', '$age')";

    if (mysqli_query($connect, $sql)) {
        echo "Utilizatorul a fost adăugat cu succes!";
    } else {
        echo "Eroare la adăugarea utilizatorului: " . mysqli_error($connect);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <title>T & E - Profil</title>
</head>

<body>
    <div class="container my-5">
        <h1>Adauga un utilizator nou</h1>
        <br>
        <br>
        <form action="" method="post">
            <div class="row mb-3">
                <label for="" class="col-sm-3 col-form-label">Nume de utilizator*</label>
                <div class="col-sm-6">
                    <input type="text" name="name" class="form-control" placeholder="Nume de utilizator..." required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="" class="col-sm-3 col-form-label">Email*</label>
                <div class="col-sm-6">
                    <input type="text" name="email" class="form-control" placeholder="Email..." required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="" class="col-sm-3 col-form-label">Vârstă*</label>
                <div class="col-sm-6">
                    <input type="number" name="age" id = "age" class="form-control" placeholder="Varsta..." required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="" class="col-sm-3 col-form-label">Număr de Telefon*</label>
                <div class="col-sm-6">
                    <input type="text" name="number" class="form-control" placeholder="Numar de Telefon..." required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="" class="col-sm-3 col-form-label">Sex*</label>
                <div class="col-sm-6">
                    <select name="gender" class="form-select" required>
                        <option value="">Selectati...</option>
                        <option value="masculin">Masculin</option>
                        <option value="feminin">Feminin</option>
                    </select>
                </div>
            </div>
            <!-- <div class="row mb-3">
                <label for="" class="col-sm-3 col-form-label">Data Înregistrării*</label>
                <div class="col-sm-6">
                    <input type="date" name="date" class="form-control" placeholder="(dd/mm/yyyy)" required>
                </div>
            </div> -->
            <div class="row mb-3">
                <label for="" class="col-sm-3 col-form-label">Parolă*</label>
                <div class="col-sm-6">
                    <input type="password" name="text" class="form-control" placeholder="Parola..." required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="" class="col-sm-3 col-form-label">Adresă</label>
                <div class="col-sm-6">
                    <input type="text" name="address" class="form-control" placeholder="Adresa (optional)...">
                </div>
            </div>
            <div class="row mb-3">
                <label for="" class="col-sm-3 col-form-label">Admin</label>
                <div class="col-sm-6">
                    <input type="text" name="admin_level" class="form-control" placeholder="Statut Admin (0 = UTILIZATOR // 1 = ADMIN)">
                </div>
            </div>
            <div class="row mb-3">
                <button type="submit" name="submit" class="col-sm-3 btn btn-success">Submit</button>
                <div class="col-sm-6">
                    <a href="administrare.php" class="btn btn-secondary">Întoarce-te!</a>
                </div>
            </div>
        </form>
</body>
</html>