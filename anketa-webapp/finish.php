<?php
    //header("Cache-Control: no-cache, must-revalidate");
	
	include_once "database.php";

    session_start();

    if (!(isset($_SESSION["id_studenta"]) && $_SERVER["REQUEST_METHOD"] == "POST")) {
        header("Location: survey.php");
        die();
    }

    if(empty($_POST)){
		header('Location: questions.php');
	}else{
		foreach ($_POST as $id_pitanja => $odgovor) {		
        if (is_numeric($id_pitanja) && $odgovor!="") {
            //prvo pokušamo update, ako ne postoji, radimo insert

            if ($_POST["tip_pitanja"] == "fakultet") {
                $query = "INSERT INTO odgovor_fakultet (id_pitanja, id_studenta, id_semestra, odgovor) VALUES (?, ?, ?, ?) 
                          ON DUPLICATE KEY UPDATE odgovor = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('sssss', $id_pitanja, $_SESSION["id_studenta"], $_SESSION["id_semestra"], $odgovor, $odgovor);
                $stmt->execute();
            } else if ($_POST["tip_pitanja"] == "predmet") {
                $query = "INSERT INTO odgovor_predmet (id_pitanja, id_studenta, id_semestra, id_predmeta, odgovor) VALUES (?, ?, ?, ?, ?) 
                          ON DUPLICATE KEY UPDATE odgovor = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ssssss', $id_pitanja, $_SESSION["id_studenta"], $_SESSION["id_semestra"], $_POST["id_predmeta"], $odgovor, $odgovor);
                $stmt->execute();
            } else {
                $query = "INSERT INTO odgovor_nastavnik (id_pitanja, id_studenta, id_semestra, id_drzi_predmet, odgovor) VALUES (?, ?, ?, ?, ?) 
                          ON DUPLICATE KEY UPDATE odgovor = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ssssss', $id_pitanja, $_SESSION["id_studenta"], $_SESSION["id_semestra"], $_POST["id_drzi_predmet"], $odgovor, $odgovor);
                $stmt->execute();
            }
        }
	   }
	  
    }
	
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pitanja</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
</head>
<body style="margin-left: 2em;">
    <h3>Vaši odgovori su sačuvani</h3>
    <?php
    if ($_POST["tip_pitanja"] != "fakultet") {
    ?>
        <form method="GET" action="course.php">
            <input type="hidden" value="<?php echo $_POST["id_predmeta"]; ?>" name="id_predmeta" />
            <input type="hidden" value="<?php echo $_POST["naziv_predmeta"]; ?>" name="naziv_predmeta" />
			<?php if (isset($_POST["ponovac"])) { ?>
				<input type="hidden" value="ponovac" name="ponovac" />
			<?php } ?>
            <input type="submit" class="btn btn-primary" value="Vrati se na predmet">
			
        </form>
    <?php
    }
    ?>
    <form method="GET" action="survey.php">
        <input type="submit" class="btn btn-info" value="Vrati se na početak">
    </form>
	<?php
		//echo $_POST["tip_pitanja"];
		//header("Location: finish.php"); ?>
</body>
</html>



