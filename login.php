<?php
if ($_POST) {
	include("db.inc.php");
	$un = $_POST['username'];
	$pw = $_POST['password'];
	$qry = $db->prepare("SELECT verificationkey, password, username FROM users WHERE username = ?");
	$qry->execute(array($un));
	$row = $qry->fetch(PDO::FETCH_ASSOC);
	$un_err = "";

	if (empty($row)) {
		$un_err .= "Gebruikersnaam bestaat niet";
	} elseif ($row["verificationkey"] == "true") {
		if (Bcrypt::check($pw, $row["password"])) {
			session_start();
			session_regenerate_id();
			$_SESSION['SESS_USERNAME'] = $row["username"];
			$_SESSION['SESS_TYPE'] = "admin";
			session_write_close();
			header("location: control.php");
			exit();
		} else {
			$pw_err = "Verkeerd wachtwoord";
		}
	} else {
		$un_err .= "Account niet geverifierd";
	}
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Log in | E2R-architecten</title>
	<link rel="stylesheet" type="text/css" href="data/style-main.css" />
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto" />
	<!--[if lt IE 9]><script src="data/js-html5shiv.js"></script><![endif]-->
</head>
<body>
	<header class="cf">
		<h1><a href="index.html">E2R-architecten</a></h1>
		<nav>
		<ul>
			<li class="ho"><a href="index.html">Home</a></li>
			<li class="pr">
				<a href="projecten.php">Projecten</a>
		        <ul>
		            <li><a href="projecten.php#een">Eengezinswoningen</a></li>
		            <li><a href="projecten.php#meer">Meergezinswoningen</a></li>
		            <li><a href="projecten.php#nietres">Niet-residentiële woningen</a></li>
		        </ul>
			</li>
			<li class="ov"><a href="overons.html">Over Ons</a></li>
			<li class="co"><a href="contact.php">Contact</a></li>
		</ul>
		</nav>
	</header>
	
	<section>
		<h2 class="textmargin">Log in</h2>

		<div class="column cf">
			<form method="post">
			  <dl>
			    <dt><label for="username">Gebruikersnaam:</label></dt>
			    <dd>
			      <input type="text" name="username" id="username" value="<?php echo $un;?>" />
			      <p><span class="error"><?php echo $un_err;?></span></p>
			    </dd>
			    <dt><label for="password">Wachtwoord:</label></dt>
			    <dd>
			      <input type="password" name="password" id="password" />
			      <p><span class="error"><?php echo $pw_err;?></span></p>
			    </dd>
			    <dt></dt>
			    <dd>
			      <input class="button" type="submit" id="submit" name="sumbit" value="Log in" />
			    </dd>
			  </dl>
			</form>
		</div>
	</section>

	<footer class="cf">
		<p class="al">E2R architecten - Hoogstraat 33/1 - 1861 Wolvertem - tel. 02/311.55.38</p>
		<p class="ar">Design <a href="mailto:buggenhout.wouter@gmail.com">Wouter B</a> | Code <a href="https://bramvanaken.be/#contact">Bram VA</a> | Foto's <a href="mailto:marjan.k.photography@gmail.com">Marjan K</a></p>
	</footer>
</body>
</html>