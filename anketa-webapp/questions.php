<?php
    /*
     * na ovoj stranici nalaze nalaze se odgovarajuca pitanja
     */
	
	header("Cache-Control: no-cache, must-revalidate");
    include_once "database.php";

    session_start();

    if (!(isset($_SESSION["id_studenta"]) && $_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["tip_pitanja"]))) {
        header("Location: survey.php");
        die();
    }

    if ($_GET["tip_pitanja"] == "fakultet") {
        $subject = "nastavi na RAF-u";
		$tekst = "Posmatrajući prethodni semestar, na skali od 1 do 5 (1 – veoma nezadovoljan, 2 – nezadovoljan, 3 – i nezadovoljan i zadovoljan, 4 – zadovoljan, 5 – veoma zadovoljan, x – ne znam) oceni u kojoj meri si kao student zadovaljan/zadovoljna sledećim:";
    } else if ($_GET["tip_pitanja"] == "predmet") {
        $subject = "predmetu <em>" . $_GET["naziv_predmeta"]."</em>";
        $tekst = "Na skali od 1 do 5 (1 - uopšte se ne slažem, 2 – ne slažem se, 3 – i slažem se, i ne slažem
se, 4 – slažem se, 5 – potpuno se slažem, x – ne znam) izrazi u kojoj meri se slažeš sa sledećim tvrdnjama:";
    } else {
        $subject = "nastavniku <em>" . $_GET["nastavnik"]."</em>" . " na predmetu <em>" . $_GET["naziv_predmeta"]."</em>";
        $tekst = "Na skali od 1 do 5 (1 - uopšte se ne slažem, 2 – ne slažem se, 3 – i slažem se, i ne slažem
se, 4 – slažem se, 5 – potpuno se slažem, x – ne znam) izrazi u kojoj meri se slažeš sa sledećim
tvrdnjama:";
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
    <script>
    </script>
</head>
<body style="margin-left: 2em;">
    <h1>Pitanja o <?php echo $subject ?></h1>
	<p><?php echo $tekst ?></p>
	<br>
    <form method="POST" action="finish.php" id="questions_form">
        <input type="hidden" value="<?php echo $_GET["tip_pitanja"]; ?>" name="tip_pitanja" />
        <?php if (isset($_GET["id_drzi_predmet"]))  { ?>
            <input type="hidden" value="<?php echo $_GET["id_drzi_predmet"]; ?>" name="id_drzi_predmet" />
        <?php } ?>
        <?php if (isset($_GET["id_predmeta"])) { ?>
            <input type="hidden" value="<?php echo $_GET["id_predmeta"]; ?>" name="id_predmeta" />
        <?php } ?>
        <?php if (isset($_GET["naziv_predmeta"])) { ?>
            <input type="hidden" value="<?php echo $_GET["naziv_predmeta"]; ?>" name="naziv_predmeta" />
        <?php }  ?> 
		
		<?php if (isset($_GET["ponovac"])) { ?>
            <input type="hidden" value="ponovac" name="ponovac" />
        <?php }
		
		?>
        <?php
			$tippitanja = $_GET["tip_pitanja"];
			
            $query = "SELECT * FROM pitanje WHERE tip = '$tippitanja' and id_pitanja>81";
           // $stmt = $conn->prepare($query);
           // $stmt->bind_param('s', $_POST["tip_pitanja"]);
           // $stmt->execute();
           // $result = $stmt->get_result();
		   $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
			$format = trim($row["format"]);
                ?>
                <p><?php echo $row["tekst"]; ?></p>
                <?php
					$idpitanja = $row["id_pitanja"];
					$idstudenta = $_SESSION["id_studenta"];
					$idsemestra = $_SESSION["id_semestra"];	
                    //ako je vec odgovoreno, ucitamo taj odgovor
                    if ($_GET["tip_pitanja"] == "fakultet") {						
                        $query = "SELECT odgovor FROM odgovor_fakultet WHERE id_pitanja = '$idpitanja' AND id_studenta = '$idstudenta' AND id_semestra = '$idsemestra'";
                       // $stmt = $conn->prepare($query);
                       // $stmt->bind_param('sss', $row["id_pitanja"], $_SESSION["id_studenta"], $_SESSION["id_semestra"]);
					   
                    } else if ($_GET["tip_pitanja"] == "predmet") {
						$idpredmeta = $_GET["id_predmeta"];						
                        $query = "SELECT odgovor FROM odgovor_predmet WHERE id_pitanja = '$idpitanja' AND id_studenta = '$idstudenta' AND id_predmeta ='$idpredmeta' AND id_semestra = '$idsemestra'";
                       // $stmt = $conn->prepare($query);
                       // $stmt->bind_param('ssss', $row["id_pitanja"], $_SESSION["id_studenta"], $_POST["id_predmeta"], $_SESSION["id_semestra"]);
                    } else {
						$idpredmeta = $_GET["id_predmeta"];
						$iddrzipredmet = $_GET["id_drzi_predmet"];
                        $query = "SELECT odgovor FROM odgovor_nastavnik WHERE id_pitanja = '$idpitanja' AND id_studenta = '$idstudenta' AND id_drzi_predmet = '$iddrzipredmet' AND id_semestra = '$idsemestra'";
                        //$stmt = $conn->prepare($query);
                        //$stmt->bind_param('ssss', $row["id_pitanja"], $_SESSION["id_studenta"], $_POST["id_drzi_predmet"], //$_SESSION["id_semestra"]);
                    }
                   // $stmt->execute();
                   // $result_answer = $stmt->get_result();
					$result_answer = $conn->query($query);
                    if ($row_answer = $result_answer->fetch_assoc()) {
                        $answer = $row_answer["odgovor"];						
                    }else{
						$answer = NULL;
					}
                    if ($format == "ocena") {
                        ?>
                        <input type="radio" name="<?php echo $row["id_pitanja"]; ?>" value="1" <?php if (isset($answer) && $answer == "1") echo "checked"; ?>> 1&nbsp;
                        <input type="radio" name="<?php echo $row["id_pitanja"]; ?>" value="2" <?php if (isset($answer) && $answer == "2") echo "checked"; ?>> 2&nbsp;
                        <input type="radio" name="<?php echo $row["id_pitanja"]; ?>" value="3" <?php if (isset($answer) && $answer == "3") echo "checked"; ?>> 3&nbsp;
                        <input type="radio" name="<?php echo $row["id_pitanja"]; ?>" value="4" <?php if (isset($answer) && $answer == "4") echo "checked"; ?>> 4&nbsp;
                        <input type="radio" name="<?php echo $row["id_pitanja"]; ?>" value="5" <?php if (isset($answer) && $answer == "5") echo "checked"; ?>> 5&nbsp;
						<input type="radio" name="<?php echo $row["id_pitanja"]; ?>" value="x" <?php if (isset($answer) && $answer == "x") echo "checked"; ?>> x&nbsp;
                        <?php
                    } else if($format == "tekst") {
                        ?>
                        <textarea rows="4" cols="50" form="questions_form" name="<?php echo $row["id_pitanja"]; ?>" placeholder="Unesite vaš odgovor..."><?php if (isset($answer)) echo $answer; ?></textarea>
                        <?php
                    }else{
						$odgovori = explode("|",$format);						
						foreach($odgovori as $odgovor){
						?>	
							<input type="radio" name="<?php echo $row["id_pitanja"]; ?>" value="<?php echo $odgovor ?>" <?php if (isset($answer) && $answer == $odgovor) echo "checked"; ?>><?php echo $odgovor?>&nbsp;&nbsp;
						<?php	
						}
					}
                ?>
                <hr>
                <?php
            }
        ?>
        <input type="submit" value="Sačuvaj" class="btn btn-success">
    </form>

    <form method="GET" action="survey.php">
        <input type="submit" value="Odustani" class="btn btn-danger">
    </form>

</body>
</html>
