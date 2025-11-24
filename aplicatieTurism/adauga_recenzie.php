<?php
session_start();
include "database.php";

$user_ID = 1;

$mesaj = "";

$locatii_query = "SELECT locatie_ID, numeLocatie FROM locatii ORDER BY numeLocatie ASC";
$locatii_result = $connect->query($locatii_query);
$locatii = [];
if ($locatii_result && $locatii_result->num_rows > 0) {
    while ($row = $locatii_result->fetch_assoc()) {
        $locatii[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $comentariu = isset($_POST['comentariu']) ? trim($_POST['comentariu']) : '';
    $locatie_ID = isset($_POST['locatie_id']) && is_numeric($_POST['locatie_id']) && $_POST['locatie_id'] > 0 ? intval($_POST['locatie_id']) : 'NULL';

    if ($rating >= 1 && $rating <= 5 && !empty($comentariu)) {
        $comentariu_escaped = $connect->real_escape_string($comentariu);

        $insert_sql = "INSERT INTO recenzii (user_ID, rating, dataPostare, vizibilitate, comentariu, locatie_ID)
                       VALUES ($user_ID, $rating, NOW(), 1, '$comentariu_escaped', $locatie_ID)";

        if ($connect->query($insert_sql)) {
            $mesaj = "Recenzia a fost adăugată cu succes!";
        } else {
            $mesaj = "Eroare la salvare: " . $connect->error;
        }
    } else {
        $mesaj = "Completează toate câmpurile corect (rating între 1-5 și comentariu).";
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Adaugă recenzie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4">Scrie o recenzie</h2>

    <?php if (!empty($mesaj)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mesaj) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="rating" class="form-label">Rating (1-5)</label>
            <select class="form-select" name="rating" id="rating" required>
                <option value="">Alege un rating</option>
                <option value="1">1 - Foarte slab</option>
                <option value="2">2 - Slab</option>
                <option value="3">3 - Acceptabil</option>
                <option value="4">4 - Bun</option>
                <option value="5">5 - Excelent</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="locatie_id" class="form-label">Alege o locație (opțional)</label>
            <select class="form-select" name="locatie_id" id="locatie_id">
                <option value="0">-- Recenzie generală / Nespecificată --</option>
                <?php foreach ($locatii as $locatie): ?>
                    <option value="<?= htmlspecialchars($locatie['locatie_ID']) ?>">
                        <?= htmlspecialchars($locatie['numeLocatie']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="comentariu" class="form-label">Comentariu</label>
            <textarea class="form-control" name="comentariu" id="comentariu" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Trimite recenzia</button>
        <a href="recenzie.php" class="btn btn-secondary">Vezi toate recenziile</a>
    </form>
</div>

</body>
</html>