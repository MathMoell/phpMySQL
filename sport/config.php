<?php
// Ühendus SQLite andmebaasiga
try {
    $yhendus = new SQLite3('sport.db');  // Veendu, et sport.db asub samas kaustas
} catch (Exception $e) {
    die("Andmebaasi viga: " . $e->getMessage());
}
?>
