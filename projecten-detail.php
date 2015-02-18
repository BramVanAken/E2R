<?php include("db.inc.php");
$id = $_GET['id'];
if ($id != null) {
	$qry = $db->prepare("SELECT projectnaam, projecttype, beschrijving FROM projecten WHERE projectID = ?");
	$qry->execute(array($id));
	$row = $qry->fetch(PDO::FETCH_ASSOC);
	$projectnaam = $row['projectnaam'];
	$projecttype = $row['projecttype'];
	$beschrijving = $row['beschrijving'];

	$breadcrumb = "";
	if ($projecttype == "eengezinswoningen") {
		$breadcrumb .= "<a href=\"projecten.php#een\">Eengezinswoningen</a>";
	} elseif ($projecttype == "meergezinswoningen") {
		$breadcrumb .= "<a href=\"projecten.php#meer\">Meergezinswoningen</a>";
	} elseif ($projecttype == "niet-residentielegebouwen") {
		$breadcrumb .= "<a href=\"projecten.php#nietres\">Niet-residentiële gebouwen</a>";
	} else {
		$breadcrumb .= "Dit project bestaat niet";
	}
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<title>Project <?php echo $projectnaam;?> | E2R-architecten</title>
	<link rel="stylesheet" type="text/css" href="data/style-main.css" />
	<link rel="shortcut icon" href="data/img-icon.ico" />
	<!--[if lt IE 9]><script src="data/js-html5shiv.js"></script><![endif]-->
</head>
<body>
	<header class="cf">
		<h1><a href="index.html">E2R-architecten</a></h1>
		<nav>
		<ul>
			<li class="ho"><a href="index.html">Home</a></li>
			<li class="pr pra">
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

	<section class="projdetail" id="foto">
		<h2><a href="projecten.php">Projecten</a> > <?php echo $breadcrumb;?> > <?php echo $projectnaam;?></h2>
	</section>
	
	<?php
	$qry = $db->prepare("SELECT COUNT(*) AS count FROM projecten_images WHERE projectID = ?");
	$qry->execute(array($id));
	$row = $qry->fetch(PDO::FETCH_ASSOC);

	if ($row['count'] == 1) {
		$qry = $db->prepare("SELECT large FROM projecten_images WHERE projectID = ?");
		$qry->execute(array($id));
		$row = $qry->fetch(PDO::FETCH_ASSOC)
		?>
		<div class="oneimage">
			<img src="images/<?php echo $row['large'];?>" alt="Afbeelding" />
		</div>
		<?php
	} else {
		$qry = $db->prepare("SELECT large FROM projecten_images WHERE projectID = ?");
		$qry->execute(array($id));

		while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
			$projectafb[] = "<img src=\"images/{$row['large']}\" alt=\"Afbeelding\" />";
		}?>

		<div class="container">
			<div id="slides">
				<?php echo (count($projectafb) > 0)?"" . implode("", $projectafb):"";?>
			</div>
		</div>
	<?php
	} ?>

	<div class="proj-tekst">
		<h2><?php echo $projectnaam;?></h2>
		<p>&nbsp;</p>
		<?php echo $beschrijving;?>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p><small>Foto's door <a href="mailto:marjan.k.photography@gmail.com">Marjan Kegels</a></small></p>
	</div>

	<footer class="cf">
		<p class="al">E2R architecten - Hoogstraat 18a - 1861 Wolvertem/Meise - tel. 02/269.25.48</p>
		<p class="ar">Design <a href="mailto:buggenhout.wouter@gmail.com">Wouter B</a> | Code <a href="https://bramvanaken.be">Bram VA</a> | Foto's <a href="mailto:marjan.k.photography@gmail.com">Marjan K</a></p>
	</footer>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script>$(document).ready(function(){var a=$("html, body");$("a").click(function(){var b=$.attr(this,"href");a.animate({scrollTop:$(b).offset().top},500,function(){window.location.hash=b});return false})});</script>
	<script src="data/js-slides.js"></script><script>$(function(){$('#slides').slidesjs();});</script>
</body>
</html>