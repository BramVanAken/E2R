<?php session_start(); if((isset($_SESSION['SESS_USERNAME'])) && ($_SESSION['SESS_TYPE'] == "admin")) { include("db.inc.php");
$id = $_GET['id'];
if ($id != null) {
	$qry = $db->prepare("DELETE FROM projecten WHERE projectID = ?");
	$qry->execute(array($id));
	$qry = $db->prepare("DELETE FROM projecten_images WHERE projectID = ?");
	$qry->execute(array($id));
	header("location: control.php");
	exit();
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Control Panel | E2R-architecten</title>
	<link rel="stylesheet" type="text/css" href="data/style-main.css" />
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto" />
	<!--[if lt IE 9]><script src="data/js-html5shiv.js"></script><![endif]-->
</head>
<body>
	<header class="cf">
		<h1><a href="control.php">E2R-architecten</a></h1>
		<nav>
		<ul>
			<li class="ho hoa"><a href="control.php">Control Panel</a></li>
			<li class="pr">
				<a href="projectspanel.php">Projecten</a>
		        <ul>
		            <li><a href="projectspanel.php#een">Eengezinswoningen</a></li>
		            <li><a href="projectspanel.php#meer">Meergezinswoningen</a></li>
		            <li><a href="projectspanel.php#nietres">Niet-residentiële gebouwen</a></li>
		            <li><a href="editproject.php">Project toevoegen</a></li>
		        </ul>
			</li>
			<li class="ov"><a href="account.php">Account</a></li>
			<li class="co"><a href="logout.php">Log Out</a></li>
		</ul>
		</nav>
	</header>

	<section>
		<h2>Control Panel</h2>
	</section>

	<section>
		<h3 class="textmargin">Nieuwste projecten</h3>

		<?php
		$qry = $db->prepare("SELECT * FROM projecten ORDER BY datetime(toevoegdatum) DESC LIMIT 5");
		$qry->execute();

		while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
			$projecten[] = "<tr><td>{$row['projectnaam']}</td><td>{$row['projecttype']}</td>";

			$time = strtotime($row['toevoegdatum']);
			$time = date("D, d M Y H:i:s",$time);

			$projecten[] .= "<td>{$time}</td><td><a href=\"editproject.php?id={$row['projectID']}\"><div class=\"butedit\"></div></td><td><a href=\"control.php?id={$row['projectID']}\"><div class=\"butremove\"></div></a></td></tr>";	
		}
		?>

		<table>
			<tr>
				<th>Naam</th>
				<th>Type</th>
				<th>Datum toegevoegd</th>
				<th></th>
				<th></th>
			</tr>
			<?php echo (count($projecten) > 0)?"" . implode("", $projecten):"";?>
		</table>
	</section>

	<footer class="cf">
		<p class="al">E2R architecten - Hoogstraat 18a - 1861 Wolvertem/Meise - tel. 02/269.25.48</p>
		<p class="ar">Design <a href="mailto:buggenhout.wouter@gmail.com">Wouter B</a> | Code <a href="https://bramvanaken.be/#contact">Bram VA</a> | Foto's <a href="mailto:marjan.k.photography@gmail.com">Marjan K</a></p>
	</footer>
</body>
</html>
<?php } else { header("location: denied.html"); exit(); }?>