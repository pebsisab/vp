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
	
	require_once "header.php";
	
?>
<ul>
	<p> Sisse logitud: <?php echo $_SESSION["firstname"]." ".$_SESSION["lastname"]; ?>
	<li>Logi <a href="?logout=1">välja</a></li>
	<li>Fotode galeriisse <a href="gallery_photo_upload.php">lisamine</a></li>
	<br>
	<li><a href="add_new_film.php">Lisa uusi filme siit</a></li>
	<li><a href="display_film.php">Vaata lisatuid filme</a></li>
	<li><a href="read_daycomments.php">Vaata lisatuid päevakommentaare</a></li>
</ul>
<?php require_once "footer.php";?>	