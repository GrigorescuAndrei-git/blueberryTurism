<?php
session_start();
include "database.php";

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

$query = "SELECT * FROM utilizatori WHERE email = '$email'";
$result = mysqli_query($connect, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $sex = mysqli_real_escape_string($connect, $_POST['sex']);
    $age = mysqli_real_escape_string($connect, $_POST['age']);
    
    $update_query = "UPDATE utilizatori SET username = '$username', sex = '$sex', varsta = '$age' WHERE email = '$email'";
    if (mysqli_query($connect, $update_query)) {
        echo "Profilul a fost actualizat cu succes!";
        $_SESSION['username'] = $username;
    } else {
        echo "Eroare la actualizarea profilului: " . mysqli_error($connect);
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilul Meu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
    <h2>Bine ai venit pe pagina profilului tău, <?php echo htmlspecialchars($user['username']); ?></h2>
        <p>ID: <?php echo $user['user_ID']; ?></p>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" placeholder="Introduceti username-ul">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" disabled placeholder="Email-ul nu poate fi modificat">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Parola</label>
                <input type="text" class="form-control" id="password" name="password" value="" placeholder="Introdu noua parola!">
            </div>
            <div class="mb-3">
                <label for="sex" class="form-label">Sex</label>
                <select class="form-select" id="sex" name="sex">
                    <option value="masculin" <?php if ($user['sex'] == 'masculin') echo 'selected'; ?>>Masculin</option>
                    <option value="feminin" <?php if ($user['sex'] == 'feminin') echo 'selected'; ?>>Feminin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success btn-sm">Salvează modificările</button>
             <a href="main.php" class="btn btn-secondary btn-sm">Întoarce-te!</a>
        </form>
    </div>
</body>
</html>