<?php
session_start();
include "database.php";

//var_dump($_SESSION); test
if (!isset($_SESSION['email']) || $_SESSION['admin_level'] != '1') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];
    //var_dump($user_id); //test

    if ($user_id > 0) {
        $delete_query = "DELETE FROM users WHERE id = $user_id";
        if (mysqli_query($connect, $delete_query)) {
            header("Location: administrare.php?success=1");
            exit;
        } else {
            echo "Eroare la ștergerea utilizatorului!";
        }
    } else {
        echo "ID invalid!";
    }
} else {
    echo "ID-ul utilizatorului nu a fost găsit!";
}
?>
