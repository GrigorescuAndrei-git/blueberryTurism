<?php
session_start();
include "database.php"; 

$sql = "SELECT
            r.*, u.nume, u.prenume, l.numeLocatie
        FROM
            recenzii r
        INNER JOIN
            utilizatori u ON r.user_ID = u.user_ID
        LEFT JOIN
            locatii l ON r.locatie_ID = l.locatie_ID
        WHERE
            r.vizibilitate = 1
        ORDER BY
            r.dataPostare DESC";

$result = $connect->query($sql);
$recenzii = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recenzii[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Recenzii Clienți - Blueberry T & E</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .scroll-rec-container {
            overflow-x: auto;
            white-space: nowrap;
            padding: 20px 0;
            display: flex;
            justify-content: flex-start; 
            align-items: flex-start;
        }
        .review-card {
            display: inline-block;
            width: 300px;
            margin-right: 16px; 
            vertical-align: top;
            flex-shrink: 0; 
        }
        .card-body {
            display: flex;
            flex-direction: column;
            height: auto;
            overflow: visible;
        }
        .card-text {
            margin-bottom: 10px;
            white-space: normal;
        }
        .no-reviews-message {
            width: 100%;
            text-align: center;
            padding: 50px 0;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <h1 class="mb-4 text-center">Ce spun clienții Blueberry T & E?</h1>

    <div class="scroll-rec-container">
        <?php if (!empty($recenzii)): ?>
            <?php foreach ($recenzii as $recenzie): ?>
                <div class="card review-card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($recenzie['prenume'] . ' ' . $recenzie['nume']) ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            Data: <?= date("d.m.Y", strtotime($recenzie['dataPostare'])) ?>
                            <?php
                            if (!empty($recenzie['numeLocatie'])) {
                                echo '<br>Locație: ' . htmlspecialchars($recenzie['numeLocatie']);
                            } else {
                                echo '<br>Locație: Nespecificat';
                            }
                            ?>
                        </h6>
                        <p class="card-text"><?= nl2br(htmlspecialchars($recenzie['comentariu'])) ?></p>
                        <p class="mt-3 mb-0"><strong>Rating:</strong> <?= htmlspecialchars($recenzie['rating']) ?>/5</p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted no-reviews-message">Momentan nu există recenzii disponibile.</p>
        <?php endif; ?>
    </div>

    <div class="mt-4 text-center">
        <a href="locatii.php" class="btn btn-primary">Vezi ofertele noastre</a>
        <a href="adauga_recenzie.php" class="btn btn-secondary">Adauga o recenzie</a>
    </div>

</div>

</body>
</html>