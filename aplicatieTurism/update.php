<?php
session_start();
include "database.php";

if (!isset($_SESSION['email']) || $_SESSION['admin_level'] != '1') {
    header("Location: login.php");
    exit;
}

function calculateAge($dob) {
    if (empty($dob) || $dob === "0000-00-00" || !strtotime($dob)) {
        return 'N/A';
    }
    try {
        $birthDate = new DateTime($dob);
        $currentDate = new DateTime();
        $age = $currentDate->diff($birthDate)->y;
        return $age;
    } catch (Exception $e) {
        return 'N/A';
    }
}

if (isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];

    $query = "SELECT 
                    user_ID, username, email, nume, prenume, numarTelefon, sex, dataInregistrare, dataNasterii, adresa, admin_level 
              FROM 
                    utilizatori 
              WHERE 
                    user_ID = $user_id";
    
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        echo "Utilizatorul nu a fost găsit!";
        exit;
    }

    if (isset($_POST['update'])) {
        $username = mysqli_real_escape_string($connect, $_POST['username']);
        $email = mysqli_real_escape_string($connect, $_POST['email']);
        $nume = mysqli_real_escape_string($connect, $_POST['nume']);
        $prenume = mysqli_real_escape_string($connect, $_POST['prenume']);
        $numarTelefon = mysqli_real_escape_string($connect, $_POST['numarTelefon']);
        $sex = mysqli_real_escape_string($connect, $_POST['sex']);
        $dataNasterii = mysqli_real_escape_string($connect, $_POST['dataNasterii']); 
        $adresa = mysqli_real_escape_string($connect, $_POST['adresa']);
        $admin_level = (int)$_POST['admin_level'];

        $update_query = "UPDATE utilizatori SET 
                            username = '$username', 
                            email = '$email', 
                            nume = '$nume',         
                            prenume = '$prenume',    
                            numarTelefon = '$numarTelefon', 
                            sex = '$sex', 
                            dataNasterii = '$dataNasterii', 
                            adresa = '$adresa', 
                            admin_level = $admin_level 
                         WHERE user_ID = $user_id";

        if (mysqli_query($connect, $update_query)) {
            header("Location: administrare.php?success=2");
            exit;
        } else {
            echo "Eroare la actualizarea utilizatorului: " . mysqli_error($connect);
        }
    }
} else {
    echo "ID-ul utilizatorului nu a fost furnizat!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blueberry T & E - Actualizare Utilizator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container my-5">
        <h2>Actualizează Utilizatorul: <?= htmlspecialchars($user['username']) ?></h2>
        
        <form action="update.php?id=<?= (int)$user['user_ID'] ?>" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="nume" class="form-label">Nume</label>
                <input type="text" class="form-control" name="nume" value="<?= htmlspecialchars($user['nume'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="prenume" class="form-label">Prenume</label>
                <input type="text" class="form-control" name="prenume" value="<?= htmlspecialchars($user['prenume'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="numarTelefon" class="form-label">Număr Telefon</label>
                <input type="text" class="form-control" name="numarTelefon" value="<?= htmlspecialchars($user['numarTelefon']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="sex" class="form-label">Sex</label>
                <select class="form-select" name="sex" required>
                    <option value="masculin" <?= ($user['sex'] == 'masculin') ? 'selected' : ''; ?>>Masculin</option>
                    <option value="feminin" <?= ($user['sex'] == 'feminin') ? 'selected' : ''; ?>>Feminin</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="dataNasterii" class="form-label">Data Nașterii</label>
                <input type="date" class="form-control" name="dataNasterii" value="<?= htmlspecialchars($user['dataNasterii'] ?? '') ?>" required>
                <small class="form-text text-muted">Format: AAAA-LL-ZZ (ex: 1990-01-15)</small>
            </div>
            <div class="mb-3">
                <label for="adresa" class="form-label">Adresă</label>
                <input type="text" class="form-control" name="adresa" value="<?= htmlspecialchars($user['adresa'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label for="admin_level" class="form-label">Nivel Admin</label>
                <select class="form-select" name="admin_level" required>
                    <option value="1" <?= ($user['admin_level'] == 1) ? 'selected' : ''; ?>>Admin</option>
                    <option value="0" <?= ($user['admin_level'] == 0) ? 'selected' : ''; ?>>Utilizator</option>
                </select>
            </div>
            
            <button type="submit" name="update" class="btn btn-success">Actualizează</button>
            <a href="administrare.php" class="btn btn-secondary ms-2">Înapoi la lista utilizatorilor</a>
        </form>
    </div>
</body>

</html>