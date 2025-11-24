<?php
session_start();
include 'database.php';

$useriPerPagina = 5;

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$start_from = ($page - 1) * $useriPerPagina;

$search_query_sql = "";
$search_param = "";
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $search = mysqli_real_escape_string($connect, $_POST['search']);
    $search_query_sql = "WHERE username LIKE '%$search%' OR email LIKE '%$search%' OR nume LIKE '%$search%' OR prenume LIKE '%$search%'";
    $search_param = "&search=" . urlencode($search);
} elseif (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($connect, $_GET['search']);
    $search_query_sql = "WHERE username LIKE '%$search%' OR email LIKE '%$search%' OR nume LIKE '%$search%' OR prenume LIKE '%$search%'";
    $search_param = "&search=" . urlencode($search);
}

if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo '<div class="alert alert-success">Utilizatorul a fost șters cu succes!</div>';
}

$query = "SELECT user_ID, username, email, nume, prenume, numarTelefon, sex, dataInregistrare, dataNasterii, adresa, admin_level 
          FROM utilizatori " . $search_query_sql . " ORDER BY user_ID ASC LIMIT $start_from, $useriPerPagina";

$result = mysqli_query($connect, $query);
if (!$result) {
    die('Eroare la interogare: ' . mysqli_error($connect));
}

$total_query = "SELECT COUNT(*) FROM utilizatori " . $search_query_sql;
$total_result = mysqli_query($connect, $total_query);
$total_users = mysqli_fetch_array($total_result)[0];
$total_pages = ceil($total_users / $useriPerPagina);

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <title>T & E - Administrare Utilizatori</title>
    <style>
        .btn.btn-info.btn-sm {
            margin-bottom: 10px;
        }
        .search-form-group {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <h2>Listă Utilizatori</h2>
        <br>
        <form method="post" action="administrare.php" class="search-form-group">
            <input type="text" name="search" class="form-control" placeholder="Căutare după username, email, nume, prenume..." id="search_bar" value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn btn-primary">Căutare</button>
            <a href="create.php" class="btn btn-info btn-sm">Creează un utilizator nou</a>
        </form>
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="px-3">ID</th>
                    <th class="px-3">Username</th>
                    <th class="px-3">Nume</th>
                    <th class="px-3">Prenume</th>
                    <th class="px-3">Email</th>
                    <th class="px-3">Număr Telefon</th>
                    <th class="px-3">Sex</th>
                    <th class="px-3">Vârstă</th> <th class="px-3">Adresă</th>
                    <th class="px-3">Data Înregistrării</th>
                    <th class="px-3">Admin Level</th>
                    <th class="px-3">Acțiune</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($user = mysqli_fetch_assoc($result)) {
                ?>
                        <tr>
                            <td class="px-3"><?= htmlspecialchars($user['user_ID']) ?></td>
                            <td class="px-3"><?= htmlspecialchars($user['username']) ?></td>
                            <td class="px-3"><?= htmlspecialchars($user['nume']) ?></td>
                            <td class="px-3"><?= htmlspecialchars($user['prenume']) ?></td>
                            <td class="px-3"><?= htmlspecialchars($user['email']) ?></td>
                            <td class="px-3"><?= htmlspecialchars($user['numarTelefon']) ?></td>
                            <td class="px-3"><?= htmlspecialchars($user['sex']) ?></td>
                            <td class="px-3"><?= calculateAge($user['dataNasterii']) ?></td> <td class="px-3"><?= htmlspecialchars($user['adresa']) ?></td>
                            <td class="px-3"><?= htmlspecialchars($user['dataInregistrare']) ?></td>
                            <td class="px-3"><?= htmlspecialchars($user['admin_level']) ?></td>
                            <td class="px-3">
                                <a href="update.php?id=<?= (int)$user['user_ID'] ?>" class="btn btn-warning btn-sm">Editează</a>
                                <a href="stergereCont.php?id=<?= (int)$user['user_ID'] ?>&success=1" class="btn btn-danger btn-sm" onclick="return confirm('Ești sigur că vrei să ștergi acest utilizator? Această acțiune este ireversibilă!');">Șterge</a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="12" class="text-center">Nu s-au găsit utilizatori.</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <div class="pagini-container">
            <nav>
                <ul class="pagination">
                    <?php
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . $search_param . '">' . $i . '</a></li>';
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>
</body>
</html>