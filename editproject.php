<?php session_start(); if((isset($_SESSION['SESS_USERNAME'])) && ($_SESSION['SESS_TYPE'] == "admin")) { include("db.inc.php");
$update = $_POST['update'];
$id = $_GET['id'];
$action = $_GET['action'];
$imageid = $_GET['imageid'];

if (($_POST) && ($update == 0)) {
	$time = date('Y-m-d H:i:s');
	$date = new DateTime($time);
	$date->modify('+1 hour');
	$date = $date->format('Y-m-d H:i:s');

	$qry = $db->prepare("INSERT INTO projecten (projectnaam, projecttype, beschrijving, toevoegdatum) VALUES (?, ?, ?, ?)");
	$qry->execute(array($_POST['naam'], $_POST['type'], $_POST['beschrijving'], $date));

	$qry = $db->prepare("SELECT projectID FROM projecten WHERE projectnaam = ? AND toevoegdatum = ?");
	$qry->execute(array($_POST['naam'], $date));
	$row = $qry->fetch(PDO::FETCH_ASSOC);

	header("location: editproject.php?id={$row['projectID']}");
	exit();
}

if (($_POST) && ($update == 1)) {
	$qry = $db->prepare("UPDATE projecten SET projectnaam = ?, projecttype = ?, beschrijving = ? WHERE projectID = ?");
	$qry->execute(array($_POST['naam'], $_POST['type'], $_POST['beschrijving'], $_POST['updateid']));
	header("location: editproject.php?id={$_POST['updateid']}");
}

if ($action == "remove") {
	$qry = $db->prepare("SELECT imageID FROM view_thumbnail WHERE projectID = ?");
	$qry->execute(array($id));
	$row = $qry->fetch(PDO::FETCH_ASSOC);

	if ($row['imageID'] == $imageid) {
		$qry = $db->prepare("UPDATE projecten SET projectthumb = ? WHERE projectID = ?");
		$qry->execute(array(NULL, $id));
	}

	$qry = $db->prepare("DELETE FROM projecten_images WHERE imageID = ?");
	$qry->execute(array($imageid));
}

if ($action == "thumbnail") {
	$qry = $db->prepare("UPDATE projecten SET projectthumb = ? WHERE projectID = ?");
	$qry->execute(array($imageid, $id));
}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Project toevoegen of wijzigen | E2R-architecten</title>
	<link rel="stylesheet" type="text/css" href="data/style-main.css" />
	<!--[if lt IE 9]><script src="data/js-html5shiv.js"></script><![endif]-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="//tinymce.cachefly.net/4.2/tinymce.min.js"></script><script>tinymce.init({selector:'textarea'});</script>
	<script src="data/js-upload.js"></script>
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
		            <li><a href="projectspanel.php#een">Eengezinswoningen</a></li>
		            <li><a href="projectspanel.php#meer">Meergezinswoningen</a></li>
		            <li><a href="projectspanel.php#nietres">Niet-residentiële woningen</a></li>
		            <li><a href="editproject.php">Project toevoegen</a></li>
		        </ul>
			</li>
			<li class="ov"><a href="account.php">Account</a></li>
			<li class="co"><a href="logout.php">Log Out</a></li>
		</ul>
		</nav>
	</header>
	
	<?php
	$qry = $db->prepare("SELECT * FROM projecten WHERE projectID = ?");
	$qry->execute(array($id));
	$row = $qry->fetch(PDO::FETCH_ASSOC);
	$projectnaam = $row['projectnaam'];
	$projecttype = $row['projecttype'];
	$beschrijving = $row['beschrijving'];
	if ($id != null) {
		$update = 1;
	} else {
		$update = 0;
	}
	?>
	<section>
		<h2 class="textmargin">Project toevoegen of wijzigen</h2>

		<div class="column cf">
			<form method="post">
			  <dl class="texteditor">
			  	<dt><label for="naam">Naam:</label></dt>
			    <dd>
			      <input type="text" name="naam" id="naam" size="32" value="<?php echo $projectnaam;?>" />
			    </dd>
			    <dt>Type:</dt>
			    <dd>
			      <select name="type">
					<option <?php if ($projecttype == "eengezinswoningen" ) echo 'selected'; ?> value="eengezinswoningen">Eengezinswoningen</option>
					<option <?php if ($projecttype == "meergezinswoningen" ) echo 'selected'; ?> value="meergezinswoningen">Meergezinswoningen</option>
					<option <?php if ($projecttype == "niet-residentielegebouwen" ) echo 'selected'; ?> value="niet-residentielegebouwen">Niet-residentiële gebouwen</option>
				  </select>
				</dd>
			    <dt><label for="beschrijving">Beschrijving:</label></dt>
			    <dd>
			      <textarea name="beschrijving" id="beschrijving" rows="15" cols="80"><?php echo $beschrijving;?></textarea>
			    </dd>
			    <dt> </dt>
			    <dd>
			      <input type="hidden" name="update" id="update" value="<?php echo $update;?>">
			      <input type="hidden" name="updateid" id="updateid" value="<?php echo $id;?>">
			      <input class="button" type="submit" id="submit" name="sumbit" value="Submit" />
			    </dd>
			  </dl>
			</form>
		</div>
	</section>
	
	<?php
	if ($id != null) {?>
		<section>
		<h2 class="textmargin">Foto toevoegen</h2>
		
		<form action="upload.php" method="post" name="image_upload" id="image_upload" enctype="multipart/form-data">
			<label>Mogelijke extensies: jpg, jpeg, png, gif</label><br />
			<input type="hidden" name="upload" id="upload" value="<?php echo $id;?>">
			<input type="file" size="45" name="uploadfile" id="uploadfile" class="file margin_5_0" onchange="ajaxUpload(this.form);" />
			<input type="hidden" name="4_images" type="checkbox" value="" />
			<div id="upload_area">Gelieve een afbeelding te selecteren</div>
		</form>
	</section>
	<?php
	}?>


	<?php
	$qry = $db->prepare("SELECT COUNT(*) AS count FROM projecten_images WHERE projectID = ?");
	$qry->execute(array($id));
	$row = $qry->fetch(PDO::FETCH_ASSOC);

	if ($row['count'] != 0) {
		?>
		<section id="pictures">
			<h2>Foto's</h2>
		</section>
		<?php

		$qry = $db->prepare("SELECT imageID, thumbnail FROM projecten_images WHERE projectID = ?");
		$qry->execute(array($id));

		while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
			$projectafb[] = "<div class=\"image imagebuttons\"><img src=\"images/{$row['thumbnail']}\" width=\"465\" alt=\"\" />";
			$projectafb[] .= "<a href=\"editproject.php?action=remove&amp;id={$id}&amp;imageid={$row['imageID']}\"><div class=\"butremove buttons\"></div></a><a href=\"editproject.php?action=thumbnail&amp;id={$id}&amp;imageid={$row['imageID']}\"><p class=\"thumbnailtext\">Instellen als thumbnail</p></a></div>";		
		}
		?>
		<div class="proj cf">
			<?php echo (count($projectafb) > 0)?"" . implode("", $projectafb):"";?>
		</div>
	<?php
	}?>


	
	<footer class="cf">
		<p class="al">E2R architecten - Hoogstraat 18a - 1861 Wolvertem/Meise - tel. 02/269.25.48</p>
		<p class="ar">Design <a href="mailto:buggenhout.wouter@gmail.com">Wouter B</a> | Code <a href="https://bramvanaken.be/contact.php">Bram VA</a> | Foto's <a href="mailto:marjan.k.photography@gmail.com">Marjan K</a></p>
	</footer>
</body>
</html>
<?php } else { header("location: denied.html"); exit(); }?>