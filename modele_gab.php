<?php
require_once "config.php";
    // Retrieve the data from the database and populate the array of options
    $resultfournisseur = $connection->query("CALL show_fournisseur()");
    $fournisseur_options = array();

    if ($resultfournisseur->num_rows > 0) {
        while ($row = $resultfournisseur->fetch_assoc()) {
            $fournisseur_options[] = $row['reference_fournisseur'];
        }
    }
    // Free the result after executing the stored procedure
    $connection->next_result();

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_POST["submit"])) {
            if (empty($_POST["Type_gab"]) || empty($_POST["Nom_modele"]) ||  empty($_POST["Fournisseur"]) ||  empty($_POST["Prix_unitaire"]) ||  empty($_POST["Door"]) ||  empty($_POST["Fonction"]) ||  empty($_POST["Site"])) {
                echo "Error: Please fill in all the required fields.";
            } else {
    // Get the value of gab attributes from the form
        $type_gab = $_POST["Type_gab"];
        $nom_modele = $_POST["Nom_modele"];
        $fournisseur = $_POST["Fournisseur"];
        $prix_unitaire = $_POST["Prix_unitaire"];
        $door = $_POST["Door"];
        $fonction = $_POST["Fonction"];
        $site = $_POST["Site"];
     // Check if any of the fields contain empty strings
     $emptyFields = array($type_gab, $nom_modele, $fournisseur, $prix_unitaire);
     if (in_array("", $emptyFields, true)) {
         echo "Error: Please fill in all required fields.";
     } else {
    $queryform = "CALL create_modele_gab('$nom_modele', '$type_gab', '$fournisseur',  '$prix_unitaire', '$door', '$fonction', '$site')";
    if ($connection->query($queryform) === TRUE) {
        // Data insertion successful
        header("Location: index.php");
        exit;
    } else {
        // Data insertion failed
        echo "Error: " . $sql . "<br>" . $connection->error;
    } }}
    // Close the database connection
    $connection->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">

    <!-- Title Page-->
    <title>Application</title>

    <!-- Icons font CSS-->
    <link href="assets/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="assets/css/select2.min.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="assets/css/mainform.css" rel="stylesheet" media="all">
    <link rel="stylesheet" href="assets/css/maintest.css" />

      <!-- Add Pikaday CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">

      <!-- Add Pikaday JS -->
  <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>

   <!-- Add XLSX library -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

  <script src="app.js"></script>
</head>
<body>
    		<!-- Header -->
			<header id="header">
				<div class="inner">
					<a href="index.php" class="logo"><img src="images/logo.png"></a>
                </div>
			</header>
            <style>
            /* Hide the child links initially */
            .dropdown-content a:nth-child(1),
            .dropdown-content a:nth-child(2) {
                display: none;
            }
            
            /* Show the child links on hover of the parent link or its dropdown */
            .dropdown:hover .dropdown-content a:nth-child(1),
            .dropdown-content:hover + .dropdown .dropdown-content a:nth-child(1),
            .dropdown:hover .dropdown-content a:nth-child(2),
            .dropdown-content:hover + .dropdown .dropdown-content a:nth-child(2) {
                display: block;
            }
            /* Style for the dropdown content */
            .dropdown-content {
                box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
                background-color: #fff0e0;
            }
        </style>
            <section id="banner" style="padding-top: 130px; padding-bottom: 100px;">
			<h1><b>Gestionnaire De GAB</b></h1>
			<nav id="nav" style="float: left;display: inline; width: 990px;height: 32px;">
				<div class="dropdown" style="float: left; display: inline; width: 20%;">
					<a href="javascript:void(0);" class="dropbtn"><b>Commande GAB</b></a>
					<div class="dropdown-content">
						<a href="commande.php"><b>Nouvelle</b></a>
						<a href="affichagecommande.php"><b>Affichage</b></a>
					</div>
				</div>
				<div class="dropdown" style="float: left; display: inline; width: 20%;">
					<a href="javascript:void(0);" class="dropbtn"><b>Agence</b></a>
					<div class="dropdown-content">
						<a href="agence.php"><b>Nouvelle</b></a>
						<a href="afficheragence.php"><b>Affichage</b></a>
					</div>
				</div>
				<div class="dropdown" style="float: left; display: inline; width: 20%;">
					<a href="javascript:void(0);" class="dropbtn"><b>Pièce de rechange</b></a>
					<div class="dropdown-content">
						<a href="historique_piece.php"><b>Réparation</b></a>
						<a href="coutTotal.php"><b>Coût total</b></a>
					</div>
				</div>
				<div class="dropdown" style="float: left; display: inline; width: 20%;">
					<a href="javascript:void(0);" class="dropbtn"><b>GAB</b></a>
					<div class="dropdown-content">
						<a href="gab.php"><b>Nouveau</b></a>
						<a href="affichergab.php"><b>Affichage</b></a>
					</div>
				</div>
				<div class="dropdown" style="float: left; display: inline; width: 20%;">
					<a href="javascript:void(0);" class="dropbtn"><b>Paramétrage</b></a>
					<div class="dropdown-content">
						<a href="modele_gab.php"><b>Modèle GAB</b></a>
						<a href="fournisseur.php"><b>Fournisseurs</b></a>
					</div>
				</div>
			</nav>
		</section>
				<a href="#navPanel" class="navPanelToggle"><span class="fa fa-bars"></span></a>
			</section>
    <div class="page-wrapper bg-orange p-t-70 p-b-100 font-robo">
        <div class="wrapper wrapper--w960">
        <sectio id="two" class="wrapper style1 special" style="display: flex; justify-content: space-between; flex-wrap: wrap; padding-left: 100px;padding-right: 100px;">
            <div class="card card-2">
                <h2 class="title" style="text-align: center;">Formulaire Modele De GAB</h2>
                    <form method="POST">
                        <div class="row row-space" style="
                        margin-bottom: 25px; ">
                            <div class="col-2">
                                <input class="input--style-2" type="text" placeholder="Nom modele" name="Nom_modele" required>
                                </div>
                            <div class="col-2">
                            <div class="input-group">
                                <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="Type_gab">
                                            <option disabled="disabled" selected="selected">Type GAB</option>
                                            <option>LSB</option>
                                            <option>DAM</option>
                                            <option>CASHLESS</option>
                                            <option>AC</option>
                                            <option>CS</option>
                                            <option>DR</option>
                                        </select>
                                    <div class="select-dropdown"></div>
                                </div>
                                 </div>
                        </div>
                        </div>
                        <div class="row row-space" style="margin-bottom: 25px; ">
                            <div class="col-2">
                                <input class="input--style-2" type="text" placeholder="Prix unitaire" name="Prix_unitaire" required>
                                </div>
                            <div class="col-2">
                            <div class="input-group">
                                    <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="Fournisseur">
                                        <option disabled="disabled" selected="selected">Fournisseur</option>
                                        <?php
                                        // Loop through the array of options and add each one to the dropdown list
                                        foreach ($fournisseur_options as $option) {
                                            echo '<option value="' . $option . '">' . $option . '</option>';
                                        }
                                        ?>
                                    </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                        </div>
                        </div>
                        <div class="row row-space" style="
                        margin-bottom: 25px; ">
                            <div class="col-2">
                            <div class="input-group">
                                <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="Door">
                                            <option disabled="disabled" selected="selected">Door</option>
                                            <option>Indoor</option>
                                            <option>Outdoor</option>
                                        </select>
                                    <div class="select-dropdown"></div>
                                </div>
                                 </div>
                                </div>
                            <div class="col-2">
                            <div class="input-group">
                                <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="Fonction">
                                            <option disabled="disabled" selected="selected">Fonction</option>
                                            <option>Monofonction</option>
                                            <option>Multifonction</option>
                                        </select>
                                    <div class="select-dropdown"></div>
                                </div>
                                 </div>
                        </div>
                        </div>
                        <div class="row row-space" style="margin-bottom: 25px;">
                        <div class="col-2">
                            <div class="input-group">
                                <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="Site">
                                            <option disabled="disabled" selected="selected">Site</option>
                                            <option>In site</option>
                                            <option>Hors site</option>
                                        </select>
                                    <div class="select-dropdown"></div>
                                </div>
                                 </div>
                        </div>
                        <div class="col-2">
                        <div class="p-t-30"style="padding-left: 18px;">
                            <button class="btn btn--radius btn--orange" type="submit">Ajouter \ Modifier</button>
                            <!-- Add this input element to handle the file upload -->
                            <input type="file" id="fileInput" accept=".xls, .xlsx, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.google-apps.spreadsheet" style="display:none">

                            <!-- Add a button to trigger the file selection -->
                            <button onclick="chooseFile()" class="btn btn--radius btn--orange">Excel</button>

                            <!-- Add a div to display the response from upload.php -->
                            <div id="result"></div>
                        </div>
                        </div></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <!-- Jquery JS-->
    <script src="assets/js/jquery.min1.js"></script>
    <!-- Vendor JS-->
    <script src="assets/js/select2.min.js"></script>
    <script src="assets/js/moment.min.js"></script>

    <!-- Main JS-->
    <script src="assets/js/global.js"></script>

    <script>
 function chooseFile() {
    document.getElementById("fileInput").click();
  }

  // Add the event listener for the "Excel" button click
  document.getElementById("excelButton").addEventListener("click", function () {
    // File input element
    var fileInput = document.getElementById("fileInput");

    // Check if a file is selected
    if (fileInput.files.length > 0) {
      var file = fileInput.files[0];
      var formData = new FormData();
      formData.append("fileInput", file);

      fetch("upload.php", {
        method: "POST",
        body: formData,
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then(function (response) {
          if (response.ok) {
            return response.text();
          } else {
            throw new Error("Network response was not ok.");
          }
        })
        .then(function (responseText) {
          document.getElementById("result").innerHTML = responseText;
        })
        .catch(function (error) {
          document.getElementById("result").innerHTML = "Error: " + error.message;
        });
    } else {
      document.getElementById("result").innerHTML = "Error: No file selected.";
    }
  });
 </script>
 <?php
// Check if a file was uploaded and process it
if (isset($_FILES['fileInput']) && $_FILES['fileInput']['error'] === UPLOAD_ERR_OK) {
    // Get the temporary file path of the uploaded file
    $tmpFilePath = $_FILES['fileInput']['tmp_name'];

    // Process the file as needed
    // ...

    echo "File uploaded successfully.";
} else {
    echo "Error: No file received or file upload failed.";
}
?>

</body>

</html>
