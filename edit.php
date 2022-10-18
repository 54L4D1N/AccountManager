<?php
session_start();

// variablen initialisieren
$error = $message = '';

if (!isset($_SESSION['loggedin']) or !$_SESSION['loggedin']) {
    // Session nicht OK,  Weiterleitung auf Anmeldung
    $error .= "Sie sind nicht angemeldet, melden Sie sich bitte auf der  <a href='login.php'>Login-Seite</a> an.";
    //  Script beenden
} else
    $message .= "Sie sind nun angemeldet: $_SESSION[username]";

if(!isset($_GET['id']) || !is_int($_GET['id']))
    $error .= "Es wurde kein Konto ausgewählt!";
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
        <a class="navbar-brand" href="index.php">Session Handling</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <?php
                // TODO - wenn Session personalisiert ist - Link zu Logout anzeigen
                echo '<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>';
                // TODO - wenn Session nicht personalisiert - Link zu Login / Register anzeigen
                echo '<li class="nav-item"><a class="nav-link" href="register.php">Registrierung</a></li>';
                echo '<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>';
                ?>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h1>Editierbereich</h1>
        <?php
        // Ausgabe der Fehlermeldungen
        if (!empty($error)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
        } else if (!empty($message)) {
            echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";

            $query = "SELECT * password FROM account WHERE id = ? and userid = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("ii", $_SESSION['id'], $_GET['id']);
            $stmt->execute();
            if ($res = $stmt->get_result()) {
                if ($res->num_rows && $row = $res->fetch_assoc()) {
                    echo "<div>$row</div>";
                }
            }
            $error .= "Es wurde kein Konto gefunden!";
            $stmt->close();
            $mysqli->close();
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>
</body>

</html>