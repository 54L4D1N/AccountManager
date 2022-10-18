<?php

include("./db_connector.inc.php");
session_start();
// Initialisierung
$error = '';
$message = "";
$name = $firstname = $lastname = $username = $email = $link = $description = $comment = '';
$account = array();

// Wurden Daten mit "POST" gesendet?
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Ausgabe des gesamten $_POST Arrays zum debuggen
    //echo "<pre>";
    //print_r($_POST);
    //echo "</pre>";

    if (isset($_POST['name'])) {
        $name = trim($_POST['name']);
        if (empty($name) || strlen($name) > 30)
            $error .= "Name ist inkorrekt!\n";
    }

    if (isset($_POST['firstname'])) {
        $firstname = trim($_POST['firstname']);
        if (empty($firstname) || strlen($firstname) > 30)
            $error .= "Vorname ist inkorrekt!\n";
    }

    if (isset($_POST['lastname'])) {
        $lastname = trim($_POST['lastname']);
        if (strlen($lastname) > 30)
            $error .= "Nachname ist inkorrekt!\n";
    }

    if (isset($_POST['username'])) {
        $username = trim($_POST['username']);
        if (!preg_match("/(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,30}/", $username) || strlen($username) > 30)
            $error .= "Username ist inkorrekt!\n";
    }

    if (isset($_POST['password'])) {
        if (!preg_match('/(?=^.{8,255}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $_POST['password']) === 0 || strlen($_POST['password']) > 255)
            $error .= "Password ist inkorrekt!\n";
    }

    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $error .= "Email address '$email' is considered invalid.\n";
        if (strlen($email) > 100)
            $error .= "Email ist inkorrekt!\n";
    }

    if (isset($_POST['link'])) {
        $link = trim($_POST['link']);
        if (strlen($link) > 255)
            $error .= "Link ist inkorrekt!\n";
    }

    if (isset($_POST['description'])) {
        $description = trim($_POST['description']);
    }

    if (isset($_POST['comment'])) {
        $comment = trim($_POST['comment']);
    }

    // TODO
    // keine Fehler vorhanden
    if (empty($error)) {
        $query = "INSERT INTO account (firstname, lastname, email, username, password) VALUES(?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt->bind_param("sssss", $firstname, $lastname, $email, $username, $passwordHash);
        try {
            $stmt->execute();
            $message = "Keine Fehler vorhanden";

            $stmt->close();
            $mysqli->close();

            header("location: login.php");
        } catch (mysqli_sql_exception $exception) {
            $error .= "Benutzername existiert bereits!\n";

            $stmt->close();
            $mysqli->close();
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

<div class="container">
    <h1>Registrierung</h1>
    <p>
        Bitte registrieren Sie sich, damit Sie diesen Dienst benutzen können
        oder melden Sie sich direkt über den Link an <a href="login.php">Login</a>
    </p>
    <?php
    // Ausgabe der Fehlermeldungen
    if (strlen($error)) {
        echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
    } elseif (strlen($message)) {
        echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
    }
    ?>
    <form action="" method="post">
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
            <input type="text" name="username" class="form-control" id="username" maxlength="30" pattern="(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}" value="<?php echo htmlspecialchars($username) ?>" placeholder="Gross- und Keinbuchstaben, min 6 Zeichen.">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" id="password" maxlength="255" pattern="(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen, keine Umlaute">
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
        <button type="submit" name="button" value="submit" class="btn btn-info">Senden</button>
        <button type="reset" name="button" value="reset" class="btn btn-warning">Löschen</button>
    </form>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>
</body>

</html>