<?php
require_once "config.php";
    // Retrieve the data from the database and populate the array of options
    $resulttype_agence = $connection->query("CALL show_type_agence()");
    $type_agence_options = array();

    if ($resulttype_agence->num_rows > 0) {
        while ($row = $resulttype_agence->fetch_assoc()) {
            $type_agence_options[] = $row['type_agence'];
        }
    }
    // Free the result after executing the stored procedure
    $connection->next_result();

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_POST["submit"])) {
            if (empty($_POST["Code_agence"]) || empty($_POST["Adresse"]) ||  empty($_POST["Type_agence"])) {
                echo "Error: Please fill in all the required fields.";
            } else {
    // Get the value of gab attributes from the form
        $libelle = $_POST["Libelle"];
        $date_ouverture = date('Y-m-d', strtotime($_POST["Date_ouverture"]));
        $code_agence = $_POST["Code_agence"];
        $ville = $_POST["Ville"];
        $resp_agence = $_POST["Resp_agence"];
        $tel_agence = $_POST["Tel_agence"];
        $gestionnaire_gab = $_POST["Gestionnaire_gab"];
        $type_agence = $_POST["Type_agence"];
        $mail_agence = $_POST["Mail_agence"];
        $adresse = $_POST["Adresse"];
        $local_clim = $_POST["Local_clim"];
        $local_electric = $_POST["Local_electric"];
        $local_reseau = $_POST["Local_reseau"];
        $local_espace = $_POST["Local_espace"];
        $latitude = $_POST["Latitude"];
        $longitude = $_POST["Longitude"];
     // Check if any of the fields contain empty strings
     $emptyFields = array($libelle, $code_agence, $type_agence, $ville, $resp_agence, $adresse);
     if (in_array("", $emptyFields, true)) {
         echo "Error: Please fill in all required fields.";
     } else {
    $queryform = "CALL create_agence('$code_agence', '$libelle', '$adresse', '$type_agence', '$ville', '$resp_agence', '$gestionnaire_gab', '$date_ouverture', '$mail_agence', '$tel_agence', '$latitude', '$longitude', '$local_electric', '$local_clim', '$local_reseau', '$local_espace')";
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
    <link rel="stylesheet" href="assets/css/main.css" />

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
		<!-- Banner -->
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
                <h2 class="title" style="text-align: center;">Formulaire Agence</h2>
                    <form method="POST">
                        <div class="row row-space" style="
                        margin-bottom: 25px; ">
                            <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Code agence" name="Code_agence" required>
                            </div>
                            <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Libelle" name="Libelle">
                        </div>
                        </div>
                        <div class="row row-space" style="
                        margin-bottom: 25px; ">
                            <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Adresse" name="Adresse">
                            </div>
                            <div class="col-2">
                            <div class="input-group">
                                    <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="Type_agence">
                                        <option disabled="disabled" selected="selected">Type agence</option>
                                        <?php
                                        // Loop through the array of options and add each one to the dropdown list
                                        foreach ($type_agence_options as $option) {
                                            echo '<option value="' . $option . '">' . $option . '</option>';
                                        }
                                        ?>
                                    </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                        </div>
                        </div>
                        <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                                <!--<div class="input-group">-->
                                    <!--<input class="input--style-2 js-datepicker" type="text" placeholder="Année adjucation" name="Date_achat">-->
                                    <input class="input--style-2" type="text" id="datepicker1" placeholder="Date ouverture" name="Date_ouverture" data-date-format="dd/mm/yyyy">
                                <!--</div>-->
                            </div>
                            <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Ville" name="Ville">
                            </div>
                        </div>
                            <div class="row row-space" style="
                            margin-bottom: 25px; ">
                                <div class="col-2">
                                <input class="input--style-2" type="text" placeholder="Responsable agence" name="Resp_agence" required>
                                </div>
                                <div class="col-2">
                                <input class="input--style-2" type="text" placeholder="Gestionnaire GAB" name="Gestionnaire_gab">
                            </div>
                            </div>
                            <div class="row row-space" style="
                            margin-bottom: 25px; ">
                                <div class="col-2">
                                <input class="input--style-2" type="text" placeholder="Mail agence" name="Mail_agence" required>
                                </div>
                                <div class="col-2">
                                <input class="input--style-2" type="text" placeholder="Telephone agence" name="Tel_agence">
                            </div>
                            </div>
                        <div class="row row-space" style="margin-bottom: 25px;">
                        <div class="col-2">
                        <div class="input-group">
                                <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="Local_electric">
                                            <option disabled="disabled" selected="selected">Local électricité</option>
                                            <option>Conforme</option>
                                            <option>Non conforme</option>
                                            <option>Inexistant</option>
                                        </select>
                                    <div class="select-dropdown"></div>
                                </div>
                                 </div>
                            </div>
                            <div class="col-2">
                            <div class="input-group">
                                <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="Local_clim">
                                            <option disabled="disabled" selected="selected">Local clim</option>
                                            <option>Conforme</option>
                                            <option>Non conforme</option>
                                            <option>Inexistant</option>
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
                                    <select name="Local_reseau">
                                            <option disabled="disabled" selected="selected">Local réseau</option>
                                            <option>Conforme</option>
                                            <option>Non conforme</option>
                                            <option>Inexistant</option>
                                        </select>
                                    <div class="select-dropdown"></div>
                                </div>
                                 </div>
                            </div>
                            <div class="col-2">
                                <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="Local_espace">
                                        <option disabled="disabled" selected="selected">Local espace</option>
                                             <option>Conforme</option>
                                            <option>Non conforme</option>
                                            <option>Inexistant</option>
                                    </select>
                                    <div class="select-dropdown"></div>
                                </div>
                        </div>
                            </div>
                     <div class="row row-space" style="
                        margin-bottom: 25px; ">
                            <div class="col-2">
                            <input class="input--style-2option" type="text" placeholder="Latitude" name="Latitude" required>
                            </div>
                            <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Longitude" name="Longitude">
                        </div>
                        </div>
                        <div class="row row-space" style="margin-bottom: 25px;">
                <div class="col-2">
                <div class="p-t-30" style="padding-left: 200px;">
                 <button type="submit" name="submit" class="btn btn--radius btn--orange">Ajouter \ Modifier</button> 
                        </div> </div> 
                        <div class="col-2">
                        <div class="p-t-30" style="padding-left: 200px;">
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
