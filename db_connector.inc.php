<?php
// Variabeln deklarieren
const host = 'localhost'; // host
const username = 'work'; // username
const password = 'work1234'; // password
const database = 'accountManager'; // database

// mit der Datenbank verbinden
$mysqli = new mysqli(host, username, password, database);

// Fehlermeldung, falls Verbindung fehl schlägt.
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
}
