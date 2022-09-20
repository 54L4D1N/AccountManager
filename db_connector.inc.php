<?php
// Variabeln deklarieren
const host = 'localhost'; // host
const username = 'valentin'; // username
const password = 'asdf'; // password
const database = '151_users'; // database

// mit der Datenbank verbinden
$mysqli = new mysqli(host, username, password, database);

// Fehlermeldung, falls Verbindung fehl schlägt.
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
}
