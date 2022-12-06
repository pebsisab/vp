<?php

	require_once "../../../config.php";

	function sign_up($student_code, $first_name, $last_name){
		$notice = 0;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id FROM vp_peol_osalejad WHERE yliopilaskood = ?");
		echo $conn->error;
		$stmt->bind_param("s", $student_code);
		$stmt->bind_result($id_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = 2;
		} else {
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO vp_peol_osalejad (yliopilaskood, eesnimi, perekonnanimi) VALUES(?,?,?)");
			echo $conn->error;
			$stmt->bind_param("sss", $student_code, $first_name, $last_name);
			if($stmt->execute()){
				$notice = 1;
			} else {
				$notice = 3;
			}
		}
		//echo $stmt->error;
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function count_registrated(){
        $registration_count = 0;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT COUNT(id) FROM vp_peol_osalejad WHERE tuhistanud IS NULL");
        echo $conn->error;
        $stmt->bind_result($count_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            $registration_count = $count_from_db;
        }
        $stmt->close();
		$conn->close();
		return $registration_count;
    }
	
	function count_paid(){
        $paid_count = 0;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT COUNT(id) FROM vp_peol_osalejad WHERE maksnud IS NOT NULL");
        echo $conn->error;
        $stmt->bind_result($count_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            $paid_count = $count_from_db;
        }
        $stmt->close();
		$conn->close();
		return $paid_count;
    }
	
	function delete_registration($student_code){
		$notice = 0;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT id FROM vp_peol_osalejad WHERE yliopilaskood = ?");
        echo $conn->error;
        $stmt->bind_param("s", $student_code);
        $stmt->bind_result($id_from_db);
        $stmt->execute();
        echo $stmt->error;
        if($stmt->fetch()){
			$stmt->close();
			$stmt = $conn->prepare("UPDATE vp_peol_osalejad SET tuhistanud = now() WHERE yliopilaskood = ?");
			$stmt->bind_param("s", $student_code);
			if($stmt->execute()){
				$notice = 1;
			} else {
				$notice = 2;
			}
			$stmt->close();
			$conn->close();
        }
    }
	
	?>