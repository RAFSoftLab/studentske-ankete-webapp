<?php
	
    
     /*pocetna stranica ankete, na kojoj se nalazi link ka opštim pitanjima i linkovi ka svakom predmetu*/
     
/*
    echo 'Anketa za neparni semestar školske 2019/2020. godine je zatvorena.';   
    die();
*/		


	
    require_once("database.php");
	
    require_once('settings.php');
    
/*	
	$login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online';
	
  */ 	
    session_start();	
     
    
    //pristup bez prethodnog logovanja
/*	
 
    if (!isset($_SESSION["user_email"]) ) {
        header("Location: ".$login_url);
        die();
    }
   	
    if(!(substr($_SESSION["user_email"],-6)==='raf.rs')){			
		echo '<br>Da biste mogli da popunite anketu morate biti ulogovani preko <b>raf.rs</b> google naloga!';
				

	}


    //logout korisnika
    if (isset($_GET["logout"]) && $_GET["logout"]==1) {
        session_destroy();
        header("Location: index.php");
        die();
    }
    //echo $_SESSION["id_semestra"];
	*/
?>

<!DOCTYPE html>
<html>
<head>
    <title>Anketa</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>      
		function myFunction() {
			alert("Ovom akcijom završavate popunjavanje ankete i nikakve naknadne izmene nisu moguće.");
		}    
    </script>
	<style>
		input{
			margin: 13px 0px;
		}
	</style>
</head>
<body style="margin-left: 2em;">	
    <h1>Dobrodošli na anketu Računarskog fakulteta</h1>
	
    <br>
    <br>
	
	<?php
	
			$_SESSION["user_email"] = 'edrpa8420ri@raf.rs';

			
			$query = "SELECT id_semestra AS id, skolska_godina, tip_semestra FROM semestar where id_semestra = (SELECT MAX(id_semestra) FROM semestar)";
			$result = $conn->query($query);
			if ($row = $result->fetch_assoc()) {
				$_SESSION["skolska_godina"] = $row["skolska_godina"];
				$_SESSION["tip_semestra"] = $row["tip_semestra"];
				$_SESSION["id_semestra"] = $row["id"];				
			}			

			$username = substr($_SESSION["user_email"],0,-7);
			$idsemestra = $_SESSION["id_semestra"];	

 			
			echo '<b>Školska godina:</b> '.$_SESSION["skolska_godina"].' - ';
			echo ($_SESSION["tip_semestra"]=='z' ? 'neparni' : 'parni').' semestar';
			
            //podaci o studentu
            $query = "SELECT s.id_studenta, s.ime, s.prezime, s.smer, s.indeks, s.godinaUpisa FROM student s 
		      WHERE s.username like '$username' order by s.id_studenta asc";
       //     $stmt = $conn->prepare($query);
	   
	    	
      //      echo "username:".$username;
 		
      //      $stmt->bind_param('s', $username);
	
      //      $stmt->execute();
	   
	
       //     $result = $stmt->get_result();

		$result = $conn->query($query);               
		$row = $result->fetch_assoc(); 
	    
 
		$_SESSION["id_studenta"] = $row["id_studenta"];
		$idstudenta = $_SESSION["id_studenta"];		 
       		$ime = 	$row["ime"];	
		$prezime = $row["prezime"];
		$smer = $row["smer"];
		$index = $row["indeks"];
		$godinaUpisa = $row["godinaUpisa"];

/*
		if(is_null($_SESSION["id_studenta"])){
				// promena emaila, trazimo da li počinje sa prvim slovom imena i prezimenom
				$username1 = preg_split("/[\d]+/",$username)[0].'%';  // pocinje sa				
				$query = "SELECT s.id_studenta, s.ime, s.prezime, s.smer, s.indeks, s.godinaUpisa, sg.grupa 
		      FROM student s, student_u_grupi sg 
		      WHERE s.username like '$username1' and sg.id_studenta = s.id_studenta and sg.id_semestra = '$idsemestra'";
			  $result = $conn->query($query);              
		
            $row = $result->fetch_assoc(); 
			$_SESSION["id_studenta"] = $row["id_studenta"];
		}
		$idstudenta = $_SESSION["id_studenta"];
       		$ime = 	$row["ime"];	
		$prezime = $row["prezime"];
		$smer = $row["smer"];
		$index = $row["indeks"];
		$godinaUpisa = $row["godinaUpisa"];
		
		$query = "SELECT grupa FROM student_u_grupi   WHERE id_studenta = '$idstudenta' and id_semestra = '$idsemestra'";		
		$result = $conn->query($query);               		
        $row = $result->fetch_assoc(); 
		$grupa = $row["grupa"];
	*/    
                ?>
                    <div>
                        <b>Ime i prezime:</b> <?php echo $ime. " ".$prezime; ?><br>
						<b>Indeks:</b><?php echo $smer. " ".$index. "/".$godinaUpisa; ?><br>
						<b>Email:</b><?php echo $_SESSION["user_email"]?><br>
						<!-- <b>Grupa:</b></td><td><?php echo $row["grupa"] ?><br><br><br>            -->                                    
				<?php
					// provera da li je anketa zaključena
					$query = "SELECT * FROM zakljucena WHERE id_studenta= '$idstudenta' and id_semestra = '$idsemestra'";
					$result = $conn->query($query);
					if ($row = $result->fetch_assoc()) {
						echo "Pupunili ste anketu za ovaj semestar. Za sva dodatna pitanja poslati email na <em>anketa@raf.rs</em>.";
						unset($_SESSION["user_email"]);    	
						session_destroy();
						die();
						
					}	
					
					
				?>	
				
					<form method="GET" action="questions.php">
							<input type="hidden" value="fakultet" name="tip_pitanja" />
							<input type="submit" class="btn btn-primary" value="Opšta pitanja vezana za nastavu na RAF-u">
					</form>
			</div>
		<br>
		
		<?php /*
			
			if(empty($grupa) || $grupa==""){
				echo "U ovom semestru niste prijavljeni u grupu.<br>";
				echo "Ako želite da učestvujete u anketi za predmete i nastavnike, potrebno je da na email anketa@raf.rs pošaljete svoj nalog za fakultetski email, ime i prezime i grupu sa kojom ste slušali nastavu.";
				die();
			}
*/
		?>
	

	
        <div class="btn-group-vertical">
        <?php
		
	
            //dodavanje predmeta koje student slusa
	  /* 
            $idstudenta = $_SESSION["id_studenta"];	 
		$predmeti = array();		
		$query = "SELECT DISTINCT predmet.id_predmeta, predmet.naziv
				  FROM student_u_grupi                      
				  JOIN drzi_predmet ON student_u_grupi.grupa = drzi_predmet.grupa AND student_u_grupi.id_studenta = '$idstudenta' AND student_u_grupi.id_semestra = '$idsemestra'
				  JOIN predmet ON drzi_predmet.id_predmeta = predmet.id_predmeta AND drzi_predmet.id_semestra = $idsemestra";
         //   $stmt = $conn->prepare($query);
         //   $stmt->bind_param('ss',  $_SESSION["id_studenta"], $_SESSION["id_semestra"]);
         //   $stmt->execute();
         //   $result = $stmt->get_result();
	
	    $result = $conn->query($query);		
		while ($row = $result->fetch_assoc()) {
			$predmeti[$row["id_predmeta"]] = $row["naziv"];			
		}

*/

	// nova verzija - citanje predmeta na osnovu mailliste
		
		echo "Ocenite predmete koje ste slušali u prethodnom semestru: "; 		

		$idstudenta = $_SESSION["id_studenta"];	 
		$predmeti = array();		
		$query = "SELECT p.id_predmeta, p.naziv FROM student_predmet_semestar sps, predmet p, drzi_predmet dp WHERE sps.student_id = '$idstudenta' AND sps.semestar_id = 			'$idsemestra'  AND p.id_predmeta = sps.predmet_id AND dp.id_predmeta = sps.predmet_id AND dp.id_semestra = '$idsemestra'" ;
		
		$result = $conn->query($query);
		while ($row = $result->fetch_assoc()) {
			$predmeti[$row["id_predmeta"]] = $row["naziv"];			
		}

		

		foreach ($predmeti as $id=>$naziv) {
                ?>

				<form method="GET" action="course.php">
					<input type="hidden" value="<?php echo $id; ?>" name="id_predmeta" />
					<input type="hidden" value="<?php echo $naziv; ?>" name="naziv_predmeta" />
					<input type="submit" class="btn btn-success" value="<?php echo $naziv; ?>">

				</form>

                <?php
            }
        
		
		// predmeti koje slusaju kao ponovci
		
		/*
		$query = "SELECT DISTINCT predmet.id_predmeta, predmet.naziv
                      FROM student_ponovac sp 
                      JOIN drzi_predmet ON sp.id_drzi_predmet = drzi_predmet.id 
                      JOIN predmet ON drzi_predmet.id_predmeta = predmet.id_predmeta AND drzi_predmet.id_semestra = '$idsemestra'
					  where sp.id_semestra = '$idsemestra' and sp.id_studenta = '$idstudenta'                       ";
		$result = $conn->query($query);
		$predmeti = array();	
		if($result!=NULL){			
			while ($row = $result->fetch_assoc()) {
				$predmeti[$row["id_predmeta"]] = $row["naziv"];			
			}
		}
		if(sizeof($predmeti)>0){
		
		echo "<br>Predmeti - ponovac";
			
		foreach ($predmeti as $id=>$naziv) {
                ?>

				<form method="GET" action="course.php">
					<input type="hidden" value="<?php echo $id; ?>" name="id_predmeta" />
					<input type="hidden" value="<?php echo $naziv; ?>" name="naziv_predmeta" />
					<input type="hidden" value="ponovac" name="ponovac" />
					<input type="submit" class="btn btn-success" value="<?php echo $naziv; ?>">

				</form>

                <?php
            }
		}
 */       ?>

        </div>
		<br>
		<br>
		 <form method="GET" action="finishAll.php">
			<input type="submit" class="btn btn-primary" value="Zaključi anketu" onclick="myFunction();">
		 </form>
	

</body>
</html>
