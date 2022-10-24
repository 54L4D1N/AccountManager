<?php

include("./db_connector.inc.php");
session_start();

// Initialisierung
$error = $message = "";
$userId = $_SESSION['id'];

if (!isset($userId) || !isset($_SESSION['loggedin'])) {
    $error .= "Sie sind nicht angemeldet! <a href='login.php'>Admin</a>";
}

// Wurden Daten mit "POST" gesendet?
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Ausgabe des gesamten $_POST Arrays zum debuggen
    //echo "<pre>";
    //print_r($_POST);
    //echo "</pre>";

    if (isset($_POST['password'])) {
        if (empty($_POST['password']) || !preg_match('/(?=^.{8,255}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $_POST['password']) === 0)
            $error .= "Passwort ist nicht korrekt!\n";
    }

    // keine Fehler vorhanden
    if (empty($error)) {
        $query = "UPDATE user SET password = ? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt->bind_param("si", $passwordHash, $userId);
        try {
            $stmt->execute();
            $message = "Passwort wurde erfolgreich geändert! <a href='admin.php'>Admin</a>";

            $stmt->close();
            $mysqli->close();
        } catch (mysqli_sql_exception $exception) {
            $error .= "Ein Fehler ist aufgetreten! <a href='admin.php'>Admin</a>\n";

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
    <title>Passwort Ändern</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <h1>Passwort Ändern</h1>
        <?php
        // Ausgabe der Fehlermeldungen
        if (strlen($error)) {
            echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
        } elseif (strlen($message)) {
            echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
        } else {
            echo '<form action="" method="post">
                    <!-- TODO: Clientseitige Validierung: password -->
                    <div class="form-group">
                        <label for="password">Neues Passwort *</label>
                        <input type="password" name="password" class="form-control" id="password" required pattern="(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen, keine Umlaute">
                    </div>
                    <button type="submit" name="button" value="submit" class="btn btn-info">Senden</button>
                    <button type="reset" name="button" value="reset" class="btn btn-warning">Löschen</button>
                    </form>
                </div>';
        }
        ?>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>
</body>

</html>