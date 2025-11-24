<?php
session_start();
include "database.php";

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$expeditor_email = $connect->real_escape_string($_SESSION['email']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destinatar_email = $connect->real_escape_string(trim($_POST['destinatar_email'] ?? ''));
    $mesaj = $connect->real_escape_string(trim($_POST['mesaj'] ?? ''));

    if ($destinatar_email !== '' && $mesaj !== '') {
        $sql = "SELECT user_ID FROM utilizatori WHERE email = '$destinatar_email'";
        $result = $connect->query($sql);
        if ($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $destinatar_ID = $row['user_ID'];

            $sql2 = "SELECT user_ID FROM utilizatori WHERE email = '$expeditor_email'";
            $result2 = $connect->query($sql2);
            if ($result2 && $result2->num_rows === 1) {
                $row2 = $result2->fetch_assoc();
                $expeditor_ID = $row2['user_ID'];

                $sqlInsert = "INSERT INTO mesajeprivate (mesaj, destinatar_ID, expeditor_ID, dataMesaj) 
                              VALUES ('$mesaj', $destinatar_ID, $expeditor_ID, NOW())";
                if ($connect->query($sqlInsert) === TRUE) {
                    $success_msg = "Mesajul a fost trimis.";
                } else {
                    $error_msg = "Eroare la trimiterea mesajului.";
                }
            } else {
                $error_msg = "Eroare: expeditor invalid.";
            }
        } else {
            $error_msg = "Nu există utilizator cu acest email destinatar.";
        }
    } else {
        $error_msg = "Completează toate câmpurile corect.";
    }
}

$sqlMesaje = "SELECT mesajeprivate.mesaj, mesajeprivate.dataMesaj, utilizatori.email, utilizatori.nume, utilizatori.prenume 
    FROM mesajeprivate INNER JOIN utilizatori ON mesajeprivate.expeditor_ID = utilizatori.user_ID 
    WHERE mesajeprivate.destinatar_ID = (SELECT user_ID FROM utilizatori WHERE email = '$expeditor_email') ORDER BY mesajeprivate.dataMesaj DESC";

$resultMesaje = $connect->query($sqlMesaje);
$lista_mesaje = [];
if ($resultMesaje) {
    while ($rowMesaj = $resultMesaje->fetch_assoc()) {
        $lista_mesaje[] = $rowMesaj;
    }
}

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8" />
    <title>Mesaje private</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<div class="container py-4">

    <?php if (isset($success_msg)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_msg) ?></div>
    <?php elseif (isset($error_msg)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_msg) ?></div>
    <?php endif; ?>

    <form method="POST" action="" class="mb-4">
        <div class="mb-3">
            <label for="destinatar_email" class="form-label">Email destinatar</label>
            <input type="email" id="destinatar_email" name="destinatar_email" class="form-control" required />
        </div>
        <div class="mb-3">
            <label for="mesaj" class="form-label">Mesaj</label>
            <textarea id="mesaj" name="mesaj" class="form-control" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Trimite mesaj</button>
    </form>

    <h2>Mesaje primite</h2>
    <?php if (count($lista_mesaje) > 0): ?>
        <?php foreach ($lista_mesaje as $mesaj): ?>
            <div class="card mb-3">
                <div class="card-header">
                    De la: <?= htmlspecialchars($mesaj['email']) ?> - <?= htmlspecialchars($mesaj['nume'] . ' ' . $mesaj['prenume']) ?>
                    <span class="float-end text-muted" style="font-size: 0.9em;">
                        <?= date("d-m-Y H:i", strtotime($mesaj['dataMesaj'])) ?>
                    </span>
                </div>
                <div class="card-body">
                    <?= nl2br(htmlspecialchars($mesaj['mesaj'])) ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nu ai niciun mesaj</p>
    <?php endif; ?>
</div>

</body>
</html>
