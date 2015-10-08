<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<title>Contact | E2R-architecten</title>
	<link rel="stylesheet" type="text/css" href="data/style-main.css" />
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto" />
	<link rel="shortcut icon" href="data/img-icon.ico" />
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
		            <li><a href="projecten.php#nietres">Niet-residentiële gebouwen</a></li>
		        </ul>
			</li>
			<li class="ov"><a href="overons.html">Over Ons</a></li>
			<li class="co coa"><a href="contact.php">Contact</a></li>
		</ul>
		</nav>
	</header>
	
	<div class="cf">
		<section class="l fixedheight">
			<h2 class="textmargin">Contact</h2>
			<p>Indien u nog vragen heeft, aarzel dan niet om contact op te nemen. Dit kan met onderstaande gegevens:</p>
			<p>&nbsp;</p>
			<div class="al adres cf">
				<p>E2R-architecten bvba</p>
				<p>Sint-Jozefstraat 10 A001</p>
				<p>1840 Londerzeel</p>
				<p>Tel. 0497/72.44.72</p>
				<p><a href="mailto:info@e2r-architecten.be">info@e2r-architecten.be</a></p>
			</div>
			<div class="ar adres cf">
				<p>Etienne Wijns</p>
				<p>Hoogstraat 18a</p>
				<p>1861 Meise</p>
				<p>Tel. 02/269.25.48</p>
				<p><a href="mailto:architect.wijns@skynet.be">architect.wijns@skynet.be</a></p>
			</div>
			<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
			<p>Of u kan onderstaand formulier invullen:</p>
			<p>&nbsp;</p>

			<?php
			$name = $_POST['name'];
			$email = $_POST['email'];
			$telefoon = $_POST['telefoon'];
			$onderwerp = $_POST['subject'];
			$comments = $_POST['comments'];
			if (($_POST) && ($name != '') && ($comments != '') && (filter_var($email, FILTER_VALIDATE_EMAIL))) {
				$bericht = "Onderwerp: ".$onderwerp."\nTel nr.: ".$telefoon."\nBericht:\n\n\n".$comments;

				mail("info@e2r-architecten.be", "E2R Contact van $name", $bericht, "From: $email");
				echo "<strong>Uw bericht is verzonden</strong>";
			} else {?>
			<form method="post">
			  <dl>
			  	<dt><label for="name">Naam:</label></dt>
			    <dd>
			      <input type="text" name="name" id="name" size="32" value="<?php echo $name;?>" />
			      <p><span class="error"><?php if (($_POST) && ($name == '')) {echo "Gelieve een naam in te voeren";}?></span></p>
			    </dd>
			    <dt><label for="email">Email adres:</label></dt>
			    <dd>
			      <input type="email" name="email" id="email" size="32" value="<?php echo $email;?>" />
			      <p><span class="error"><?php if (($_POST) && (!filter_var($email, FILTER_VALIDATE_EMAIL))) {echo "Ongeldig e-mail adres";}?></span></p>
			    </dd>
			    <dt><label for="telefoon">Tel. nr:</label></dt>
			    <dd>
			      <input type="text" name="telefoon" id="telefoon" size="16" value="<?php echo $telefoon;?>" />
			    </dd>
			    <dt>Onderwerp:</dt>
			    <dd>
			      <select name="subject">
					<option value="Algemeen">Algemeen</option>
					<option value="Architectuur">Architectuur</option>
					<option value="EPC">EPC</option>
					<option value="Expertise">Expertise</option>
					<option value="Plaatsbeschrijving">Plaatsbeschrijving</option>
				  </select>
				</dd>
			    <dt><label for="comments">Bericht:</label></dt>
			    <dd>
			      <textarea name="comments" id="comments" rows="4" cols="26"><?php echo $comments;?></textarea>
			      <p><span class="error"><?php if (($_POST) && ($comments == '')) {echo "Gelieve een bericht in te voeren";}?></span></p>
			    </dd>
			    <dt> </dt>
			    <dd>
			      <input class="button" type="submit" id="submit" name="sumbit" value="Verzend" />
			    </dd>
			  </dl>
			</form>
			<?php }?>
		</section>

		<section class="r fixedheight">
			<h2 class="textmargin">Vind Ons</h2>
			<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2513.4709159204785!2d4.3103013500000005!3d50.95199705!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c3ea17a67fd5f1%3A0xe7caa14bb8eac4fd!2sHoogstraat+18!5e0!3m2!1sen!2s!4v1393069299656" width="415" height="350"></iframe>			
			<a href="https://www.google.com/maps/place/Hoogstraat+18/@50.9519971,4.3103014,17z/data=!3m1!4b1!4m2!3m1!1s0x47c3ea17a67fd5f1:0xe7caa14bb8eac4fd" class="bigbutton">Ga naar Google Maps</a>
		</section>
	</div>
	
	<footer class="cf">
		<p class="al">E2R architecten - Hoogstraat 18a - 1861 Wolvertem/Meise - tel. 02/269.25.48</p>
		<p class="ar">Design <a href="mailto:buggenhout.wouter@gmail.com">Wouter B</a> | Code <a href="https://bramvanaken.be/#contact">Bram VA</a> | Foto's <a href="mailto:marjan.k.photography@gmail.com">Marjan K</a></p>
	</footer>
</body>
</html>