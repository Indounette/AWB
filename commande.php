<?php
require_once "config.php";
    // Retrieve the data from the database and populate the array of options
    $resultmodele = $connection->query("CALL show_modele_gab()");
    $modele_options = array();

    if ($resultmodele->num_rows > 0) {
        while ($row = $resultmodele->fetch_assoc()) {
            $modele_options[] = $row['nom_modele'];
        }
    }
    // Free the result after executing the stored procedure
    $connection->next_result();
    if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_POST["submit"])) {
            if (empty($_POST["Bon_commande"]) || empty($_POST["Année_adjucation"]) || empty($_POST["Date_commande"]) || empty($_POST["Date_de_livraison"])) {
                echo "Error: Please fill in all required fields.";
            } else {
    // Get the value of "Bon commande" from the form
    $bon_commande = $_POST["Bon_commande"];
    $date_contrat = date('Y-m-d', strtotime($_POST["Date_contrat"]));
    $annee_adjucation = date('Y-m-d', strtotime($_POST["Année_adjucation"]));
    $date_commande = date('Y-m-d', strtotime($_POST["Date_commande"]));
    $nature_commande = $_POST["nature_commande"];
    $modele = $_POST["modele"];
    $quantite = $_POST["quantite"];
    $commentaire = $_POST["commentaire"];
    $taux = $_POST["taux"];
    $rounded_taux = number_format($taux, 2, '.', ''); 
    $date_livraison = date('Y-m-d', strtotime($_POST["Date_de_livraison"]));
    $date_achat = date('Y-m-d', strtotime($_POST["Date_achat"]));
    $periode_garantie_hard = $_POST["Periode_garantie_hard"];
    $periode_garantie_soft = $_POST["Periode_garantie_soft"];

     // Check if any of the fields contain empty strings
     $emptyFields = array($bon_commande, $annee_adjucation, $date_commande, $date_livraison, $date_achat);
     if (in_array("", $emptyFields, true)) {
         echo "Error: Please fill in all required fields.";
     } else {
    $querycheck = "CALL search_commande('$bon_commande')";    
    if ($querycheck = 1) { // if exists 
        $queryform = "CALL update_commande('$bon_commande', '$date_contrat', '$annee_adjucation', '$date_commande', '$nature_commande', '$modele', '$quantite', '$commentaire', '$rounded_taux', '$date_livraison', '$date_achat', '$periode_garantie_hard', '$periode_garantie_soft')";
    }    
    else {
        $queryform = "CALL create_commande('$bon_commande', '$date_contrat', '$annee_adjucation', '$date_commande', '$nature_commande', '$modele', '$quantite', '$commentaire', '$rounded_taux', '$date_livraison', '$date_achat', '$periode_garantie_hard', '$periode_garantie_soft')";}
    if ($connection->query($queryform) === TRUE) {
        // Data insertion successful
       header("Location: index.php");
        exit;
    } else {
        // Data insertion failed
        echo "Error: " . $sql . "<br>" . $connection->error;
    } }}

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
   <style>
        /* W3.CSS styles for the sidebar */
        .w3-sidebar {
            width: 0;
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            height: 100%;
            overflow-y: auto;
            background-color: #e78f51;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            transition: 0.5s;
        }

        /* W3.CSS styles for the sidebar content */
        .w3-sidebar-content {
            padding: 16px;
        }

        /* Style for the Close button */
        .w3-sidebar-close {
            font-size: 16px;
            font-weight: bold;
            background-color: #8d7575;
            border: none; /* Remove the border */
            padding: 0px 12px; /* Adjust padding */
            border-radius: 4px; /* Add some border radius for rounded corners */
            cursor: pointer; /* Show a pointer cursor on hover */
            margin-left: 290px;
        }

        /* Style for links in the sidebar */
        .w3-sidebar a {
            display: block;
            padding: 8px 16px;
            text-decoration: none;
            color: #333;
            margin-top: 10px;
        }

        /* Hover effect for links */
        .w3-sidebar a:hover {
            background-color: #ddd;
        }

        /* Adjusted styles based on JavaScript functions */
        #main {
            transition: 0.5s;
            margin-left: 0;
        }

        #openNav {
            display: inline-block;
        }

        /* Adjusted styles for the sidebar button */
        #openNav {
            display: inline-block;
            float: right; /* Float the button to the right */
            margin-right: 100px; /* Add some margin for spacing */
            margin-top: 50px;
            background-color: transparent; /* Set background color to transparent */
            font-size: 22px; /* Increase the font size */
            color: #9f293e !important;
            text-shadow: 0 0 7px #ffe3c7;
        }
    </style>

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
    .dropdown-content a:nth-child(2),
    .dropdown-content a:nth-child(3) {
        display: none;
    }
    .chart-article {
        margin-top: 12px; /* Adjust the margin as needed */
    }
    /* Show the child links on hover of the parent link or its dropdown */
    .dropdown:hover .dropdown-content a:nth-child(1),
    .dropdown-content:hover + .dropdown .dropdown-content a:nth-child(1),
    .dropdown:hover .dropdown-content a:nth-child(2),
    .dropdown-content:hover + .dropdown .dropdown-content a:nth-child(2),
    .dropdown:hover .dropdown-content a:nth-child(3),
    .dropdown-content:hover + .dropdown .dropdown-content a:nth-child(3) {
        display: block;
    }
	
    /* Style for the dropdown content */
    .dropdown-content {
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        background-color: #fff0e0;
		z-index: 1; /* Ensure the dropdown is above other content */
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
						<a href="acommande.php"><b>Affichage</b></a>
					</div>
				</div>
				<div class="dropdown" style="float: left; display: inline; width: 20%;">
					<a href="javascript:void(0);" class="dropbtn"><b>Site</b></a>
					<div class="dropdown-content">
						<a href="Site.php"><b>Nouvelle</b></a>
						<a href="affectation.php"><b>Affectation</b></a>
						<a href="asite.php"><b>Affichage</b></a>
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
						<a href="agab.php"><b>Affichage</b></a>
						<a href="historique_gab.php"><b>Historique</b></a>
					</div>
				</div>
				<div class="dropdown" style="float: left; display: inline; width: 20%;">
					<a href="javascript:void(0);" class="dropbtn"><b>Paramétrage</b></a>
					<div class="dropdown-content">
						<a href="stock.php"><b>Stock</b></a>
						<a href="suspendu.php"><b>Suspendu</b></a>
					</div>
				</div>
			</nav>
            <!-- Sidebar -->
            <div class="w3-sidebar w3-bar-block w3-card w3-animate-right" style="display: none;z-index: 2;" id="mySidebar">
                <div class="w3-sidebar-content">
                    <button class="w3-bar-item w3-button w3-large w3-sidebar-close" onclick="w3_close()">&times;</button>
                    <a href="modele_gab.php" style ="margin-top: 25px"><b>Modèle GAB</b></a>
					<a href="fournisseur.php"><b>Fournisseur</b></a>
                </div>
            </div>
            <div id="main">
                        <div class="w3">
                            <button id="openNav" class="w3-button w3 w3-xlarge" onclick="w3_open()">&#9776;</button>
                        </div>
                    </div>
                </div>
         <script>
            function w3_open() {
            document.getElementById("main").style.marginLeft = "25%";
            document.getElementById("mySidebar").style.width = "25%";
            document.getElementById("mySidebar").style.display = "block";
            document.getElementById("openNav").style.display = 'none';
            }
            function w3_close() {
            document.getElementById("main").style.marginLeft = "0%";
            document.getElementById("mySidebar").style.display = "none";
            document.getElementById("openNav").style.display = "inline-block";
            }
            </script>
		</section>
        
    <div class="page-wrapper bg-orange p-t-70 p-b-100 font-robo">
        <div class="wrapper wrapper--w960">
        <sectio id="two" class="wrapper style1 special" style="display: flex; justify-content: space-between; flex-wrap: wrap; padding-left: 100px;padding-right: 100px;">
            <div class="card card-2">
                <h2 class="title" style="text-align: center;">Formulaire Commande</h2>
                    <form method="POST">
                        <div class="row row-space" style="margin-bottom: 25px; ">
                            <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Bon commande" name="Bon_commande" value="<?php echo isset($_GET['edit']) ? htmlspecialchars($_GET['edit']) : ''; ?>" required>
							<?php
// Check if 'edit' parameter is set in the URL
if (isset($_GET['edit'])) {
    $editValue = $_GET['edit'];
    
    // Call the stored procedure to populate other fields
    $populateResult = $connection->query("CALL populate_commande('$editValue')");
    
    if ($populateResult && $populateResult->num_rows > 0) {
        $data = $populateResult->fetch_assoc();
        // Populate other input fields using the returned data
		$date_contrat = $data['date_contrat'];
		$annee_adjucation = $data['annee_adjucation'];
        $date_commande = $data['date_commande'];
        $nature_commande = $data['nature_commande'];
        $modele = $data['modele'];
        $quantite = $data['quantite'];
        $commentaire = $data['commentaire'];
        $taux_maintenance = $data['taux_maintenance'];
        $date_livraison = $data['date_livraison'];
        $date_achat = $data['date_achat'];
        $periode_garantie_hard = $data['periode_garantie_hard'];
        $periode_garantie_soft = $data['periode_garantie_soft'];
    }
} 
$connection->close(); ?></div>
                            <div class="col-2">
                            <input class="input--style-2" type="text" id="datepicker1" placeholder="Date de contrat" name="Date_contrat" data-date-format="dd/mm/yyyy" value="<?php echo isset($date_contrat) ? htmlspecialchars($date_contrat) : ''; ?>">
                        </div>
                        </div>
                        <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                                <!--<div class="input-group">-->
                                    <!--<input class="input--style-2 js-datepicker" type="text" placeholder="Année adjucation" name="Année_adjucation">-->
                                    <input class="input--style-2" type="text" id="datepicker2" placeholder="Année adjucation" name="Année_adjucation" data-date-format="dd/mm/yyyy" value="<?php echo isset($annee_adjucation) ? htmlspecialchars($annee_adjucation) : ''; ?>">
                                <!--</div>-->
                            </div>
                            <div class="col-2">
                            <div class="input-group">
                                <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="modele" class="js-select2">
                                        <option disabled="disabled" selected="selected">Modele de GAB</option>
                                        <?php
                                        // Loop through the array of options and add each one to the dropdown list
                                        foreach ($modele_options as $option) {
                                            // Check if $modele variable is set and compare it with the current option
                                            $isSelected = isset($modele) && $modele === $option ? 'selected' : '';
                                            echo '<option value="' . $option . '" ' . $isSelected . '>' . $option . '</option>';
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
                                    <div class="input-group">
                                       <!-- <input class="input--style-2 js-datepicker" type="text" placeholder="Date de commande" name="Date_de_commande">-->
									   <input class="input--style-2" type="text" id="datepicker3" placeholder="Date de commande" name="Date_commande" data-date-format="dd/mm/yyyy" value="<?php echo isset($date_commande) ? htmlspecialchars($date_commande) : ''; ?>">
                                    </div>
                            </div>
                            <div class="col-2">
                                    <div class="input-group">
									<input class="input--style-2" type="text" id="datepicker4" placeholder="Date de livraison" name="Date_de_livraison" data-date-format="dd/mm/yyyy" value="<?php echo isset($date_livraison) ? htmlspecialchars($date_livraison) : ''; ?>">
                                    </div>
                            </div>
                        </div>
                        <div class="row row-space" style="margin-bottom: 25px;">
                        <div class="col-2">
						<input class="input--style-2" type="text" id="datepicker5" placeholder="Date d'achat" name="Date_achat" data-date-format="dd/mm/yyyy" value="<?php echo isset($date_achat) ? htmlspecialchars($date_achat) : ''; ?>">
                            </div>
							<div class="col-2">
                            <div class="input-group">
							<div class="rs-select2 js-select-simple select--no-search">
								<select name="nature_commande">
									<option disabled="disabled" selected="selected">Nature de commande</option>
									<option <?php if (isset($nature_commande) && $nature_commande === 'Nouvelle') echo 'selected'; ?>>Nouvelle</option>
									<option <?php if (isset($nature_commande) && $nature_commande === 'Remplacement') echo 'selected'; ?>>Remplacement</option>
								</select>
								<div class="select-dropdown"></div>
							</div></div>
						</div>
                        </div>
                        <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Quantite" name="quantite" value="<?php echo isset($quantite) ? htmlspecialchars($quantite) : ''; ?>">
                            </div>
                            <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Taux de maintenance" name="taux" value="<?php echo isset($taux_maintenance) ? htmlspecialchars($taux_maintenance) : ''; ?>">
                            </div>
                        </div>
                        <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Periode de garantie hard" name="Periode_garantie_hard" value="<?php echo isset($periode_garantie_hard) ? htmlspecialchars($periode_garantie_hard) : ''; ?>">
                            </div>
                            <div class="col-2">   
                            <input class="input--style-2" type="text" placeholder="Periode de garantie soft" name="Periode_garantie_soft" value="<?php echo isset($periode_garantie_soft) ? htmlspecialchars($periode_garantie_soft) : ''; ?>">
                            </div>
                        </div>
                        <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Commentaire" name="commentaire" value="<?php echo isset($commentaire) ? htmlspecialchars($commentaire) : ''; ?>">
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
                        </div>
                        </div>
                    </form>
                </section>
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
    $(document).ready(function() {
        $('.js-select2').select2();
    });
</script>

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
