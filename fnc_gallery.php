<?php
	require_once "../../config.php";
	
	function read_public_photos($privacy, $page, $limit){
		$skip = ($page - 1) * $limit;
        $photo_html = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        //$stmt = $conn->prepare("SELECT filename, alttext FROM vp_photos WHERE privacy >= ? AND deleted IS NULL");
		//LIMIT x - mitu näidata
		//LIMIT x,y - mitu vahele jätta, mitu näidata
		$stmt = $conn->prepare("SELECT vp_photos.id, filename, alttext, vp_photos.created, firstname, lastname, AVG(rating) as AvgValue FROM vp_photos JOIN vp_users ON vp_photos.userid = vp_users.id LEFT JOIN vp_photoratings ON vp_photoratings.photoid = vp_photos.id WHERE vp_photos.privacy >= ? AND deleted IS NULL GROUP BY vp_photos.id DESC LIMIT ?,?");
        echo $conn->error;
        $stmt->bind_param("iii", $privacy, $skip, $limit);
        $stmt->bind_result($id_from_db, $filename_from_db, $alttext_from_db, $created_from_db, $firstname_from_db, $lastname_from_db, $avg_from_db);
        $stmt->execute();
        while($stmt->fetch()){
			//<img src="photo_upload_thumbnail/vp_16672936178946.jpg" alt="hawwww" class="thumbs" data-filename="vp_16672936178946.jpg" data-id="43">
			$photo_html .= '<div class="thumbgallery">' ."\n";
			$photo_html .= '<img src="' .$GLOBALS["gallery_photo_thumbnail_folder"] .$filename_from_db .'" alt="';
            if(empty($alttext_from_db)){
                $photo_html .= "Üleslaetud foto";
            } else {
                $photo_html .= $alttext_from_db;
            }
            $photo_html .= '" class="thumbs" data-filename="' .$filename_from_db .'" data-id="' .$id_from_db .'">' ."\n";
            $photo_html .= "<p>" .$firstname_from_db ." " .$lastname_from_db ."</p> \n";
			$photo_html .= "<p>" .$avg_from_db ."</p> \n";
			$photo_html .= "</div> \n";
        }
        $stmt->close();
		$conn->close();
		return $photo_html;
    }
	
	/*function latest_public_photo(){
		$skip = ($page - 1) * 1;
        $photo_html = null;
		$privacy = 3
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT filename, alttext, FROM vp_photos WHERE id = (SELECT MAX(id)) FROM vp_photos WHERE privacy = ? AND deleted IS NULL");
        echo $conn->error;
        $stmt->bind_param("i", $privacy);
        $stmt->bind_result($filename_from_db, $alttext_from_db);
        $stmt->execute();
        while($stmt->fetch()){
			$photo_html .= '<div class="thumbgallery">' ."\n";
			$photo_html .= '<img src="' .$GLOBALS["gallery_photo_thumbnail_folder"] .$filename_from_db .'" alt="';
            if(empty($alttext_from_db)){
                $photo_html .= "Üleslaetud foto";
            } else {
                $photo_html .= $alttext_from_db;
            }
            $photo_html .= '" class="thumbs">' ."\n";
            $photo_html .= "<p>" .$firstname_from_db ." " .$lastname_from_db ."</p> \n";
			$photo_html .= "</div> \n";
        }
        $stmt->close();
		$conn->close();
		return $photo_html;
    } */
	
	function read_own_photos($page, $limit){
		$skip = ($page - 1) * $limit;
        $photo_html = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT id, filename, alttext FROM vp_photos WHERE userid = ? AND deleted IS NULL ORDER BY id DESC LIMIT ?,?");
        echo $conn->error;
        $stmt->bind_param("iii", $_SESSION["user_id"], $skip, $limit);
        $stmt->bind_result($id_from_db, $filename_from_db, $alttext_from_db);
        $stmt->execute();
        while($stmt->fetch()){
			$photo_html .= '<div class="thumbgallery">' ."\n";
			$photo_html .= '<img src="' .$GLOBALS["gallery_photo_thumbnail_folder"] .$filename_from_db .'" alt="';
            if(empty($alttext_from_db)){
                $photo_html .= "Üleslaetud foto";
            } else {
                $photo_html .= $alttext_from_db;
            }
            $photo_html .= '" class="thumbs">' ."\n";
			//<p><a href="edit_photo_data.php?id=5">Muuda andmeid</a></p>
            $photo_html .= '<p><a href="edit_photo_data.php?id=' .$id_from_db .'">Muuda andmeid</a></p>' ."\n";
			$photo_html .= "</div> \n";
        }
        $stmt->close();
		$conn->close();
		return $photo_html;
    }
	
	function count_photos($privacy){
        $photo_count = 0;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT COUNT(id) FROM vp_photos WHERE privacy >= ? AND deleted IS NULL");
        echo $conn->error;
        $stmt->bind_param("i", $privacy);
        $stmt->bind_result($count_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            $photo_count = $count_from_db;
        }
        $stmt->close();
		$conn->close();
		return $photo_count;
    }
	
	function count_own_photos(){
        $photo_count = 0;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT COUNT(id) FROM vp_photos WHERE userid = ? AND deleted IS NULL");
        echo $conn->error;
		//sessiooni sisseloginud kasutaja info lugemine
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->bind_result($count_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            $photo_count = $count_from_db;
        }
        $stmt->close();
		$conn->close();
		return $photo_count;
    }
	
	function read_own_photo_data($id){
		$photo_data = [];
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT filename, alttext, privacy FROM vp_photos WHERE id = ?");
        echo $conn->error;
		$stmt->bind_param("i", $id);
		$stmt->bind_result($filename_from_db, $alttext_from_db, $privacy_from_db, );
		$stmt->execute();
        if($stmt->fetch()){
			$photo_data["filename"] = $filename_from_db;
			$photo_data["alt"] = $alttext_from_db;
			$photo_data["privacy"] = $privacy_from_db;
		}
		$stmt->close();
		$conn->close();
		return $photo_data;
	}
	
	function update_photo_data($alt, $privacy, $id){
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT userid FROM vp_photos WHERE id = ?");
        echo $conn->error;
        $stmt->bind_param("i", $id);
        $stmt->bind_result($userid_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            if($userid_from_db == $_SESSION["user_id"]){
                //$stmt->close();
                $stmt->prepare("UPDATE vp_photos SET alttext = ?, privacy = ? WHERE id = ?");
                $stmt->bind_param("sii", $alt, $privacy, $id);
                $stmt->execute();
                $stmt->close();
                $conn->close();
                exit();
			}
		}
        echo $stmt->error;
        $stmt->close();
        $conn->close();
        header("Location: gallery_own.php");
        exit();
        
	}
	
	function delete_own_photo_data($id){
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT userid FROM vp_photos WHERE id = ?");
        echo $conn->error;
        $stmt->bind_param("i", $id);
        $stmt->bind_result($userid_from_db);
        $stmt->execute();
        echo $stmt->error;
        //kontrollime kas muutja user id ja sessiooni user id on sama, siis võib kustutada
        if($stmt->fetch()){
            if($userid_from_db == $_SESSION["user_id"]){
                //$stmt->close();
                $stmt->prepare("UPDATE vp_photos SET deleted = now() WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->close();
                $conn->close();
                exit();
            }
        }
        header("Location: gallery_own.php");
        exit();
    }
	
	function show_latest_public_photo(){
        $photo_html = null;
        $privacy = 3;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT id, alttext FROM vp_photos WHERE id = (SELECT MAX(id) FROM vp_photos WHERE privacy = ? AND deleted IS NULL)");
        echo $conn->error;
        $stmt->bind_param("i", $privacy);
        $stmt->bind_result($id_from_db, $alttext_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            //<img src="kataloog/fail" alt="tekst">
			//<img src="show_public_photo.php?photo=74" alt="tekst">
            //$photo_html = '<img src="' .$GLOBALS["gallery_photo_normal_folder"] .$filename_from_db .'" alt="';
			$photo_html = '<img src="show_public_photo.php?photo=' .$id_from_db .'" alt="';
            if(empty($alttext_from_db)){
                $photo_html .= "Üleslaetud foto";
            } else {
                $photo_html .= $alttext_from_db;
            }
            $photo_html .= '">' ."\n";
        } else {
            $photo_html = "<p>Kahjuks pole ühtegi avalikku fotot üles laetud!</p>";
        }
        $stmt->close();
		$conn->close();
		return $photo_html;
    }