<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'database.php';

if (!$connect) {
    die("<div class='alert alert-danger' role='alert'>Conexiunea la baza de date a eșuat: " . mysqli_connect_error() . "</div>");
}

$data_reuniune = [];
$data_diferenta = [];
$display_reuniune = false;
$display_diferenta = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reuniune'])) {
        $display_reuniune = true;
        $sql_reuniune = <<<SQL
        SELECT
            locatie_ID AS ID_Entitate, numeLocatie AS Nume_Entitate, 'Locatie' AS Tip_Entitate FROM locatii UNION ALL
        SELECT
            event_ID AS ID_Entitate, nume_Event AS Nume_Entitate, 'Eveniment' AS Tip_Entitate
        FROM 
            evenimente
        ORDER BY
            Tip_Entitate, Nume_Entitate;
SQL;

        $result_reuniune = mysqli_query($connect, $sql_reuniune);
        if ($result_reuniune) {
            while ($row = mysqli_fetch_assoc($result_reuniune)) {
                $data_reuniune[] = $row;
            }
        } else {
            $error_message = "Eroare SQL: " . mysqli_error($connect);
        }

    } elseif (isset($_POST['diferenta'])) {
        $display_diferenta = true;
        $sql_diferenta = <<<SQL
        SELECT
            l.locatie_ID, l.numeLocatie
        FROM
            locatii l
        WHERE
            l.locatie_ID IN (SELECT DISTINCT r.locatie_ID FROM recenzii r WHERE r.locatie_ID IS NOT NULL)
        AND
            l.locatie_ID NOT IN (SELECT el.locatie_ID FROM evenimente_locatii el);
SQL;

        $result_diferenta = mysqli_query($connect, $sql_diferenta);
        if ($result_diferenta) {
            while ($row = mysqli_fetch_assoc($result_diferenta)) {
                $data_diferenta[] = $row;
            }
        } else {
            $error_message = "Eroare SQL: " . mysqli_error($connect);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB T & E</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        .btn-group {
            margin-bottom: 30px;
        }
        h2 {
            margin-top: 30px;
            margin-bottom: 20px;
            color: #343a40;
        }
        .table thead th {
            background-color: #007bff;
            color: white;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">

    <?php if ($error_message): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="d-grid gap-2 d-md-flex justify-content-md-center btn-group" role="group" aria-label="Opțiuni operații">
            <button type="submit" name="reuniune" class="btn btn-primary btn-lg">Afișează locații & evenimente</button>
            <button type="submit" name="diferenta" class="btn btn-success btn-lg">Afișează locații cu recenzie fără evenimente</button>
        </div>
    </form>

    <?php if ($display_reuniune): ?>
        <h2>Toate locațiile și evenimentele</h2>
        <?php if (!empty($data_reuniune)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>ID Entitate</th>
                            <th>Nume Entitate</th>
                            <th>Tip Entitate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_reuniune as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['ID_Entitate']) ?></td>
                                <td><?= htmlspecialchars($row['Nume_Entitate']) ?></td>
                                <td><?= htmlspecialchars($row['Tip_Entitate']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($display_diferenta): ?>
        <h2>Locații care au recenzii, dar nu sunt asociate cu niciun eveniment</h2>
        <?php if (!empty($data_diferenta)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>ID Locație</th>
                            <th>Nume Locație</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_diferenta as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['locatie_ID']) ?></td>
                                <td><?= htmlspecialchars($row['numeLocatie']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>