<?php
    /*
     * na ovoj stranici nalaze se sekcije pitanja za odabrani predmet. Generalna pitanja, i za svakog nastavnika posebno.
     */
    include_once "database.php";

    session_start();

    if (!(isset($_SESSION["id_studenta"]) && $_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id_predmeta"]) && isset($_GET["naziv_predmeta"]))) {
        header("Location: survey.php");
        die();
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Kurs</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
		input{
			margin: 13px 0px;
		}
	</style>
</head>
<body style="margin-left: 2em;">
    <h1><?php echo $_GET["naziv_predmeta"]; ?></h1>

    <table>
        <tr>
            <form method="GET" action="questions.php">
                <td>
                    <input type="hidden" value="predmet" name="tip_pitanja" />
                    <input type="hidden" value="<?php echo $_GET["id_predmeta"]; ?>" name="id_predmeta" />
                    <input type="hidden" value="<?php echo $_GET["naziv_predmeta"]; ?>" name="naziv_predmeta" />
                    <input type="submit" class="btn btn-info" value="Oceni predmet">
                </td>
            </form>
        </tr>
        <br>
	
        <?php
/*
        //listanje svih nastavnika na izabranom predmetu koji predaju studentu
		$idstudenta = $_SESSION["id_studenta"];
		$idpredmeta = $_GET["id_predmeta"];
		$idsemestra =  $_SESSION["id_semestra"];
        $query = "SELECT nastavnik.ime, nastavnik.prezime, nastavnik.tip, drzi_predmet.id
                  FROM student_u_grupi 
                  JOIN drzi_predmet ON student_u_grupi.grupa = drzi_predmet.grupa AND student_u_grupi.id_studenta = '$idstudenta' AND drzi_predmet.id_predmeta = '$idpredmeta' AND drzi_predmet.id_semestra = '$idsemestra'
                  JOIN nastavnik ON drzi_predmet.id_nastavnika = nastavnik.id_nastavnika";
 //       $stmt = $conn->prepare($query);
 //       $stmt->bind_param('sss', $_SESSION["id_studenta"], $_POST["id_predmeta"], $_SESSION["id_semestra"]);
 //       $stmt->execute();
 //       $result = $stmt->get_result();
		$result = $conn->query($query);
		
		if(isset($_GET["ponovac"])){
				// ponovac
			$query = "SELECT nastavnik.ime, nastavnik.prezime, nastavnik.tip, drzi_predmet.id
                  FROM student_ponovac 
                  JOIN drzi_predmet ON student_ponovac.id_drzi_predmet = drzi_predmet.id AND student_ponovac.id_studenta = '$idstudenta' AND drzi_predmet.id_predmeta = '$idpredmeta' AND drzi_predmet.id_semestra = '$idsemestra' AND student_ponovac.id_semestra = '$idsemestra'
                  JOIN nastavnik ON drzi_predmet.id_nastavnika = nastavnik.id_nastavnika";	
			$result = $conn->query($query);	
				
		}
*/
	
	
	$idstudenta = $_SESSION["id_studenta"];
	$idpredmeta = $_GET["id_predmeta"];
	$idsemestra =  $_SESSION["id_semestra"];
        $query = "SELECT distinct nastavnik.id_nastavnika, nastavnik.ime, nastavnik.prezime, nastavnik.tip, drzi_predmet.id
                  FROM nastavnik LEFT JOIN drzi_predmet ON drzi_predmet.id_nastavnika = nastavnik.id_nastavnika WHERE drzi_predmet.id_predmeta = '$idpredmeta' AND 				    drzi_predmet.id_semestra = '$idsemestra' group by nastavnik.id_nastavnika" ;
	$result = $conn->query($query);	

	echo "<tr><td><br><br>Ocenite samo nastavnike koji su vam držali nastavu u prethodnom semestru</td></tr>";
		
        while ($row = $result->fetch_assoc()) {
	    		

		// selektovati drzi predmet za datog nastavnika
	    $idnastavnika = $row["id_nastavnika"];
	    $ime =  $row["ime"];
	    $prezime = $row["prezime"];
	    $tip = $row["tip"]; 
	
            ?>
            <tr>
                <form method="GET" action="questions.php">
                    <td>
                        <input type="hidden" value="nastavnik" name="tip_pitanja" />
                        <input type="hidden" value="<?php echo $row["id"]; ?>" name="id_drzi_predmet" />
                        <input type="hidden" value="<?php echo $ime ." ".$prezime; ?>" name="nastavnik" />
                        <input type="hidden" value="<?php echo $idpredmeta; ?>" name="id_predmeta" />
                        <input type="hidden" value="<?php echo $_GET["naziv_predmeta"]; ?>" name="naziv_predmeta" />
                        <input type="submit" class="btn btn-warning" value="<?php echo $ime." ".$prezime." - ".$tip; ?>">
					
                    </td>
                </form>
            </tr>
            <?php
        }
        ?>
    </table>
    <br>

    <form method="GET" action="survey.php">
        <input type="submit" class="btn btn-basic" value="Vrati se na početak">
    </form>

</body>
</html>
