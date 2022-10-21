<?php

require("./db_connector.inc.php");
require("./password.generator.php");
session_start();
// Initialisierung
$error = '';
$message = "";
$name = $firstname = $lastname = $username = $email = $link = $description = $comment = $password = '';
$account = array();
$userid = $_SESSION['id'];

// Wurden Daten mit "POST" gesendet?
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['generate'])) {
        $password = getRandomPassword();
    } else {
        if (isset($_POST['name'])) {
            $name = trim($_POST['name']);
            if (empty($name) || strlen($name) > 30)
                $error .= "Name ist inkorrekt!\n";
        }

        if (isset($_POST['firstname'])) {
            $firstname = trim($_POST['firstname']);
        }

        if (isset($_POST['lastname'])) {
            $lastname = trim($_POST['lastname']);
        }

        if (isset($_POST['username'])) {
            $username = trim($_POST['username']);
        }

        if (isset($_POST['password'])) {
            $password = trim($_POST['password']);
        }

        if (isset($_POST['email'])) {
            $email = trim($_POST['email']);
        }

        if (isset($_POST['link'])) {
            $link = trim($_POST['link']);
        }

        if (isset($_POST['description'])) {
            $description = trim($_POST['description']);
        }

        if (isset($_POST['comment'])) {
            $comment = trim($_POST['comment']);
        }

        if (empty($error)) {
            $query = "INSERT INTO account (name, firstname, lastname, username, password, email, link, description, comment, userid) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("sssssssssi", $name, $firstname, $lastname, $username, $password, $email, $link, $description, $comment, $userid);
            try {
                $stmt->execute();
                $message = "Keine Fehler vorhanden";

                $stmt->close();
                $mysqli->close();

                header("location: admin.php");
            } catch (mysqli_sql_exception $exception) {
                $error .= "ERROR! Please Try again\n";

                $stmt->close();
                $mysqli->close();
            }
        }
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
                } else {
                    echo '<li class="nav-item"><a class="nav-link" href="register.php">Registrierung</a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>';
                }
                ?>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1>Account hinzufuegen</h1>
        <?php
        // Ausgabe der Fehlermeldungen
        if (strlen($error)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
        } elseif (strlen($message)) {
            echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
        }
        ?>
        <form class="row g-2" action="" method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" id="name" maxlength="30" value="<?php echo htmlspecialchars($name) ?>" placeholder="Geben Sie den Applikationsnamen an.">
            </div>
            <div class="form-group">
                <label for="firstname">Vorname</label>
                <input type="text" name="firstname" class="form-control" id="firstname" maxlength="30" value="<?php echo htmlspecialchars($firstname) ?>" placeholder="Geben Sie Ihren Vornamen an.">
            </div>
            <div class="form-group">
                <label for="lastname">Nachname</label>
                <input type="text" name="lastname" class="form-control" id="lastname" maxlength="30" value="<?php echo htmlspecialchars($lastname) ?>" placeholder="Geben Sie Ihren Nachnamen an">
            </div>
            <div class="form-group">
                <label for="username">Benutzername</label>
                <input type="text" name="username" class="form-control" id="username" maxlength="30" value="<?php echo htmlspecialchars($username) ?>" placeholder="Gross- und Keinbuchstaben, min 6 Zeichen.">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" maxlength="255" value="<?php echo htmlspecialchars($password) ?>" placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen, keine Umlaute">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" id="email" maxlength="100" value="<?php echo htmlspecialchars($email) ?>" placeholder="Geben Sie Ihre Email-Adresse an.">
            </div>
            <div class="form-group">
                <label for="link">Link</label>
                <input type="text" name="link" class="form-control" id="link" maxlength="255" value="<?php echo htmlspecialchars($link) ?>" placeholder="Link für Webapplikation.">
            </div>
            <div class="form-group">
                <label for="description">Beschreibung</label>
                <textarea name="description" class="form-control" id="description" rows="4" value="<?php echo htmlspecialchars($description) ?>"></textarea>
            </div>
            <div class="form-group">
                <label for="comment">Kommentar</label>
                <textarea name="comment" class="form-control" id="comment" rows="2" value="<?php echo htmlspecialchars($description) ?>"></textarea>
            </div>
            <div class="form-group col-auto">
                <button type="submit" name="button" value="submit" class="btn btn-info">Senden</button>
            </div>
            <div class="form-group col-auto">
                <button type="reset" name="button" value="reset" class="btn btn-warning">Löschen</button>
            </div>
            <div class="form-group col-auto">
                <button type="submit" name="generate" value="generate" class="btn btn-success">Generiere Passwort</button>
            </div>
        </form>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>

</html>