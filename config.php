<?php
	try {
		// sinu andmed
		$db_server = 'localhost';
		$db_andmebaas = 'sport2025';
		$db_kasutaja = 'moell';
		$db_salasona = 'moell';

		// ühendus
		$yhendus = mysqli_connect($db_server, $db_kasutaja, $db_salasona, $db_andmebaas);
	} catch (mysqli_sql_exception $e) {
		die('Probleem andmebaasiga: ' . $e->getMessage());
	}
?>