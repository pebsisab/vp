<?php

	require_once "../../config.php";
	
	function sign_in($email, $password){
		$login_error = null;
		//globaalseid muutujaid hoitakse massiivis $GLOBALS
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT password FROM vp_users WHERE email = ?");
        echo $conn->error;
        $stmt->bind_param("s", $email);
        $stmt->bind_result($password_from_db);
        $stmt->execute();
        if($stmt->fetch()){ //teeb tulemused prepared olekusse
            //kasutaja on olemas, parool tuli ...
            if(password_verify($password, $password_from_db)){
				$stmt->close();
				$stmt = $conn->prepare("SELECT id, firstname, lastname FROM vp_users WHERE email = ?");
                echo $conn->error;
				$stmt->bind_param("s", $email);
				$stmt->bind_result($id_from_db, $first_name_from_db, $last_name_from_db);
				$stmt->execute();
				if($stmt->fetch()){
					$_SESSION["user_id"] = $id_from_db;
					$_SESSION["firstname"] = $first_name_from_db;
					$_SESSION["lastname"] = $last_name_from_db;
					$stmt->close();
					$conn->close();
					header("Location: home.php");
					exit();
				} else {
					$login_error = "Kasutajatunnus või salasõna oli vale!";
				}
            } else {
                $login_error = "Kasutajatunnus või salasõna oli vale!";
            }
        } else {
            $login_error = "Kasutajatunnus või salasõna oli vale!";
        }
        
        $stmt->close();
        $conn->close();
		return $login_error;
	}

	function sign_up($first_name, $last_name, $birth_date, $gender, $email, $password){
		$notice = 0;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id FROM vp_users WHERE email = ?");
		echo $conn->error;
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = 2;
		} else {
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO vp_users (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)");
			echo $conn->error;
			//krüpteerime salasõna
			$pwd_hash = password_hash($password, PASSWORD_DEFAULT);
			$stmt->bind_param("sssiss", $first_name, $last_name, $birth_date, $gender, $email, $pwd_hash);
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

?>