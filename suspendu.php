<?php
require_once "config.php";
    // Retrieve the data from the database and populate the array of options
    $resultSerial = $connection->query("CALL show_g_serial_suspendu()");
    $serial_options = array();

    if ($resultSerial->num_rows > 0) {
        while ($row = $resultSerial->fetch_assoc()) {
            $serial_options[] = $row['g_serial'];
        }
    }
    // Free the result after executing the stored procedure
    $connection->next_result();

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
            if (empty($_POST["G_serial"] || $_POST["Motif_suspension"] )) {
                echo "Error: Please fill in all the required fields.";
            } else {
    // Get the value of gab attributes from the form
    $g_serial = $_POST["G_serial"];
    $motif_suspension = $_POST["Motif_suspension"];
    $date_debut = $_POST["DateDebut"];
    $date_fin = $_POST["DateFin"];
    $num_devis = $_POST["NumDevis"];
    $observation = $_POST["Observation"];

    // Handle single quote issue for specific option value
$motif_suspension = str_replace("infiltration d'eau", "infiltration d''eau", $motif_suspension);

     // Check if any of the fields contain empty strings
     $emptyFields = array($g_serial,$motif_suspension);
     if (in_array("", $emptyFields, true)) {
         echo "Error: Please fill in all required fields.";
     } else {
        $querycheck = "CALL show_motif_suspendu('$g_serial')";
        if ($querycheck = 1){
            $queryform = "CALL update_motif_suspendu('$g_serial', '$motif_suspension', '$date_debut', '$date_fin', '$num_devis', '$observation')";
        }
        else{
    $queryform = "CALL motif_suspendu('$g_serial', '$motif_suspension', '$date_debut', '$date_fin', '$num_devis', '$observation')";}
    if ($connection->query($queryform) === TRUE) {
        // Data insertion successful
       header("Location: suspendu.php");
        exit;
    } else {
        // Data insertion failed
        echo "Error: " . $sql . "<br>" . $connection->error;
    } }}   
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['G_serial'])) {
    $g_serial = $_GET['G_serial'];
    
    // Call the search procedure
    $query = "CALL suspendu_by_g_serial('$g_serial')";
    $result = $connection->query($query);
    
    $search_results = array();
    
    while ($data = mysqli_fetch_array($result)) {
        $g_serial = $data['g_serial'];
        $reference_fournisseur = $data['reference_fournisseur'];
        $motif_suspension = $data['motif_suspension'];
        $date_debut = $data['date_debut'];
        $date_fin = $data['date_fin'];
        $num_devis = $data['num_devis'];
        $observation = $data['observation'];
        
        $search_results[] = array(
            "g_serial" => $g_serial,
            "reference_fournisseur" => $reference_fournisseur,
            "motif_suspension" => $motif_suspension,
            "date_debut" => $date_debut,
            "date_fin" => $date_fin,
            "num_devis" => $num_devis,
            "observation" => $observation
        );
    }
    // Free the result set
    $result->free();

    // Return search results as JSON
    header('Content-Type: application/json');
    echo json_encode($search_results);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['NumDevis'])) {
    $num_devis = $_GET['NumDevis'];
    
    // Call the search procedure
    $query = "CALL suspendu_by_num_devis('$num_devis')";
    $result = $connection->query($query);
    
    $search_results = array();
    
    while ($data = mysqli_fetch_array($result)) {
        $g_serial = $data['g_serial'];
        $reference_fournisseur = $data['reference_fournisseur'];
        $motif_suspension = $data['motif_suspension'];
        $date_debut = $data['date_debut'];
        $date_fin = $data['date_fin'];
        $num_devis = $data['num_devis'];
        $observation = $data['observation'];
        
        $search_results[] = array(
            "g_serial" => $g_serial,
            "reference_fournisseur" => $reference_fournisseur,
            "motif_suspension" => $motif_suspension,
            "date_debut" => $date_debut,
            "date_fin" => $date_fin,
            "num_devis" => $num_devis,
            "observation" => $observation
        );
    }
    // Free the result set
    $result->free();

    // Return search results as JSON
    header('Content-Type: application/json');
    echo json_encode($search_results);
    exit;
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
            <div class="card card-2" style="margin-bottom: 25px;">
                <h2 class="title" style="text-align: center;">Formulaire Suspension</h2>
                    <form method="POST">
                        <div class="row row-space" style="margin-bottom: 25px; ">
                        <div class="col-2">
                        <div class="input-group">
                        <div class="rs-select2 js-select-simple select--no-search">
                        <select name="G_serial" class="js-select2">
                            <option disabled="disabled" selected="selected">G Serial</option>
                            <?php
                            // Loop through the array of options and add each one to the dropdown list
                            foreach ($serial_options as $option) {
                                // Check if $edit value matches the current option
                                $isSelected = isset($_GET['edit']) && $_GET['edit'] === $option ? 'selected' : '';
                                echo '<option value="' . $option . '" ' . $isSelected . '>' . $option . '</option>';
                            }
                            ?>
                                </select>
                                <div class="select-dropdown"></div>
                                </div>
                                </div>
                                <?php
                        if (isset($_GET['edit'])) {
                            $editValue = $_GET['edit'];

                            // Call the stored procedure to populate other fields
                            $populateResult = $connection->query("CALL suspendu_by_g_serial('$editValue')");

                            if ($populateResult && $populateResult->num_rows > 0) {
                                $data = $populateResult->fetch_assoc();
                                // Populate other input fields using the returned data
                                $reference_fournisseur = $data['reference_fournisseur'];
                                $motif_suspension = $data['motif_suspension'];
                                $date_debut = $data['date_debut'];
                                $date_fin = $data['date_fin'];
                                $num_devis = $data['num_devis'];
                                $observation = $data['observation'];
                            }
                        }
                        ?>
                        </div>
                        <div class="col-2">
                        <div class="input-group">
                            <div class="rs-select2 js-select-simple select--no-search">
                                <select name="Motif_suspension">
                                    <option disabled="disabled" selected="selected">Motif Suspension</option>
                                    <option value="infiltration d'eau" <?php if (isset($motif_suspension) && $motif_suspension === 'infiltration d\'eau') echo 'selected'; ?>>Infiltration d'eau</option>
                                    <option value="mauvaise manipulation" <?php if (isset($motif_suspension) && $motif_suspension === 'mauvaise manipulation') echo 'selected'; ?>>Mauvaise manipulation</option>
                                    <option value="réaménagement" <?php if (isset($motif_suspension) && $motif_suspension === 'réaménagement') echo 'selected'; ?>>Réaménagement</option>
                                    <option value="vandalisme" <?php if (isset($motif_suspension) && $motif_suspension === 'vandalisme') echo 'selected'; ?>>Vandalisme</option>
                                </select>
                                <div class="select-dropdown"></div>
                            </div>
                        </div>
                       </div>
                       </div>
                       <div class="row row-space" style="margin-bottom: 25px;">
                        <div class="col-2">
                            <div class="input-group">
                                <input class="input--style-2" type="text" id="datepicker1" placeholder="Date debut" name="DateDebut" value="<?php echo isset($date_debut) ? htmlspecialchars($date_debut) : ''; ?>" data-date-format="dd/mm/yyyy">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="input-group">
                                <input class="input--style-2" type="text" id="datepicker2" placeholder="Date fin" name="DateFin" value="<?php echo isset($date_fin) ? htmlspecialchars($date_fin) : ''; ?>" data-date-format="dd/mm/yyyy">
                            </div>
                        </div>
                    </div>
                        <div class="row row-space" style="margin-bottom: 25px;">
                        <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Num Devis" name="NumDevis" value="<?php echo isset($num_devis) ? htmlspecialchars($num_devis) : ''; ?>">
                        </div>
                        <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Observation" name="Observation" value="<?php echo isset($observation) ? htmlspecialchars($observation) : ''; ?>">
                        </div></div>
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
                         </div></div></div>
                        </div>
                        <div class="wrapper wrapper--w960">
        <sectio id="two" class="wrapper style1 special" style="display: flex; justify-content: space-between; flex-wrap: wrap; padding-left: 100px;padding-right: 100px;">
            <div class="card card-2">
                <h2 class="title" style="text-align: center;">Suspendu</h2>
                <div class="container my-4">
                <header style="margin-left: 870px">
                <div class="col-2 float-right" style="display: inline-block; width: 30%;">
                    <input class="input--style-2" type="text" placeholder="Code GAB" id="G_serial">
                </div>
                <div class="col-2 float-right" style="display: inline-block; width: 30%;">
                    <input class="input--style-2" type="text" placeholder="Num Devis" id="NumDevis">
                </div>
                <div class="btn btn-search float-right" style="display: inline-block; margin-bottom: 6px; padding: 4px 10px;">
                    <a href="#" style="color: white; text-decoration: none;" id="searchButton">Search</a>
                </div>
            </header>
            <table class="popup-table"style="background-color: #fdf7f0;">
        <thead>
            <tr>
            <th>G Serial</th>
            <th>Reference Fournisseur</th>
            <th>Motif Suspension</th>
            <th>Date Debut</th>
            <th>Date Fin</th>
            <th>Num Devis</th>
            <th>Observation</th>
            <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $resultstock = $connection->query("CALL liste_suspendu()");
         while ($data = mysqli_fetch_array($resultstock)) {
            $g_serial = $data['g_serial'];
            $reference_fournisseur = $data['reference_fournisseur'];
            $motif_suspension = $data['motif_suspension'];
            $date_debut = $data['date_debut'];
            $date_fin = $data['date_fin'];
            $num_devis = $data['num_devis'];
            $observation = $data['observation'];
            ?>
            <tr>
                <td><b><?php echo $g_serial; ?></b></td>
                <td><b><?php echo $reference_fournisseur; ?></b></td>
                <td><b><?php echo $motif_suspension; ?></b></td>
                <td><b><?php echo $date_debut; ?></b></td>
                <td><b><?php echo $date_fin; ?></b></td>
                <td><b><?php echo $num_devis; ?></b></td>
                <td><b><?php echo $observation; ?></b></td>
                <td>
                    <div class="btn btn-edit" style="margin-bottom: 6px; height: 40px">
                        <a href="suspendu.php?edit=<?php echo urlencode($g_serial); ?>" style="color: white; text-decoration: none;">Edit</a>
                    </div>
                </td>
            </tr>
            <?php
        }
        // Free the result set
        $resultstock->free();
        ?>
        </tbody>
        </table>
    </div></div>
                        </div>
                </div>
            </div>
        </div>
        <script>
    const searchButton = document.getElementById("searchButton");

searchButton.addEventListener("click", (event) => {
    event.preventDefault(); // Prevent the default form submission

    const gSerial = document.getElementById("G_serial").value;
    const numDevis = document.getElementById("NumDevis").value;

    if (gSerial) {
        // Call the search procedure for G Serial and fetch results from the server
        fetch("?G_serial=" + gSerial)
            .then(response => response.json())
            .then(data => displayResults(data))
            .catch(error => console.error("Error:", error));
    } else if (numDevis) {
        // Call the search procedure for Num Devis and fetch results from the server
        fetch("?NumDevis=" + numDevis)
            .then(response => response.json())
            .then(data => displayResults(data))
            .catch(error => console.error("Error:", error));
    }
    });
    // This function will handle displaying the search results
    function displayResults(results) {
    // Create a custom popup container
    const popupContainer = document.createElement("div");
    popupContainer.classList.add("popup");

    // Create a close button
    const closeButton = document.createElement("span");
    closeButton.classList.add("popup-close");
    closeButton.textContent = "X";
    closeButton.addEventListener("click", () => {
        popupContainer.remove();
    });

    // Create a table element
    const table = document.createElement("table");
    table.classList.add("popup-table");

    // Create table header row
    const headerRow = document.createElement("tr");
    headerRow.innerHTML = `<th>G Serial</th>
                            <th>Reference Fournisseur</th>
                            <th>Motif Suspension</th>
                            <th>Date Debut</th>
                            <th>Date Fin</th>
                            <th>Num Devis</th>
                            <th>Observation</th>
                            <th>Action</th>`;
    table.appendChild(headerRow);

    // Loop through the results and create table rows
    results.forEach(result => {
        const row = document.createElement("tr");
        row.innerHTML = `<td><b>${result.g_serial}</b></td>
                        <td><b>${result.reference_fournisseur}</b></td>
                        <td><b>${result.motif_suspension}</b></td>
                        <td><b>${result.date_debut}</b></td>
                        <td><b>${result.date_fin}</b></td>
                        <td><b>${result.num_devis}</b></td>
                        <td><b>${result.observation}</b></td>
                        <td>
                            <div class="btn btn-edit" style="margin-bottom: 6px;">
                                <a href="suspendu.php?edit=${encodeURIComponent(result.g_serial)}" style="color: white; text-decoration: none;">Edit</a>
                            </div>
                        </td>`;
        table.appendChild(row);
    });
        // Add close button to the popup container
        popupContainer.appendChild(closeButton);

        // Add table to the popup container
        popupContainer.appendChild(table);

        // Add the popup container to the document body
        document.body.appendChild(popupContainer);
    }
</script>

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
 // Close the database connection
 $connection->close();
?>

</body>

</html>
