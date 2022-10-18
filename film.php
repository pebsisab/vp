<?php
	//algatan sessiooni
	session_start();
	//loen sisse konfiguratsioonifaili
	require_once "fnc_user.php";
	
	require_once "fnc_user.php";
	if(!isset($_SESSION["user_id"])){
		//jõuga viiakse page.php
		header("Location: page.php");
		exit();
	}
	//logime välja
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
		exit();
	}
	require_once "header.php";

	require_once "../../config.php";
	$title_error = null;
	$year_error = null;
	$duration_error = null;
	$genre_error = null;
	$studio_error = null;
	$director_error = null;
	
	//tegeleme filmi vormi sisestatud infoga
	if(isset($_POST["film_submit"])){
		if(isset($_POST["title_input"]) and !empty($_POST["title_input"])){
			$title = $_POST["title_input"];
		} else {
			$title_error = "Kommentaar jäi lisamata!";
		}
		if(isset($_POST["year_input"]) and !empty($_POST["year_input"])){
			$year = $_POST["year_input"];
		} else {
			$year_error = "Kommentaar jäi lisamata!";
		}
		if(isset($_POST["duration_input"]) and !empty($_POST["duration_input"])){
			$duration = $_POST["duration_input"];
		} else {
			$duration_error = "Kommentaar jäi lisamata!";
		}
		if(isset($_POST["genre_input"]) and !empty($_POST["genre_input"])){
			$genre = $_POST["genre_input"];
		} else {
			$genre_error = "Kommentaar jäi lisamata!";
		}
		if(isset($_POST["studio_input"]) and !empty($_POST["studio_input"])){
			$studio = $_POST["studio_input"];
		} else {
			$studio_error = "Kommentaar jäi lisamata!";
		}
		if(isset($_POST["director_input"]) and !empty($_POST["director_input"])){
			$director = $_POST["director_input"];
		} else {
			$director_error = "Kommentaar jäi lisamata!";
		}
		
		if(empty($title_error and $year_error and $duration_error and $genre_error and $studio_error and $director_error)){
			//loome andmebaasiühenduse
			$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
			//määrame suhtlemisel kasutatava kooditabeli
			$conn->set_charset("utf8");
			//valmistame ette SQL keeles päringu
			$stmt = $conn->prepare("INSERT INTO film (pealkiri, aasta, kestus, zanr, tootja, lavastaja) VALUES(?,?,?,?,?,?)");
			echo $conn->error;
			//seome SQL päringu päris andmetega
			//määrata andmetüübid: i-integer (täisarv), d-decimel (murdarv), s-string (text)
			$stmt->bind_param("siisss", $title, $year, $duration, $genre, $studio, $director);
			//täidame käsu
			$stmt->execute();
			echo $stmt->error;
			//sulgeme käsu/päringu
			$stmt->close();
			//sulgeme andmebaasiühenduse
			$conn->close();
		}
	}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Isabella Pebsen, veebiprogrammeerimine</title>
</head>
<body>

	<ul>
		<li>Logi <a href="?logout=1">välja</a></li>
	</ul>

	<h1>Isabella Pebsen, veebiprogrammeerimine</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsist infot</p>
	<p>õppetöö toimus <a href="https://www.tlu.ee">Tallinna Ülikoolis</a>, Digitehnoloogiate instituudis.</p>
	<img src="pics/tlu_40.jpg" alt="Tallinna Ülikooli Astra õppehoone">

 <form method="POST">
        <label for="title_input">Filmi pealkiri</label>
        <input type="text" name="title_input" id="title_input" placeholder="filmi pealkiri">
		<span><?php echo $title_error; ?>
        <br>
        <label for="year_input">Valmimisaasta</label>
        <input type="number" name="year_input" id="year_input" min="1912">
		<span><?php echo $year_error; ?>
        <br>
        <label for="duration_input">Kestus</label>
        <input type="number" name="duration_input" id="duration_input" min="1" value="60" max="600">
		<span><?php echo $duration_error; ?>
        <br>
        <label for="genre_input">Filmi žanr</label>
        <input type="text" name="genre_input" id="genre_input" placeholder="žanr">
		<span><?php echo $genre_error; ?>
        <br>
        <label for="studio_input">Filmi tootja</label>
        <input type="text" name="studio_input" id="studio_input" placeholder="filmi tootja">
		<span><?php echo $studio_error; ?>
        <br>
        <label for="director_input">Filmi režissöör</label>
        <input type="text" name="director_input" id="director_input" placeholder="filmi režissöör">
		<span><?php echo $director_error; ?>
        <br>
        <input type="submit" name="film_submit" value="Salvesta">
    </form>

</body>
</html>