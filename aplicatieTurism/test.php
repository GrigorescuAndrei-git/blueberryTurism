<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'database.php'; // Make sure this file correctly establishes $connect

if (!$connect) {
    die("Conexiunea la baza de date a eșuat: " . mysqli_connect_error());
}

$sql_jnspeciala = <<<SQL
SELECT
    u.username,
    u.email,
    u.nume,
    u.prenume,
    rr.comentariu,
    rr.rating,
    rr.dataPostare,
    rr.tip_context,
    rr.nume_context
FROM
    utilizatori u
INNER JOIN (
    SELECT
        r.user_ID,
        r.comentariu,
        r.rating,
        r.dataPostare,
        'Locatie Capitala' AS tip_context,
        l.numeLocatie AS nume_context
    FROM
        recenzii r
    INNER JOIN
        locatii l ON r.locatie_ID = l.locatie_ID
    INNER JOIN
        orase o ON l.oras_ID = o.oras_ID
    WHERE
        o.nume_oras IN ('Bucuresti', 'Paris', 'Berlin', 'Roma', 'Londra')

    UNION ALL

    SELECT
        r.user_ID,
        r.comentariu,
        r.rating,
        r.dataPostare,
        'Event de muzica' AS tip_context,
        e.nume_Event AS nume_context
    FROM
        recenzii r
    INNER JOIN
        locatii l ON r.locatie_ID = l.locatie_ID
    INNER JOIN
        evenimente_locatii el ON l.locatie_ID = el.locatie_ID
    INNER JOIN
        evenimente e ON el.event_ID = e.event_ID
    WHERE
        e.nume_Event LIKE '%Concert%' OR e.nume_Event LIKE '%Festival%'
) AS rr ON u.user_ID = rr.user_ID
ORDER BY
    u.username, rr.dataPostare DESC;
SQL;

$res_jnspeciala1 = mysqli_query($connect, $sql_jnspeciala);
$data_scenario1 = [];
if ($res_jnspeciala1) {
    while ($row = mysqli_fetch_assoc($res_jnspeciala1)) {
        $data_scenario1[] = $row;
    }
} else {
    echo "Eroare SQL: " . mysqli_error($connect);
}

$sql_jnspeciala2 = <<<SQL
WITH LocatiiTaraSelectate AS (
    SELECT
        l.locatie_ID,
        l.numeLocatie,
        l.oras_ID
    FROM
        locatii l
    INNER JOIN
        orase o ON l.oras_ID = o.oras_ID
    INNER JOIN
        tari t ON o.tara_ID = t.tara_ID
    WHERE
        t.nume_tara IN ('Romania')
),
LocatiiRatingMediu AS (
    SELECT
        r.locatie_ID,
        AVG(r.rating) AS rating_mediu
    FROM
        recenzii r
    GROUP BY
        r.locatie_ID
    HAVING
        AVG(r.rating) >= 3.0
),
LocatiiCuEvenimente AS (
    SELECT DISTINCT
        el.locatie_ID,
        e.nume_Event AS eveniment_asociat
    FROM
        evenimente_locatii el
    INNER JOIN
        evenimente e ON el.event_ID = e.event_ID
)
SELECT
    lts.numeLocatie,
    lrm.rating_mediu,
    o.nume_oras,
    t.nume_tara,
    LCE.eveniment_asociat
FROM
    LocatiiTaraSelectate lts
INNER JOIN
    LocatiiRatingMediu lrm ON lts.locatie_ID = lrm.locatie_ID
INNER JOIN
    orase o ON lts.oras_ID = o.oras_ID
INNER JOIN
    tari t ON o.tara_ID = t.tara_ID
LEFT JOIN
    LocatiiCuEvenimente LCE ON lts.locatie_ID = LCE.locatie_ID
ORDER BY
    lrm.rating_mediu DESC, lts.numeLocatie, LCE.eveniment_asociat;
SQL;


$res_jnspeciala2 = mysqli_query($connect, $sql_jnspeciala2);
$data_scenario2 = [];
if ($res_jnspeciala2) {
    while ($row = mysqli_fetch_assoc($res_jnspeciala2)) {
        $data_scenario2[] = $row;
    }
} else {
    echo "Eroare SQL: " . mysqli_error($connect);
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB T & E</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container my-5">
    <h2></h2>
    <?php if (!empty($data_scenario1)): ?>
        <table class="table table-striped table-bordered mb-5">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Nume Complet</th>
                    <th>Email</th>
                    <th>Comentariu</th>
                    <th>Rating</th>
                    <th>Data Postării</th>
                    <th>Tip Context</th>
                    <th>Nume Context</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data_scenario1 as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['nume'] . ' ' . $row['prenume']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['comentariu']) ?></td>
                        <td><?= htmlspecialchars($row['rating']) ?></td>
                        <td><?= htmlspecialchars($row['dataPostare']) ?></td>
                        <td><?= htmlspecialchars($row['tip_context']) ?></td>
                        <td><?= htmlspecialchars($row['nume_context']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nu s-au găsit date pentru Scenariul 1.</p>
    <?php endif; ?>

    <hr>

    <h2></h2>
    <?php if (!empty($data_scenario2)): ?>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Nume locație</th>
                    <th>Rating mediu</th>
                    <th>Oraș</th>
                    <th>Țară</th>
                    <th>Eveniment asociat</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data_scenario2 as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['numeLocatie']) ?></td>
                        <td><?= number_format($row['rating_mediu'], 2) ?></td>
                        <td><?= htmlspecialchars($row['nume_oras']) ?></td>
                        <td><?= htmlspecialchars($row['nume_tara']) ?></td>
                        <td><?= htmlspecialchars($row['eveniment_asociat'] ?? 'N/A') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nu s-au găsit date pentru Scenariul 2.</p>
    <?php endif; ?>

</div>

</body>
</html>