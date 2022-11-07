<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>veebiprogrammeerimine</title>
	<?php
		if(isset($style_sheets)){
			//<link rel="stylesheet" href="styles/gallery.css">
			echo '<link rel="stylesheet" href="' .$style_sheets .'">' ."\n";
		}
		
		$style_sheets = ["styles/gallery.css"];
        if(isset($style_sheets) and !empty($style_sheets)){
            for($i = 0;$i < count($style_sheets);$i++){
            //<link rel="stylesheet" href="styles/gallery.css">
                echo '<link rel="stylesheet" href="' .$style_sheets[$i] .'">' ."\n";
            }
        }
	?>
</head>
<body>
	<img src="pics/vp_banner_gs.png" alt="banner">
	<h1>Veebiprogrammeerimine</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsist infot</p>