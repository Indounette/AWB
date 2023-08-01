<?php require 'config.php';
// Enable error reporting for all types of errors
error_reporting(E_ALL);

// Display errors in the browser
ini_set('display_errors', 1); ?>
 <!DOCTYPE html> 
<html lang="en" dir="ltr">
	<head> 
		<meta charset="utf-8">
		<title>Import Excel To MySQL</title>
	</head>
	<body>
		<form class="" action="" method="post" enctype="multipart/form-data">
			<input type="file" name="excel" required value="">
			<button type="submit" name="import">Import</button>
		</form>
		<hr>
		<table border = 1>
			<tr>
				<td>#</td>
				<td>1</td>
				<td>2</td>
				<td>3</td>
				<td>4</td>
				<td>5</td>
				<td>6</td>
				<td>7</td>
				<td>8</td>
				<td>9</td>
				<td>10</td>
				<td>11</td>
			</tr>
			<?php
			$i = 1;
			$rows = $connection->query("SELECT bon_commande, numero_contrat, annee_adjucation, date_commande, nature_commande, modele, quantite, commentaire, taux_maintenance, date_livraison, module FROM commande");
			foreach($rows as $row) :
			?>
			<tr>
				<td> <?php echo $i++; ?> </td>
				<td> <?php echo $row["bon_commande"]; ?> </td>
				<td> <?php echo $row["numero_contrat"]; ?> </td>
				<td> <?php echo date('Y-m-d', strtotime($row["annee_adjucation"])); ?> </td>
				<td> <?php echo	date('Y-m-d', strtotime($row["date_commande"]));?> </td>
				<td> <?php echo	$row["nature_commande"];?> </td>
				<td> <?php echo	$row["modele"];?> </td>
				<td> <?php echo	$row["quantite"];?> </td>
				<td> <?php echo	$row["commentaire"];?> </td>
				<td> <?php echo	$row["taux_maintenance"];?> </td>
				<td> <?php echo	date('Y-m-d', strtotime($row["date_livraison"]));?> </td>
				<td> <?php echo	$row["module"];?> </td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php
		if(isset($_POST["import"])){
			// Check for file upload errors
			if ($_FILES["excel"]["error"] !== UPLOAD_ERR_OK) {
				die("File upload error. Error code: " . $_FILES["excel"]["error"]);
			}
			$fileName = $_FILES["excel"]["name"];
			$fileExtension = explode('.', $fileName);
      $fileExtension = strtolower(end($fileExtension));
			$newFileName = date("Y.m.d") . " - " . date("h.i.sa") . "." . $fileExtension;

			$targetDirectory = "uploads/" . $newFileName;
			move_uploaded_file($_FILES['excel']['tmp_name'], $targetDirectory);

			error_reporting(0);
			ini_set('display_errors', 0);

			require '../excelReader/excel_reader2.php';
			require '../excelReader/SpreadsheetReader.php';

			$reader = new SpreadsheetReader($targetDirectory);
			foreach($reader as $key => $row){
				$bon_commande = $row[1];
				$numero_contrat = $row[2];
				$annee_adjucation = $row[3];
				//$date_commande = $row[4];
				// Check if $row[4] is set and not null before using strtotime
$date_commande = isset($row[4]) ? date('Y-m-d', strtotime($row[4])) : null;
				$nature_commande = $row[5];
				$modele = $row[6];
				$quantite = $row[7];
				$commentaire = $row[8];
				$taux = $row[9];
				//$date_livraison = $row[10];
				// Check if $row[10] is set and not null before using strtotime
$date_livraison = isset($row[10]) ? date('Y-m-d', strtotime($row[10])) : null;
				$module = $row[11];
                var_dump($bon_commande);
				var_dump($numero_contrat);
				var_dump($annee_adjucation);
				var_dump($date_commande);
				var_dump($nature_commande);
				var_dump($modele);
				var_dump($quantite);
				var_dump($commentaire);
				var_dump($taux);
				var_dump($date_livraison);
				var_dump($module);
                error_log("bon_commande: " . $bon_commande);
    error_log("numero_contrat: " . $numero_contrat);
    error_log("annee_adjucation: " . $annee_adjucation);
    error_log("date_commande: " . $date_commande);
    error_log("nature_commande: " . $nature_commande);
    error_log("modele: " . $modele);
    error_log("quantite: " . $quantite);
    error_log("commentaire: " . $commentaire);
    error_log("taux: " . $taux);
    error_log("date_livraison: " . $date_livraison);
    error_log("module: " . $module);
				$connection->query("CALL create_commande('$bon_commande', '$numero_contrat', '$annee_adjucation', '$date_commande', '$nature_commande', '$modele', '$quantite', '$commentaire', '$taux', '$date_livraison', '$module')");
			}

			echo
			"
			<script>
			alert('Succesfully Imported');
			document.location.href = '';
			</script>
			";
		}
		?>
	</body>
</html>
