<?php
// config.php asemel otse ühendus (määra enda andmebaasi seaded)
$host = 'localhost';
$user = 'moell';
$pass = 'moell';
$db = 'kino2025';

// Ühenda andmebaasiga
$yhendus = mysqli_connect($host, $user, $pass, $db);

if (!$yhendus) {
    die("Andmebaasi ühendus ebaõnnestus: " . mysqli_connect_error());
}

echo "Andmebaasiga ühendus õnnestus.<br>";

// Kas film_id on GET parameetrina olemas?
if (!isset($_GET['film_id'])) {
    die("Film_id pole määratud. Kasuta URL: test.php?film_id=1 (või mõni muu id)");
}

$film_id = (int)$_GET['film_id'];

if ($film_id <= 0) {
    die("Film_id peab olema positiivne täisarv.");
}

echo "Otsitakse film_id: $film_id<br>";

// Koosta päring
$paring = "
    SELECT s.*, sa.nimi as saal_nimi 
    FROM seansid s
    JOIN saalid sa ON s.saal_id = sa.id
    WHERE s.film_id = $film_id
    AND s.aktiivne = 1
    AND CONCAT(s.kuupaev, ' ', s.kellaaeg) > NOW()
    ORDER BY s.kuupaev, s.kellaaeg
";

echo "Päring: <pre>$paring</pre>";

// Käivita päring
$vastus = mysqli_query($yhendus, $paring);

if (!$vastus) {
    die("Päring ebaõnnestus: " . mysqli_error($yhendus));
}

$arv = mysqli_num_rows($vastus);

echo "Leitud seansse: $arv<br>";

if ($arv === 0) {
    // Kontrollime ka serveri aega ja andmebaasi kuupäeva ja kellaaja näiteid

    echo "<b>Seansse pole saadaval, vaatame miks:</b><br>";

    // Kontrollime serveri praegust aega
    $server_aeg = date('Y-m-d H:i:s');
    echo "Serveri praegune aeg: $server_aeg<br>";

    // Näidiskontroll: leia vähemalt üks aktiivne seanss ilma kuupäeva ja kellaaja piiranguta
    $kontroll_paring = "
        SELECT s.kuupaev, s.kellaaeg, CONCAT(s.kuupaev, ' ', s.kellaaeg) as datetime_combined
        FROM seansid s
        WHERE s.film_id = $film_id
        AND s.aktiivne = 1
        ORDER BY s.kuupaev, s.kellaaeg
        LIMIT 5
    ";
    $kontroll_vastus = mysqli_query($yhendus, $kontroll_paring);
    if (!$kontroll_vastus) {
        die("Kontrollpäring ebaõnnestus: " . mysqli_error($yhendus));
    }
    if (mysqli_num_rows($kontroll_vastus) == 0) {
        echo "Seansse pole leitud ka ilma kuupäeva kontrollita.<br>";
        echo "Võimalik, et filmil pole aktiivseid seansse või film_id on vale.";
    } else {
        echo "Leitud aktiivsed seansid (kuupäev ja kellaaeg):<br><ul>";
        while ($rida = mysqli_fetch_assoc($kontroll_vastus)) {
            echo "<li>" . $rida['kuupaev'] . " " . $rida['kellaaeg'] . " (ühendatud: " . $rida['datetime_combined'] . ") </li>";
        }
        echo "</ul>";
        echo "Kontrolli, kas kuupäev ja kellaaeg on tõesti tulevikus ja serveri aeg on õige.";
    }
    exit;
}

// Kui jõudsime siia, on vähemalt üks seanss olemas:
echo "<b>Seansid:</b><br>";
while ($seanss = mysqli_fetch_assoc($vastus)) {
    echo htmlspecialchars($seanss['kuupaev']) . " " . htmlspecialchars($seanss['kellaaeg']) . " | Saal: " . htmlspecialchars($seanss['saal_nimi']) . "<br>";
}
?>
