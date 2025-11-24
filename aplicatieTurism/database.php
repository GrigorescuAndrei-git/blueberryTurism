<?php

    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = '';
    $db_name = 'exsgbd';

    $connect = mysqli_connect($db_host, $db_user, $db_password, $db_name);

    if(!$connect){
        echo 'Nu ai fost conectat la baza de date!';
    }

?>