<?php
	try {
		$db_server = 'localhost';
		$db_andmebaas = 'sport';
		$db_kasutaja = 'kaspar';
		$db_salasona = 'Passw0rd';

		$yhendus = mysqli_connect($db_server, $db_kasutaja, $db_salasona, $db_andmebaas);
	} catch (mysqli_sql_exception $e) {
		die('Probleem andmebaasiga: ' . $e->getMessage());
	}
?>