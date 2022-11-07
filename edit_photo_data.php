<?php
	session_start();
	if(!isset($_SESSION["user_id"])){
		//jõuga viiakse page.php lehele
		header("Location: page.php");
		exit();
	}
	
	//logime välja
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
		exit();
	}
	
	require_once "fnc_gallery.php";
	require_once "fnc_general.php";
	
	//kontrollin pildi valikut
	$photo_error = null;
	$alt = null;
	$privacy = 1;
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST["photo_submit"])){
			$alt = test_input($_POST["alt_input"]);
			$privacy = filter_var($_POST["privacy_input"], FILTER_VALIDATE_INT);
			$id = filter_var($_POST["photo_input"], FILTER_VALIDATE_INT);
			//andmete uuendamise osa, funktsiooni välja kutsumine
			$photo_update = update_photo_data($alt, $privacy, $id);
		}
	}
	
	if(isset($_GET["id"]) and !empty($_GET["id"]) and filter_var($_GET["id"], FILTER_VALIDATE_INT)){
		$photo_data = read_own_photo_data($_GET["id"]);
		$alt = $photo_data["alt"];
		$privacy = $photo_data["privacy"];
		
	}
	
	if(isset($_POST["photo_delete_submit"])){
        //mida me siin kontrollime?
        if(isset($_POST["photo_input"]) and filter_var($_POST["photo_input"], FILTER_VALIDATE_INT)){
            $id = filter_var($_POST["photo_input"], FILTER_VALIDATE_INT);
            $photo_error = delete_own_photo_data($id);
            if (empty($photo_error)){
                $photo_error = "Pilt sai kustutatud";
            } else {
                $photo_error = "Pole luba pilti kustutada. Saate kustutada vaid enda faile.";
            } 
        } else {
        $photo_error = "Pilti ei saanud kustutada";
        }
    }
	
	require_once "header.php";
	
	echo "<p>Sisse loginud: " .$_SESSION["firstname"] ." " .$_SESSION["lastname"] .".</p> \n";
?>
<ul>
	<li>Logi <a href="?logout=1">välja</a></li>
	<li>Tagasi <a href="home.php">avalehele</a></li>
	
</ul>
	<hr>
	<h2>Fotode andmete muutmine</h2>
	<?php
		//<img src="kataloog/fail" alt="tekst">
		echo '<img src="' .$gallery_photo_normal_folder .$photo_data["filename"] .'" alt="' .$alt .'">' ."\n";
	?>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<input type="hidden" name="photo_input" id="photo_input" value="<?php echo $_GET["id"]; ?>">
		<br>
		<label for="alt_input">Alternatiivtekst (alt): </label>
		<input type="text" name="alt_input" id="alt_input" placeholder="alternatiivtekst ..." value="<?php echo $alt; ?>">
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_1" value="1"<?php if($privacy == 1){echo " checked";}?>>
		<label for="privacy_input_1">Privaatne (ainult ise näen)</label>
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_2" value="2"<?php if($privacy == 2){echo " checked";}?>>
		<label for="privacy_input_2">Sisseloginud kasutajatele</label>
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_3" value="3"<?php if($privacy == 3){echo " checked";}?>>
		<label for="privacy_input_3">Avalik (kõik näevad)</label>
		<br>
		<input type="submit" name="photo_submit" id="photo_submit" value="Muuda">
		<span><?php echo $photo_error; ?></span>
	</form>
	<!-- Lisan kustutamise formi -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <input type="hidden" name="photo_input" value="<?php echo $_GET["id"]; ?>">
        <input type="submit" name="photo_delete_submit" id="photo_delete_submit" value="Kustuta">
    </form>
<?php require_once "footer.php"; ?>