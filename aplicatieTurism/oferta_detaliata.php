<?php
session_start();
include "database.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID ofertă invalid.");
}

$id = intval($_GET['id']);

$sql_locatie = "SELECT 
                    l.*, o.nume_oras AS oras_nume, t.nume_tara AS tara_nume 
                FROM 
                    locatii l
                LEFT JOIN 
                    orase o ON l.oras_ID = o.oras_ID
                LEFT JOIN 
                    tari t ON o.tara_ID = t.tara_ID
                WHERE 
                    l.locatie_ID = ? LIMIT 1";

$stmt_locatie = $connect->prepare($sql_locatie);
if (!$stmt_locatie) {
    die("Eroare la pregătirea interogării locației: " . $connect->error);
}
$stmt_locatie->bind_param("i", $id); 
$stmt_locatie->execute();
$result_locatie = $stmt_locatie->get_result();

if (!$result_locatie || $result_locatie->num_rows === 0) {
    die("Oferta nu a fost găsită.");
}

$locatie = $result_locatie->fetch_assoc();
$stmt_locatie->close();

$sql_events = "SELECT 
                    e.nume_Event, 
                    e.dataIncepere, 
                    e.dataSfarsire, 
                    e.descriere 
                FROM 
                    evenimente e 
                INNER JOIN 
                    evenimente_locatii el ON e.event_ID = el.event_ID 
                WHERE 
                    el.locatie_ID = ?";

$stmt_events = $connect->prepare($sql_events);
if (!$stmt_events) {
    die("Eroare la pregătirea interogării evenimentelor: " . $connect->error);
}
$stmt_events->bind_param("i", $id);
$stmt_events->execute();
$events_result = $stmt_events->get_result();

$evenimente = [];
if ($events_result && $events_result->num_rows > 0) {
    while ($row_event = $events_result->fetch_assoc()) {
        $evenimente[] = $row_event;
    }
}
$stmt_events->close();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Detalii ofertă - <?= htmlspecialchars($locatie['numeLocatie']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .oferta-img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .sectiune {
            margin-top: 20px;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <h1 class="mb-4"><?= htmlspecialchars($locatie['numeLocatie']) ?></h1>
    <div class="row">
        <div class="col-md-6">
            <img src="poze/<?= htmlspecialchars($locatie['locatie_ID']) ?>.jpg" alt="Imagine ofertă" class="oferta-img" onerror="this.src='poze/default.jpg'">
        </div>
        <div class="col-md-6">
            <h4>Detalii locație</h4>
            <ul class="list-group">
                <li class="list-group-item"><strong>Țară:</strong> <?= htmlspecialchars($locatie['tara_nume'] ?? 'N/A') ?></li>
                <li class="list-group-item"><strong>Oraș:</strong> <?= htmlspecialchars($locatie['oras_nume'] ?? 'N/A') ?></li>
                <li class="list-group-item"><strong>Tip locație:</strong> <?= htmlspecialchars($locatie['tipLocatie']) ?></li>
                <li class="list-group-item"><strong>Valabilitate ofertă:</strong> <?= date("d-m-Y", strtotime($locatie['valabilitate'])) ?></li>
                <li class="list-group-item"><strong>Preț per persoană:</strong> <?= number_format($locatie['pret_per_persoana'], 2) ?> RON</li>
            </ul>

            <div class="sectiune">
                <h5>Descriere detaliată</h5>
                <p><?= nl2br(htmlspecialchars($locatie['descriere_detaliata'])) ?></p>
            </div>

            <?php if (!empty($evenimente)): ?>
                <div class="sectiune">
                    <h5>Evenimente asociate</h5>
                    <?php foreach ($evenimente as $event): ?>
                        <div class="mb-3 p-3 bg-light border rounded">
                            <strong><?= htmlspecialchars($event['nume_Event']) ?></strong><br>
                            Perioadă: <?= date("d-m-Y", strtotime($event['dataIncepere'])) ?> - <?= date("d-m-Y", strtotime($event['dataSfarsire'])) ?><br>
                            <small><?= nl2br(htmlspecialchars($event['descriere'])) ?></small> 
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="sectiune">
                    <h5>Evenimente asociate</h5>
                    <p>Nu există evenimente pentru această locație.</p>
                </div>
            <?php endif; ?>

            <div class="mt-4">
                <a href="locatii.php" class="btn btn-primary">Întoarce-mă la locații</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>