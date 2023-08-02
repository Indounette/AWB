<?php
require_once "config.php";
    // Retrieve the data from the database and populate the array of options
    $resultreference_piece = $connection->query("CALL show_reference_piece()");
    $reference_piece_options = array();

    if ($resultreference_piece->num_rows > 0) {
        while ($row = $resultreference_piece->fetch_assoc()) {
            $reference_piece_options[] = $row['reference'];
        }
    }
    // Free the result after executing the stored procedure
    $connection->next_result();

        // Retrieve the data from the database and populate the array of options
        $resultagence = $connection->query("CALL show_agence()");
        $agence_options = array();
    
        if ($resultagence->num_rows > 0) {
            while ($row = $resultagence->fetch_assoc()) {
                $agence_options[] = $row['code_agence'];
            }
        }
        // Free the result after executing the stored procedure
        $connection->next_result();

          // Retrieve the data from the database and populate the array of options
    $resultgab = $connection->query("CALL show_gab()");
    $gab_options = array();

    if ($resultgab->num_rows > 0) {
        while ($row = $resultgab->fetch_assoc()) {
            $gab_options[] = $row['g_serial'];
        }
    }
    // Free the result after executing the stored procedure
    $connection->next_result();

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
            if (empty($_POST["Code_gab"]) || empty($_POST["Quantite"]) ||  empty($_POST["Reference_piece"]) ||  empty($_POST["Technicien"]) ||  empty($_POST["Numero_devis"])) {
                echo "Error: Please fill in all the required fields.";
            } else {
    // Get the value of gab attributes from the form
        $code_gab = $_POST["Code_gab"];
        $date_changement = date('Y-m-d', strtotime($_POST["Date_changement"]));
        $code_agence = $_POST["Code_agence"];
        $motif_changement = $_POST["Motif_changement"];
        $numero_devis = $_POST["Numero_devis"];
        $observation = $_POST["Observation"];
        $quantite = $_POST["Quantite"];
        $reference_piece = $_POST["Reference_piece"];
        $technicien = $_POST["Technicien"];
     // Check if any of the fields contain empty strings
     $emptyFields = array($code_gab, $quantite, $reference_piece, $numero_devis, $technicien);
     if (in_array("", $emptyFields, true)) {
         echo "Error: Please fill in all required fields.";
     } else {
    $queryform = "CALL create_historique_piece('$code_gab', '$reference_piece', '$date_changement', '$motif_changement', '$numero_devis', '$quantite', '$technicien', '$observation')";
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
		<!-- Banner -->
			<section id="banner" style="padding-top: 130px;padding-bottom: 100px;">
				<h1><b>Gestonnaire De GAB</b></h1>
				<nav id="nav">
					<a href="commande.php"><b>Commandes GAB</b></a>
					<a href="agence.php"><b>Agence</b></a>
					<a href="gab.php"><b>GAB</b></a>
					<a href="historique_piece.php"><b>Piece de rechange</b></a>
					<a href="index.html"><b>Paramétrage</b></a>
				</nav>
				<a href="#navPanel" class="navPanelToggle"><span class="fa fa-bars"></span></a>
			</section>
    <div class="page-wrapper bg-orange p-t-70 p-b-100 font-robo">
        <div class="wrapper wrapper--w960">
        <sectio id="two" class="wrapper style1 special" style="display: flex; justify-content: space-between; flex-wrap: wrap; padding-left: 100px;padding-right: 100px;">
            <div class="card card-2">
                <h2 class="title" style="text-align: center;">Formulaire Historique Piece De Rechange</h2>
                    <form method="POST">
                        <div class="row row-space" style="
                        margin-bottom: 25px; ">
                            <div class="col-2">
                            <div class="input-group">
                                    <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="Code_agence" required>
                                        <option disabled="disabled" selected="selected">Code agence</option>
                                        <?php
                                        // Loop through the array of options and add each one to the dropdown list
                                        foreach ($agence_options as $option) {
                                            echo '<option value="' . $option . '">' . $option . '</option>';
                                        }
                                        ?>
                                    </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                            <div class="input-group">
                                    <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="Code_gab" required>
                                        <option disabled="disabled" selected="selected">Code gab</option>
                                        <?php
                                        // Loop through the array of options and add each one to the dropdown list
                                        foreach ($gab_options as $option) {
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
                                <input class="input--style-2" type="text" placeholder="Technicien" name="Technicien" required>
                                </div>
                            <div class="col-2">
                            <div class="input-group">
                                    <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="Reference_piece">
                                        <option disabled="disabled" selected="selected">Reference piece</option>
                                        <?php
                                        // Loop through the array of options and add each one to the dropdown list
                                        foreach ($reference_piece_options as $option) {
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
                                    <input class="input--style-2" type="text" id="datepicker1" placeholder="Date changement" name="Date_changement" data-date-format="dd/mm/yyyy">
                                <!--</div>-->
                            </div>
                            <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Motif de change" name="Motif_changement">
                            </div>
                        </div>
                            <div class="row row-space" style="
                            margin-bottom: 25px; ">
                                <div class="col-2">
                                <input class="input--style-2" type="text" placeholder="numero devis" name="Numero_devis" required>
                                </div>
                                <div class="col-2">
                                <input class="input--style-2" type="text" placeholder="Quantite" name="Quantite">
                            </div>
                            </div>
                            <div class="row row-space" style="
                            margin-bottom: 25px; ">
                                <div class="col-2">
                                <input class="input--style-2" type="text" placeholder="Observation" name="Observation">
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
