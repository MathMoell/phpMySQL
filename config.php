<?php
$db_server = 'localhost';
$db_andmebaas = 'sport2025';
$db_kasutaja = 'root';
$db_salasona = '';
$yhendus = mysqli_connect($db_server, $db_kasutaja, $db_salasona, $db_andmebaas);
if (!$yhendus) {
    die("Probleem andmebaasiga: " . mysqli_connect_error());
}
?>
