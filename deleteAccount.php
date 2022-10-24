<?php

require("./db_connector.inc.php");
session_start();
// Initialisierung
$error = $message = $account = $userid = $id = '';

if (!isset($_SESSION['id']) || !isset($_SESSION['loggedin'])) {
    $error .= "Sie sind nicht angemeldet! <a href='login.php'>Admin</a>";
} else if (!isset($_GET['select'])) {
    $error .= "Es wurde kein Konto selektiert!  <a href='admin.php'>Admin</a>";
}

else {
    $account = array();
    $userid = $_SESSION['id'];
    $id = $_GET['select'];
    $query = "DELETE FROM account WHERE userid = ? AND id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $userid, $id);
    try {
        $stmt->execute();
        $message = "Der Account wurde erfolgreich entfehrnt! <a href='admin.php'>Admin</a>";

        $stmt->close();
        $mysqli->close();
    } catch (mysqli_sql_exception $exception) {
        $error .= "ERROR! Please Try again\n";

        $stmt->close();
        $mysqli->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrierung</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
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
                    echo '<li class="nav-item"><a class="nav-link" href="editPassword.php">Passwort ändern</a></li>';
                } else {
                    echo '<li class="nav-item"><a class="nav-link" href="register.php">Registrierung</a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>';
                }
                ?>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1>Entfernter Account</h1>
        <?php
        // Ausgabe der Fehlermeldungen
        if (strlen($error)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
        } elseif (strlen($message)) {
            echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
        }
        ?>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>

</html>