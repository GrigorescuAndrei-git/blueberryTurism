<?php
session_start();
include "database.php";

$filtru_tara = $_GET['tara'] ?? '';
$filtru_oras = $_GET['oras'] ?? '';
$filtru_pret = $_GET['pret'] ?? '';

$where = "WHERE 1=1";
$join_tara_oras = "
    LEFT JOIN orase ON locatii.oras_ID = orase.oras_ID
    LEFT JOIN tari ON orase.tara_ID = tari.tara_ID
";

if ($filtru_tara) {
    $where .= " AND tari.nume_tara = '" . $connect->real_escape_string($filtru_tara) . "'";
}
if ($filtru_oras) {
    $where .= " AND orase.nume_oras = '" . $connect->real_escape_string($filtru_oras) . "'";
}
if ($filtru_pret) {
    $where .= " AND locatii.pret_Locatie = '" . $connect->real_escape_string($filtru_pret) . "'";
}

$sql = "SELECT 
            locatii.*, 
            orase.nume_oras AS oras_nume, 
            tari.nume_tara AS tara_nume, 
            evenimente.nume_Event 
        FROM 
            locatii 
        " . $join_tara_oras . " 
        LEFT JOIN 
            evenimente_locatii ON locatii.locatie_ID = evenimente_locatii.locatie_ID 
        LEFT JOIN 
            evenimente ON evenimente_locatii.event_ID = evenimente.event_ID 
        $where 
        ORDER BY 
            locatii.locatie_ID DESC";

$rezultat = $connect->query($sql);

$lista_tari_query = "SELECT DISTINCT nume_tara FROM tari ORDER BY nume_tara ASC";
$lista_tari = $connect->query($lista_tari_query);

$lista_orase_query = "SELECT DISTINCT nume_oras FROM orase ORDER BY nume_oras ASC";
$lista_orase = $connect->query($lista_orase_query);

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Oferte Vacanțe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-oferta {
            height: 100%;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.2s ease;
        }
        .card-oferta:hover {
            transform: scale(1.02);
        }
        .img-oferta {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        .descriere-scurta {
            max-height: 60px;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <h1 class="mb-4 text-center">Oferte de Vacanță - Blueberry T & E</h1>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <select name="tara" class="form-select">
                <option value="">-- Țară --</option>
                <?php 
                if ($lista_tari && $lista_tari->num_rows > 0) : 
                    while ($row_tara = $lista_tari->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row_tara['nume_tara']) ?>" <?= ($filtru_tara === $row_tara['nume_tara']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($row_tara['nume_tara']) ?>
                        </option>
                    <?php endwhile; 
                endif; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="oras" class="form-select">
                <option value="">-- Oraș --</option>
                <?php 
                if ($lista_orase && $lista_orase->num_rows > 0) :
                    while ($row_oras = $lista_orase->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row_oras['nume_oras']) ?>" <?= ($filtru_oras === $row_oras['nume_oras']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($row_oras['nume_oras']) ?>
                        </option>
                    <?php endwhile;
                endif; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="pret" class="form-select">
                <option value="">-- Preț --</option>
                <option value="$" <?= ($filtru_pret === '$') ? 'selected' : '' ?>>$</option>
                <option value="$$" <?= ($filtru_pret === '$$') ? 'selected' : '' ?>>$$</option>
                <option value="$$$" <?= ($filtru_pret === '$$$') ? 'selected' : '' ?>>$$$</option>
            </select>
        </div>
        <div class="col-md-3 d-grid">
            <button type="submit" class="btn btn-success">Aplică filtre</button>
        </div>
    </form>

    <div class="row g-4">
        <?php 
        if ($rezultat && $rezultat->num_rows > 0): ?>
            <?php while ($row = $rezultat->fetch_assoc()): ?>
                <?php
                $imgPath = "poze/" . $row['locatie_ID'] . ".jpg";
                $imgSrc = file_exists($imgPath) ? $imgPath : "poze/default.jpg";
                ?>
                <div class="col-md-4">
                    <div class="card shadow-sm card-oferta">
                        <img src="<?= htmlspecialchars($imgSrc) ?>" alt="Imagine ofertă" class="img-oferta">
                        <div class="p-3">
                            <h5 class="card-title mb-1"><?= htmlspecialchars($row['numeLocatie']) ?></h5>
                            <p class="text-muted mb-1"><strong>Tip:</strong> <?= htmlspecialchars($row['tipLocatie']) ?></p>
                            <p class="text-muted mb-1"><strong>Țară:</strong> <?= htmlspecialchars($row['tara_nume'] ?? 'N/A') ?></p>
                            <p class="text-muted mb-1"><strong>Oraș:</strong> <?= htmlspecialchars($row['oras_nume'] ?? 'N/A') ?></p>
                            <p class="text-muted mb-1"><strong>Preț:</strong> <?= htmlspecialchars($row['pret_Locatie']) ?></p>
                            <p class="descriere-scurta"><?= htmlspecialchars($row['descriere_detaliata'] ?? 'N/A') ?></p>
                            <div class="d-grid mt-3">
                                <a href="oferta_detaliata.php?id=<?= (int)$row['locatie_ID'] ?>" class="btn btn-success btn-sm">Obține oferta</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="lead">Nu s-au găsit oferte conform filtrelor selectate.</p>
            </div>
        <?php endif; ?>
    </div>

</div>

</body>
</html>