<?php
	require_once "../../../config.php";
	require_once "fnc_registration.php";
	
    $notice = null;
	$first_name = null;
    $last_name = null;
    $student_code = null;
	$registrated_count = null;
	$paid_count = null;
    
    //muutujad võimalike veateadetega
    $first_name_error = null;
    $last_name_error = null;
    $studentcode_error = null;
    
	
	if($_SERVER["REQUEST_METHOD"] === "POST"){
		if(isset($_POST["user_data_submit"])){
			
			if(isset($_POST["first_name_input"]) and !empty($_POST["first_name_input"])){
				$first_name = ($_POST["first_name_input"]);
				if($first_name != $_POST["first_name_input"]){
					$first_name_error = "Palun kontrolli oma eesnime, et seal poleks keelatuid märke!";
				}
			} else {
				$first_name_error = "Palun sisesta eesnimi!";
			}
			
			if(isset($_POST["last_name_input"]) and !empty($_POST["last_name_input"])){
				$last_name = ($_POST["last_name_input"]);
				if($last_name != $_POST["last_name_input"]){
					$last_name_error = "Palun kontrolli oma perekonnanime, et seal poleks keelatuid märke!";
				}
			} else {
				$last_name_error = "Palun sisesta perekonnanime!";
			}
			
			if(isset($_POST["student_code_input"]) and !empty($_POST["student_code_input"])){
				$student_code = ($_POST["student_code_input"]);
				if($student_code != $_POST["student_code_input"]){
					$studentcode_error = "Palun kontrolli oma üliõpilaskoodi, et seal poleks keelatuid märke!";
				}
			} else {
				$studentcode_error = "Palun sisesta üliõpilaskood!";
			}

            //kui kõik kombes, salvestame uue kasutaja
            if(empty($firstname_error) and empty($last_name_error) and empty($studentcode_error)){
				//salvestame andmetabelisse
				$notice = sign_up($student_code, $first_name, $last_name);
				if($notice == 1){
					$notice = "Peole edukalt registreeritud!";
					//$notice = null;
					$first_name = null;
					$last_name = null;
					$student_code = null;
				} else {
					if($notice == 2){
						$notice = "Sellise üliõpilaskoodiga on juba registreeritud!";
					} else {
						$notice = "Registreerimisel tekkis tõrge!";
					}
				}
			}
		} //if submit lõppeb
	} //if POST lõppeb	

	$registrated_count = count_registrated();
	$paid_count = count_paid();
	
?>


<!DOCTYPE html>
<html lang="et">
  <head>
    <meta charset="utf-8">
	
  </head>
  <body>
	
	<hr>
    <h2>Registreerimine</h2>
		
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label for="first_name_input">Eesnimi:</label><br>
	  <input name="first_name_input" id="first_name_input" type="text" value="<?php echo $first_name; ?>"><span><?php echo $first_name_error; ?></span><br>
      <label for="lastname_input">Perekonnanimi:</label><br>
	  <input name="last_name_input" id="last_name_input" type="text" value="<?php echo $last_name; ?>"><span><?php echo $last_name_error; ?></span>
	  <br>
	  <label for="studentcode_input">Üliõpilaskood:</label><br>
	  <input name="student_code_input" id="student_code_input" type="text" value="<?php echo $student_code; ?>"><span><?php echo $studentcode_error; ?></span>
	  <br>
	  <input name="user_data_submit" type="submit" value="Registreeri">
	  <span><?php echo $notice; ?></span>
	</form>
	
	<hr>
	<h2>Registreerinute arv</h2>
	<p>Hetkel registreerinud: <span><?php echo $registrated_count; ?></span> inimest</p>
	
<?php require_once "../footer.php";?>