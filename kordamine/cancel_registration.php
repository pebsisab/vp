<?php
	require_once "../../../config.php";
	require_once "fnc_registration.php";
	
    $notice = null;
    $student_code = null;
    
    //muutujad võimalike veateadetega
    $studentcode_error = null;
    
	
	if($_SERVER["REQUEST_METHOD"] === "POST"){
		if(isset($_POST["user_data_submit"])){
			
			if(isset($_POST["student_code_input"]) and !empty($_POST["student_code_input"])){
				$student_code = ($_POST["student_code_input"]);
				if($student_code != $_POST["student_code_input"]){
					$studentcode_error = "Palun kontrolli oma üliõpilaskoodi, et seal poleks keelatuid märke!";
				}
			} else {
				$studentcode_error = "Palun sisesta üliõpilaskood!";
			}

            //kui kõik kombes, salvestame uue kasutaja
            if(empty($studentcode_error)){
				//salvestame andmetabelisse
				$notice = delete_registration($student_code);
				if($notice == 1){
					$notice = "Registreerimine on tühistatud!";
					//$notice = null;
					$student_code = null;
				} else {
					if($notice == 2){
						$notice = "Registreerimise tühistamisel tekkis tõrge!";
					} else {
						$notice = "Registreerimisel tekkis tõrge!";
					} 
				}
			}
		} //if submit lõppeb
	} //if POST lõppeb	


?>

<!DOCTYPE html>
<html lang="et">
  <head>
    <meta charset="utf-8">
	
  </head>
  <body>
	
	<hr>
    <h2>Registreerimise tühistamine</h2>
		
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label for="studentcode_input">Üliõpilaskood:</label><br>
	  <input name="student_code_input" id="student_code_input" type="text" value="<?php echo $student_code; ?>"><span><?php echo $studentcode_error; ?></span>
	  <br>
	  <input name="user_data_submit" type="submit" value="Tühista">
	  <span><?php echo $notice; ?></span>
	</form>
	
<?php require_once "../footer.php";?>