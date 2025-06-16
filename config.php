<?php
try {
    // Andmebaasi ühenduse seaded
    $db_server = 'localhost';
    $db_andmebaas = 'mmoll';
    $db_kasutaja = 'mmoll';
    $db_salasona = 'iKahnunPWfJyjJGT';

    // Ühendus andmebaasiga
    $yhendus = mysqli_connect($db_server, $db_kasutaja, $db_salasona, $db_andmebaas);
    
    if (!$yhendus) {
        throw new Exception('Andmebaasi ühendus ebaõnnestus: ' . mysqli_connect_error());
    }
    
    mysqli_set_charset($yhendus, "utf8");
    
} catch (Exception $e) {
    die('Probleem andmebaasiga: ' . $e->getMessage());
}

// Funktsioon aegunud broneeringute puhastamiseks
function puhasta_aegunud_broneeringud($yhendus) {
    $paring = "UPDATE piletid SET staatus = 'aegunud' 
               WHERE staatus = 'broneeritud' 
               AND broneeritud_aeg < DATE_SUB(NOW(), INTERVAL 15 MINUTE)";
    mysqli_query($yhendus, $paring);
}

// Funktsioon e-maili valideerimiseks
function valideeri_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Funktsioon isikukoodi valideerimiseks (lihtne kontroll)
function valideeri_isikukood($isikukood) {
    return preg_match('/^[0-9]{11}$/', $isikukood);
}
?>