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
	
	//loome andmebaasiühenduse
	$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
	//määrame suhtlemisel kasutatava kooditabeli
	$conn->set_charset("utf8");
	//valmistame ette SQL keeles päringu
	$stmt = $conn->prepare("SELECT pealkiri, aasta, kestus, zanr, tootja, lavastaja FROM film");
	echo $conn->error;
	//seome loetavad andmed muutujaga
	$stmt->bind_result($title_from_db, $year_from_db, $duration_from_db, $genre_from_db, $studio_from_db, $director_from_db);
	//täidame käsu
	$stmt->execute();
	echo $stmt->error;
	//võtan andmed
	//kui on oodata vaid üks võimalik kirje
	//if($stmt->fetch()){
		//kõik mida teha, nt näita kuupäeva jne
	//}
	$film_html = null;
	//Kui on oodata mitut, aga teadmata arv
	while($stmt->fetch()){
		// <p>Kommentaar, hinne päevale: x, lisatud yyyyy.</p>
		$film_html .= "<h3>" .$title_from_db ."</h3>" ."<ul>";	
		$film_html .= "<li>Valmimisaasta: " .$year_from_db ."</li>";
		$film_html .= "<li>Kestus: " .$duration_from_db ."</li>";
		$film_html .= "<li>Zanr: " .$genre_from_db ."</li>";
		$film_html .= "<li>Tootja: " .$studio_from_db ."</li>";
		$film_html .= "<li>Lavastaja: " .$director_from_db ."</li>" ."</ul>";
	}
	//sulgeme käsu/päringu
	$stmt->close();
	//sulgeme andmbeaasi ühenduse
	$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Isabella Pebsen, veebiprogrammeerimine</title>
</head>
<body>
	<img src="pics/vp_banner_gs.png" alt="banner">
	<h1>Isabella Pebsen, veebiprogrammeerimine</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsist infot</p>
	<p>õppetöö toimus <a href="https://www.tlu.ee">Tallinna Ülikoolis</a>, Digitehnoloogiate instituudis.</p>

	<?php echo $film_html; ?>
</body>
</html>