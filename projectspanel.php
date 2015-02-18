<?php session_start(); if((isset($_SESSION['SESS_USERNAME'])) && ($_SESSION['SESS_TYPE'] == "admin")) { include("db.inc.php");
$id = $_GET['id'];
if ($id != null) {
	$qry = $db->prepare("DELETE FROM projecten WHERE projectID = ?");
	$qry->execute(array($id));
	$qry = $db->prepare("DELETE FROM projecten_images WHERE projectID = ?");
	$qry->execute(array($id));
	header("location: projectspanel.php");
	exit();
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Projecten | E2R-architecten</title>
	<link rel="stylesheet" type="text/css" href="data/style-main.css" />
	<!--[if lt IE 9]><script src="data/js-html5shiv.js"></script><![endif]-->
</head>
<body>
	<header class="cf">
		<h1><a href="control.php">E2R-architecten</a></h1>
		<nav>
		<ul>
			<li class="ho"><a href="control.php">Control Panel</a></li>
			<li class="pr pra">
				<a href="projectspanel.php">Projecten</a>
		        <ul>
		            <li><a href="#een">Eengezinswoningen</a></li>
		            <li><a href="#meer">Meergezinswoningen</a></li>
		            <li><a href="#nietres">Niet-residentiële gebouwen</a></li>
		            <li><a href="editproject.php">Project toevoegen</a></li>
		        </ul>
			</li>
			<li class="ov"><a href="account.php">Account</a></li>
			<li class="co"><a href="logout.php">Log Out</a></li>
		</ul>
		</nav>
	</header>

	<div class="top"><a href="#top"><img src="data/img-top.png" /></a></div>

	<section id="een">
		<?php
		$qry = $db->prepare("SELECT COUNT(*) AS count FROM projecten WHERE projecttype = ? AND projectthumb is null");
		$qry->execute(array("eengezinswoningen"));
		$row = $qry->fetch(PDO::FETCH_ASSOC);

		if ($row['count'] == 0) {
			?><h2>Eengezinswoningen</h2><?php
		} else {
			?><h2 class="textmargin">Eengezinswoningen</h2>

			<?php
			$qry = $db->prepare("SELECT * FROM view_nothumb WHERE projecttype = ?");
			$qry->execute(array("eengezinswoningen"));

			while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
				$projecteneennothumb[] = "<tr><td>{$row['projectnaam']}</td>";
				$time = strtotime($row['toevoegdatum']);
				$time = date("D, d M Y H:i:s",$time);

				$projecteneennothumb[] .= "<td>{$time}</td><td><a href=\"editproject.php?id={$row['projectID']}\"><div class=\"butedit\"></div></a></td><td><a href=\"projectspanel.php?id={$row['projectID']}\"><div class=\"butremove\"></div></a></td></tr>";
			}
			?>
			
			<p>Projecten zonder foto's:</p><p>&nbsp;</p>
			<table>
				<tr>
					<th>Naam</th>
					<th>Datum toegevoegd</th>
					<th></th>
					<th></th>
				</tr>
				<?php echo (count($projecteneennothumb) > 0)?"" . implode("", $projecteneennothumb):"";?>
			</table>
		<?php
		}?>
	</section>
	
	<div class="proj cf">
		<?php
		$qry = $db->prepare("SELECT projecten.projectID, projecten.projectnaam, projecten_images.thumbnail FROM projecten, projecten_images WHERE projecten.projectthumb = projecten_images.imageID AND projecttype = ?");
		$qry->execute(array("eengezinswoningen"));

		while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
			$projecteneen[] = "<div class=\"image imagebuttons\"><img src=\"images/{$row['thumbnail']}\" width=\"465\" alt=\"\" /><h4><span>{$row['projectnaam']}</span></h4>";
			$projecteneen[] .= "<a href=\"editproject.php?id={$row['projectID']}\"><div class=\"butedit buttons\"></div></a><a href=\"projectspanel.php?id={$row['projectID']}\"><div class=\"butremove buttons\"></div></a></div>";
 		}
		echo (count($projecteneen) > 0)?"" . implode("", $projecteneen):"";
		?>
	</div>

	<section id="meer">
		<?php
		$qry = $db->prepare("SELECT COUNT(*) AS count FROM projecten WHERE projecttype = ? AND projectthumb is null");
		$qry->execute(array("meergezinswoningen"));
		$row = $qry->fetch(PDO::FETCH_ASSOC);

		if ($row['count'] == 0) {
			?><h2>Meergezinswoningen</h2><?php
		} else {
			?><h2 class="textmargin">Meergezinswoningen</h2>

			<?php
			$qry = $db->prepare("SELECT * FROM view_nothumb WHERE projecttype = ?");
			$qry->execute(array("meergezinswoningen"));

			while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
				$projectenmeernothumb[] = "<tr><td>{$row['projectnaam']}</td>";
				$time = strtotime($row['toevoegdatum']);
				$time = date("D, d M Y H:i:s",$time);

				$projectenmeernothumb[] .= "<td>{$time}</td><td><a href=\"editproject.php?id={$row['projectID']}\"><div class=\"butedit\"></div></a></td><td><a href=\"projectspanel.php?id={$row['projectID']}\"><div class=\"butremove\"></div></a></td></tr>";
			}
			?>
			
			<p>Projecten zonder foto's:</p><p>&nbsp;</p>
			<table>
				<tr>
					<th>Naam</th>
					<th>Datum toegevoegd</th>
					<th></th>
					<th></th>
				</tr>
				<?php echo (count($projectenmeernothumb) > 0)?"" . implode("", $projectenmeernothumb):"";?>
			</table>
		<?php
		}?>
	</section>

	<div class="proj cf">
		<?php
		$qry = $db->prepare("SELECT projecten.projectID, projecten.projectnaam, projecten_images.thumbnail FROM projecten, projecten_images WHERE projecten.projectthumb = projecten_images.imageID AND projecttype = ?");
		$qry->execute(array("meergezinswoningen"));

		while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
			$projectenmeer[] = "<div class=\"image imagebuttons\"><img src=\"images/{$row['thumbnail']}\" width=\"465\" alt=\"\" /><h4><span>{$row['projectnaam']}</span></h4>";
			$projectenmeer[] .= "<a href=\"editproject.php?id={$row['projectID']}\"><div class=\"butedit buttons\"></div></a><a href=\"projectspanel.php?id={$row['projectID']}\"><div class=\"butremove buttons\"></div></a></div>";
 		}
		echo (count($projectenmeer) > 0)?"" . implode("", $projectenmeer):"";
		?>
	</div>

	<section id="nietres">
		<?php
		$qry = $db->prepare("SELECT COUNT(*) AS count FROM projecten WHERE projecttype = ? AND projectthumb is null");
		$qry->execute(array("niet-residentielegebouwen"));
		$row = $qry->fetch(PDO::FETCH_ASSOC);

		if ($row['count'] == 0) {
			?><h2>Niet-residentiële gebouwen</h2><?php
		} else {
			?><h2 class="textmargin">Niet-residentiële gebouwen</h2>

			<?php
			$qry = $db->prepare("SELECT * FROM view_nothumb WHERE projecttype = ?");
			$qry->execute(array("niet-residentielegebouwen"));

			while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
				$projectennietresnothumb[] = "<tr><td>{$row['projectnaam']}</td>";
				$time = strtotime($row['toevoegdatum']);
				$time = date("D, d M Y H:i:s",$time);

				$projectennietresnothumb[] .= "<td>{$time}</td><td><a href=\"editproject.php?id={$row['projectID']}\"><div class=\"butedit\"></div></a></td><td><a href=\"projectspanel.php?id={$row['projectID']}\"><div class=\"butremove\"></div></a></td></tr>";
			}
			?>
			
			<p>Projecten zonder foto's:</p><p>&nbsp;</p>
			<table>
				<tr>
					<th>Naam</th>
					<th>Datum toegevoegd</th>
					<th></th>
					<th></th>
				</tr>
				<?php echo (count($projectennietresnothumb) > 0)?"" . implode("", $projectennietresnothumb):"";?>
			</table>
		<?php
		}?>
	</section>

	<div class="proj cf">
		<?php
		$qry = $db->prepare("SELECT projecten.projectID, projecten.projectnaam, projecten_images.thumbnail FROM projecten, projecten_images WHERE projecten.projectthumb = projecten_images.imageID AND projecttype = ?");
		$qry->execute(array("niet-residentielegebouwen"));

		while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
			$projectennietres[] = "<div class=\"image imagebuttons\"><img src=\"images/{$row['thumbnail']}\" width=\"465\" alt=\"\" /><h4><span>{$row['projectnaam']}</span></h4>";
			$projectennietres[] .= "<a href=\"editproject.php?id={$row['projectID']}\"><div class=\"butedit buttons\"></div></a><a href=\"projectspanel.php?id={$row['projectID']}\"><div class=\"butremove buttons\"></div></a></div>";
 		}
		echo (count($projectennietres) > 0)?"" . implode("", $projectennietres):"";
		?>
	</div>

	<section id="addproject">
		<h2><a href="editproject.php">Project toevoegen</a></h2>
	</section>

	<footer class="cf">
		<p class="al">E2R architecten - Hoogstraat 18a - 1861 Wolvertem/Meise - tel. 02/269.25.48</p>
		<p class="ar">Design <a href="mailto:buggenhout.wouter@gmail.com">Wouter B</a> | Code <a href="https://bramvanaken.be">Bram VA</a> | Foto's <a href="mailto:marjan.k.photography@gmail.com">Marjan K</a></p>
	</footer>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script>$(document).ready(function(){var a=$("html, body");$("a").click(function(){var b=$.attr(this,"href");a.animate({scrollTop:$(b).offset().top},500,function(){window.location.hash=b});return false})});</script>
</body>
</html>
<?php } else { header("location: denied.html"); exit(); }?>