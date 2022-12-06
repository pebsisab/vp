<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>veebiprogrammeerimine</title>
	<style>
		body {
			background-color: <?php echo $_SESSION["user_bg_color"];?>;
			color: <?php echo $_SESSION["user_txt_color"];?>;
		}
	</style>
	<?php
		
		//$style_sheets = ["styles/gallery.css"];
        if(isset($style_sheets) and !empty($style_sheets)){
            for($i = 0;$i < count($style_sheets);$i++){
            //<link rel="stylesheet" href="styles/gallery.css">
                echo '<link rel="stylesheet" href="' .$style_sheets[$i] .'">' ."\n";
            }
        }
		
		if(isset($javascripts) and !empty($javascripts)){
            foreach($javascripts as $js){
            //<script src="javascript.js" defer></script>
                echo '<script src="' .$js .'" defer></script>' ."\n";
            }
        }
	?>
</head>
<body>
	<img src="pics/vp_banner_gs.png" alt="banner">
	<h1>Veebiprogrammeerimine</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsist infot</p>