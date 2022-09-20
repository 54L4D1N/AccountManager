<?php

//Datenbankverbindung
include('db_connector.inc.php');

$error = '';
$message = '';

// Formular wurde gesendet und Besucher ist noch nicht angemeldet.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";

	$username = "";
	// username
	if (isset($_POST['username'])) {
		//trim
		$username = trim($_POST['username']);

		// prüfung benutzername
		if (empty($username) || !preg_match("/(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,30}/", $username)) {
			$error .= "Der Benutzername entspricht nicht dem geforderten Format.<br />";
		}
	} else {
		$error .= "Geben Sie bitte den Benutzername an.<br />";
	}
	$password = "";
	// password
	if (isset($_POST['password'])) {
		//trim
		$password = trim($_POST['password']);
		// passwort gültig?
		if (empty($password) || !preg_match("/(?=^.{8,255}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $password)) {
			$error .= "Das Passwort entspricht nicht dem geforderten Format.<br />";
		}
	} else {
		$error .= "Geben Sie bitte das Passwort an.<br />";
	}

	// kein fehler
	if (empty($error)) {

		$query = "SELECT username, password FROM users WHERE username = ?";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("s", $username);
		$stmt->execute();
		if ($res = $stmt->get_result()) {
			if ($res->num_rows && ($row = $res->fetch_assoc())) {
				if (password_verify($password, $row['password'])) {
					$message .= "Sie sind nun eingeloggt";

					$stmt->close();
					$mysqli->close();

					session_start();
					$_SESSION['loggedin'] = true;
					$_SESSION['username'] = $username;
					session_regenerate_id(true);
					header("location: admin.php");
				}
			}
			$res->free_result();
		}
		$error .= "Benutzername oder Passwort sind falsch";
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
	<div class="container">
		<h1>Login</h1>
		<p>
			Bitte melden Sie sich mit Benutzernamen und Passwort an.
		</p>
		<?php
		// fehlermeldung oder nachricht ausgeben
		if (!empty($message)) {
			echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
		} else if (!empty($error)) {
			echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
		}
		?>
		<form action="" method="POST">
			<div class="form-group">
				<label for="username">Benutzername *</label>
				<input type="text" name="username" class="form-control" id="username" value="" placeholder="Gross- und Keinbuchstaben, min 6 Zeichen." pattern="(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}" title="Gross- und Keinbuchstaben, min 6 Zeichen." maxlength="30" required="true">
			</div>
			<!-- password -->
			<div class="form-group">
				<label for="password">Password *</label>
				<input type="password" name="password" class="form-control" id="password" placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen, keine Umlaute" pattern="(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" title="mindestens einen Gross-, einen Kleinbuchstaben, eine Zahl und ein Sonderzeichen, mindestens 8 Zeichen lang,keine Umlaute." maxlength="255" required="true">
			</div>
			<button type="submit" name="button" value="submit" class="btn btn-info">Senden</button>
			<button type="reset" name="button" value="reset" class="btn btn-warning">Löschen</button>
		</form>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
  </body>
</body>

</html>