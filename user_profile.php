<?php
	//session_start();
	require_once "classes/SessionManager.class.php";
	SessionManager::sessionStart("vp", 0, "~pebsisab/vp/", "greeny.cs.tlu.ee");
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
	require_once "fnc_general.php";
	require_once "fnc_user.php";
	require_once "classes/Photoupload.class.php";
	
	$description = null;
	$notice = null;
	$photo_error = null;
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST["profile_submit"])){
			$description = test_input($_POST["description_input"]);
			$new_bg_color = test_input($_POST["bg_color_input"]);
			$new_txt_color = test_input($_POST["txt_color_input"]);
			$notice = store_user_profile($description, $new_bg_color, $new_txt_color);
		}//if profile_submit
	}//if method==POST
	
	//profiilipilt
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST["photo_submit"])){
			if(isset($_FILES["photo_input"]["tmp_name"]) and !empty($_FILES["photo_input"]["tmp_name"])){
				$upload = new Photoupload($_FILES["photo_input"]);
				if(empty($upload->error)){
					$upload->check_file_size($photo_file_size_limit);
				}
				if(empty($upload->error)){
					$upload->create_filename($photo_name_prefix);
				}
				if(empty($upload->error)){
					$upload->resize_photo($profile_photo_w, $profile_photo_h, false);
					$upload->save_photo($gallery_photo_profile_folder .$upload->file_name);
				}
				
			} else {
				$photo_error = "Pildifail on valimata!";
			}
		}
	}
	
	$description = read_user_description();
	
	require_once "header.php";
	echo "<p>Sisse loginud: " .$_SESSION["firstname"] ." " .$_SESSION["lastname"] .".</p> \n";
?>
<ul>
	<li><a href="?logout=1">Logi välja</a></li>
	<li><a href="home.php">Avalehele</a></li>
</ul>

<h2>Kasutajaprofiil</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="description_input">Lühikirjeldus</label>
        <br>
        <textarea name="description_input" id="description_input" rows="10" cols="80" placeholder="Minu lühikirjeldus ..."><?php echo $description; ?></textarea>
        <br>
        <label for="bg_color_input">Taustavärv</label>
        <br>
        <input type="color" name="bg_color_input" id="bg_color_input" value="<?php echo $_SESSION["user_bg_color"]; ?>">
        <br>
        <label for="txt_color_input">Tekstivärv</label>
        <br>
        <input type="color" name="txt_color_input" id="txt_color_input" value="<?php echo $_SESSION["user_txt_color"]; ?>">
        <br>
        <input type="submit" name="profile_submit" value="Salvesta">
    </form>
    <span><?php echo $notice; ?></span>

<h2>Profiilipilt</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label for="photo_input">Vali pildifail: </label>
		<input type="file" name="photo_input" id="photo_input">
		<br>
		<input type="submit" name="photo_submit" id="photo_submit" value="Lae üles">
		<span><?php echo $photo_error; ?></span>
	</form>

<?php require_once "footer.php"; ?>