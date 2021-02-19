<html>
<head>
    <title>Anketa</title>   
</head>
<body style="margin-left: 2em;">	
<?php
	include_once "database.php";
	session_start();	
	//$idstudenta = $_SESSION["id_studenta"]
	//$idsemestra = $_SESSION["id_semestra"]
	
	$query = "INSERT INTO zakljucena (id_studenta, id_semestra) VALUES (?, ?)";
	$stmt = $conn->prepare($query);
	$stmt->bind_param('ss', $_SESSION["id_studenta"], $_SESSION["id_semestra"]);
	$stmt->execute();
	$query = "UPDATE odgovor_nastavnik SET id_studenta=NULL where id_studenta=? AND id_semestra=?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param('ss', $_SESSION["id_studenta"], $_SESSION["id_semestra"]);
	$stmt->execute();
	$query = "UPDATE odgovor_fakultet SET id_studenta=NULL where id_studenta=? AND id_semestra=?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param('ss', $_SESSION["id_studenta"], $_SESSION["id_semestra"]);
	$stmt->execute();
	$query = "UPDATE odgovor_predmet SET id_studenta=NULL where id_studenta=? AND id_semestra=?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param('ss', $_SESSION["id_studenta"], $_SESSION["id_semestra"]);
	$stmt->execute();
	
	unset($_SESSION["user_email"]);    	
	session_destroy();

?>	


Završili ste popunjavanje ankete. <br>
Popunjavanjem ankete doprinosite kvalitetu nastave na Računarskom fakultetu.<br>
Hvala na izdvojenom vremenu!
	
</body>
</html>