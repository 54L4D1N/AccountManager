<?php

include("./db_connector.inc.php");
session_start();

// variablen initialisieren
$error = $message = '';
$userid = $_SESSION['id'] ?? -1;
$disabled = True;
$selected = null;

if (!isset($_SESSION['loggedin']) or !$_SESSION['loggedin']) {
    // Session nicht OK,  Weiterleitung auf Anmeldung
    $error .= "Sie sind nicht angemeldet, melden Sie sich bitte auf der  <a href='login.php'>Login-Seite</a> an.";
    //  Script beenden
} else {
    $message .= "Sie sind nun angemeldet: $_SESSION[username]";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrationbereich</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/aa92474866.js" crossorigin="anonymous"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="admin.php">Admin</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <?php
                if (isset($_SESSION['loggedin']) and $_SESSION['loggedin']) {
                    echo '<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>';
                } else {
                    echo '<li class="nav-item"><a class="nav-link" href="register.php">Registrierung</a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>';
                }
                ?>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h1>Administrationbereich</h1>
        <?php
        // Ausgabe der Fehlermeldungen
        if (!empty($error)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
        } else if (!empty($message)) {
            echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";

            echo "<form>
            <h1>Accounts</h1>
            <a class=\"btn btn-secondary\" href=\"addAccount.php\" role=\"button\">add Account</a>
            <input type=\"submit\" class=\"btn btn-secondary\" value=\"edit Account\" formaction=\"edit.php\"/>
            <input type=\"submit\" class=\"btn btn-secondary\" value=\"delete Account\" formaction=\"deleteAccount.php\"/>";
            
            $query = "SELECT * FROM account WHERE userid = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("i", $userid);
            $stmt->execute();
            $result = $stmt->get_result();

            echo "<table class='table'>
        <thead>
            <tr>
                <th scope='col'>#</th>
                <th scope='col'>name</th>
                <th scope='col'>firstname</th>
                <th scope='col'>lastname</th>
                <th scope='col'>username</th>
                <th scope='col'>password</th>
                <th scope='col'>email</th>
                <th scope='col'>link</th>
                <th scope='col'>description</th>
                <th scope='col'>comment</th>
                <th scope='col'>select</th>
            </tr>
        </thead>";

            echo "<tbody>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                <th scope='row'>1</th>
                <td>" . $row['name'] . "</td>
                <td>" . $row['firstname'] . "</td>
                <td>" . $row['lastname'] . "</td>
                <td>" . $row['username'] . "</td>
                <td>" . $row['password'] . "</td>
                <td>" . $row['email'] . "</td>
                <td>" . $row['link'] . "</td>
                <td>" . $row['description'] . "</td>
                <td>" . $row['comment'] . "</td>
                <td align='center'><input type='radio' id='select' name='select' value=" . $row['id'] . "></td>
            </tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</form>";
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>

</html>