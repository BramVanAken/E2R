<?php include("db.inc.php");?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<title>Projecten | E2R-architecten</title>
	<link rel="stylesheet" type="text/css" href="data/style-main.css" />
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto" />
	<link rel="shortcut icon" href="data/img-icon.ico" />
	<!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="data/style-ie.css" /><script src="data/js-html5shiv.js"></script><![endif]-->
</head>
<body>
	<header class="cf">
		<h1 id="top"><a href="index.html">E2R-architecten</a></h1>
		<nav>
		<ul>
			<li class="ho"><a href="index.html">Home</a></li>
			<li class="pr pra">
				<a href="#">Projecten</a>
		        <ul>
		            <li><a href="#een">Eengezinswoningen</a></li>
		            <li><a href="#meer">Meergezinswoningen</a></li>
		            <li><a href="#nietres">Niet-residentiële gebouwen</a></li>
		        </ul>
			</li>
			<li class="ov"><a href="overons.html">Over Ons</a></li>
			<li class="co"><a href="contact.php">Contact</a></li>
		</ul>
		</nav>
	</header>

	<div class="top"><a href="#top"><img src="data/img-top.png" alt="Top" /></a></div>

	<section id="een">
		<h2>Eengezinswoningen</h2>
	</section>
	
	<div class="proj cf">
		<?php
		$qry = $db->prepare("SELECT projecten.projectID, projecten.projectnaam, projecten_images.thumbnail FROM projecten, projecten_images WHERE projecten.projectthumb = projecten_images.imageID AND projecttype = ?");
		$qry->execute(array("eengezinswoningen"));

		while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
			$projecteneen[] = "<div class=\"image\"><a href=\"projecten-detail.php?id={$row['projectID']}#foto\"><img src=\"images/{$row['thumbnail']}\" height=\"308\" alt=\"\" /><h4><span>{$row['projectnaam']}</span></h4></a></div>";
		}
		echo (count($projecteneen) > 0)?"" . implode("", $projecteneen):"";
		?>
	</div>

	<section id="meer">
		<h2>Meergezinswoningen</h2>
	</section>

	<div class="proj cf">
		<?php
		$qry = $db->prepare("SELECT projecten.projectID, projecten.projectnaam, projecten_images.thumbnail FROM projecten, projecten_images WHERE projecten.projectthumb = projecten_images.imageID AND projecttype = ?");
		$qry->execute(array("meergezinswoningen"));

		while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
			$projectenmeer[] = "<div class=\"image\"><a href=\"projecten-detail.php?id={$row['projectID']}#foto\"><img src=\"images/{$row['thumbnail']}\" width=\"465\" height=\"308\" alt=\"\" /><h4><span>{$row['projectnaam']}</span></h4></a></div>";
		}
		echo (count($projectenmeer) > 0)?"" . implode("", $projectenmeer):"";
		?>
	</div>

	<section id="nietres">
		<h2>Niet-residentiële gebouwen</h2>
	</section>

	<div class="proj cf">
		<?php
		$qry = $db->prepare("SELECT projecten.projectID, projecten.projectnaam, projecten_images.thumbnail FROM projecten, projecten_images WHERE projecten.projectthumb = projecten_images.imageID AND projecttype = ?");
		$qry->execute(array("niet-residentielegebouwen"));

		while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
			$projectennietres[] = "<div class=\"image\"><a href=\"projecten-detail.php?id={$row['projectID']}#foto\"><img src=\"images/{$row['thumbnail']}\" width=\"465\" height=\"308\" alt=\"\" /><h4><span>{$row['projectnaam']}</span></h4></a></div>";
		}
		echo (count($projectennietres) > 0)?"" . implode("", $projectennietres):"";
		?>
	</div>

	<footer class="cf">
		<p class="al">E2R architecten - Hoogstraat 33/1 - 1861 Wolvertem - tel. 02/311.55.38</p>
		<p class="ar">Design <a href="mailto:buggenhout.wouter@gmail.com">Wouter B</a> | Code <a href="https://bramvanaken.be/#contact">Bram VA</a> | Foto's <a href="mailto:marjan.k.photography@gmail.com">Marjan K</a></p>
	</footer>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script>$(document).ready(function(){var a=$("html, body");$("a").click(function(){var b=$.attr(this,"href");a.animate({scrollTop:$(b).offset().top},500,function(){window.location.hash=b});return false})});</script>
	<!--[if lt IE 9]><script>$(document).ready(function() {$(".image:nth-child(even)").addClass("image-even");$(".image:nth-child(odd)").addClass("image-odd");});</script><![endif]-->
</body>
</html>