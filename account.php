<?php session_start(); if((isset($_SESSION['SESS_USERNAME'])) && ($_SESSION['SESS_TYPE'] == "admin")) { include("db.inc.php");

$action = $_GET['action'];
$text = "";
$ediptext = "";

if (($_POST) && ($action == "addaccount")) {
	$email = $_POST['email'];
	$un = $_POST['username'];
	$pw = $_POST['password1'];
	$errflag = false;
	$un_err = "";
	$pw_err = "";

	if ($un == '') {
		$un_err .= "Gebruikersnaam kan niet leeg zijn";
		$errflag = true;
	} else {
		$qry = $db->prepare("SELECT COUNT(*) as count FROM users WHERE username = ?");
		$qry->execute(array($un));
		$row = $qry->fetch(PDO::FETCH_ASSOC);
		if ($row['count'] > 0) {
			$un_err .= "Gebruikersnaam bestaat al";
			$errflag = true;
		}
	}

	if ($un == $pw) { 
		$pw_err .= "Gebruikersnaam en wachtwoord moeten verschillend zijn";
		$errflag = true;
	} elseif (strlen($pw) < 8) { 
		$pw_err .= "Wachtwoord moet minstens 8 karakters lang zijn";
		$errflag = true;
	} elseif ($pw != $_POST['password2']) {
		$pw_err .= "Wachtwoorden komen niet overeen";
		$errflag = true;
	}

	if ((filter_var($email, FILTER_VALIDATE_EMAIL)) && ($errflag == false)) {
		$pw = Bcrypt::hash($pw);
		$qry = $db->prepare("INSERT INTO users (email, username, password, verificationkey) VALUES (?, ?, ?, ?)");
		$qry->execute(array($email, $un, $pw, "true"));
		$text .= "Nieuwe account is aangemaakt.";
	}
}

if (($_POST) && ($action == "editpassword")) {
	$opw = $_POST['opassword'];
	$npw1 = $_POST['npassword1'];
	$npw2 = $_POST['npassword2'];
	$nerrflag = false;
	$opw_err = "";
	$npw_err = "";

	$qry = $db->prepare("SELECT password FROM users WHERE username = ?");
	$qry->execute(array($_SESSION['SESS_USERNAME']));
	$row = $qry->fetch(PDO::FETCH_ASSOC);

	if (Bcrypt::check($opw, $row["password"])) {
		if ($_SESSION['SESS_USERNAME'] == $npw1) { 
			$npw_err .= "Gebruikersnaam en wachtwoord moeten verschillend zijn";
			$nerrflag = true;
		} elseif (strlen($npw1) < 8) { 
			$npw_err .= "Wachtwoord moet minstens 8 karakters lang zijn";
			$nerrflag = true;
		} elseif ($npw1 != $npw2) {
			$npw_err .= "Wachtwoorden komen niet overeen";
			$nerrflag = true;
		}

		if ($nerrflag == false) {
			$npw1 = Bcrypt::hash($npw1);
			$qry = $db->prepare("UPDATE users SET password = ? WHERE username = ?");
			$qry->execute(array($npw1, $_SESSION['SESS_USERNAME']));
			$ediptext .= "Wachtwoord is veranderd";
		}
	} else {
		$opw_err .= "Verkeerd wachtwoord";
		$nerrflag = true;
	}
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Account | E2R-architecten</title>
	<link rel="stylesheet" type="text/css" href="data/style-main.css" />
	<!--[if lt IE 9]><script src="data/js-html5shiv.js"></script><![endif]-->
</head>
<body>
	<header class="cf">
		<h1><a href="control.php">E2R-architecten</a></h1>
		<nav>
		<ul>
			<li class="ho"><a href="control.php">Control Panel</a></li>
			<li class="pr">
				<a href="projectspanel.php">Projecten</a>
		        <ul>
		            <li><a href="projectspanel.php#een">Eengezinswoningen</a></li>
		            <li><a href="projectspanel.php#meer">Meergezinswoningen</a></li>
		            <li><a href="projectspanel.php#nietres">Niet-residentiële gebouwen</a></li>
		            <li><a href="editproject.php">Project toevoegen</a></li>
		        </ul>
			</li>
			<li class="ov ova"><a href="account.php">Account</a></li>
			<li class="co"><a href="logout.php">Log Out</a></li>
		</ul>
		</nav>
	</header>

	<div class="cf">
		<section class="l">
			<h2 class="textmargin">Nieuwe admin-account toevoegen</h2>
			
			<?php if ($text == '') {?>
			<form action="account.php?action=addaccount" method="post">
			  <dl>
			    <dt><label for="username">Gebruikersnaam:</label></dt>
			    <dd>
			      <input type="text" name="username" id="username" value="<?php echo $un;?>" />
			      <p><span class="error"><?php echo $un_err;?></span></p>
			    </dd>
			    <dt><label for="email">Email adres:</label></dt>
				<dd>
				  <input type="email" name="email" id="email" value="<?php echo $email;?>" />
				  <p><span class="error"><?php if (($_POST) && ($action == "addaccount") && (!filter_var($email, FILTER_VALIDATE_EMAIL))) {echo "Ongeldig email";}?></span></p>
				</dd>
			    <dt><label for="password1">Wachtwoord:</label></dt>
			    <dd>
			      <input type="password" name="password1" id="password1" />
			      <p><span class="error"><?php echo $pw_err;?></span></p>
			    </dd>
			    <dt><label for="password2">Wachtwoord opnieuw:</label></dt>
			    <dd>
			      <input type="password" name="password2" id="password2" />
			    </dd>
			    <dt></dt>
			    <dd>
			      <input class="button" type="submit" id="submit" name="sumbit" value="Submit" />
			    </dd>
			  </dl>
			</form>
			<?php } else { echo $text; }?>

		</section>

		<section class="r">
			<h2 class="textmargin">Wachtwoord wijzigen</h2>
			
			<?php if ($ediptext == '') {?>
			<form action="account.php?action=editpassword" method="post">
			  <dl>
			  	<dt><label for="opassword">Oud wachtwoord:</label></dt>
			    <dd>
			      <input type="password" name="opassword" id="opassword" />
			      <p><span class="error"><?php echo $opw_err;?></span></p>
			    </dd>
			    <dt><label for="npassword1">Wachtwoord:</label></dt>
			    <dd>
			      <input type="password" name="npassword1" id="npassword1" />
			      <p><span class="error"><?php echo $npw_err;?></span></p>
			    </dd>
			    <dt><label for="npassword2">Wachtwoord opnieuw:</label></dt>
			    <dd>
			      <input type="password" name="npassword2" id="npassword2" />
			    </dd>
			    <dt></dt>
			    <dd>
			      <input class="button" type="submit" id="submit" name="sumbit" value="Submit" />
			    </dd>
			  </dl>
			</form>
			<?php } else { echo $ediptext; }?>
		</section>
	</div>

	<footer class="cf">
		<p class="al">E2R architecten - Hoogstraat 18a - 1861 Wolvertem/Meise - tel. 02/269.25.48</p>
		<p class="ar">Design <a href="mailto:buggenhout.wouter@gmail.com">Wouter B</a> | Code <a href="https://bramvanaken.be/contact.php">Bram VA</a> | Foto's <a href="mailto:marjan.k.photography@gmail.com">Marjan K</a></p>
	</footer>
</body>
</html>
<?php } else { header("location: denied.html"); exit(); }?>