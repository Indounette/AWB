<?php
require_once "config.php";
// Include the PHPSpreadsheet autoloader
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['G_serial'])) {
    $g_serial = $_GET['G_serial'];
    
    // Call the search procedure
    $query = "CALL search_historique_gab_byg_serial('$g_serial')";
    $result = $connection->query($query);
    
    $search_results = array();
    
    while ($data = mysqli_fetch_array($result)) {
        $search_results[] = $data;
    }
    // Return search results as JSON
    header('Content-Type: application/json');
    echo json_encode($search_results);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['Statut'])) {
    $statut = $_GET['Statut'];
    
    // Call the search procedure
    $query = "CALL search_historique_gab_bystatut('$statut')";
    $result = $connection->query($query);
    
    $search_results = array();
    
    while ($data = mysqli_fetch_array($result)) {
        $search_results[] = $data;
    }
    // Return search results as JSON
    header('Content-Type: application/json');
    echo json_encode($search_results);
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_POST["extract"])) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $fields = array(
        'gSerial', 'Modele', 'Fournisseur', 'Statut', 'dateLivraison',
        'Motif', 'dateInstallation', 'dateDemarrage', 'dateCloture'
    );

    // Set headers as first row
    $sheet->fromArray($fields, null, 'A1');

    // Auto-size columns based on content
    foreach ($sheet->getColumnIterator() as $column) {
        $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }

    // Apply style to set light green background for header row
    $headerStyleArray = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => ['rgb' => 'C6EFCE']
        ]
    ];

    // Replace 'A1:AL1' with appropriate range
    $sheet->getStyle('A1:I1')->applyFromArray($headerStyleArray);

    $queryextract = "CALL liste_historique_gab()";
    $result = $connection->query($queryextract);

    if ($result->num_rows > 0) {
        $rowNumber = 2; // Start writing data from row 2
        while ($row = $result->fetch_assoc()) {
            $lineData = array(
                $row['g_serial'], $row['modele'], $row['fournisseur'],
                $row['statut'], $row['date_livraison'], $row['motif'],
                $row['date_installation'], $row['date_demarrage'], $row['date_cloture']
            );

            $sheet->fromArray($lineData, null, 'A' . $rowNumber);
            $rowNumber++;
        }
    } else {
        $sheet->setCellValue('A2', 'No records found...');
    }

    // Auto-size columns based on content
    foreach ($sheet->getColumnIterator() as $column) {
        $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }

    $writer = new Xlsx($spreadsheet);

    $fileName = "historique_gab_" . date('Y-m-d') . ".xlsx";

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    $writer->save('php://output');
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
						<a href="site.php"><b>Nouveau</b></a>
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
                    <a href="type_agence.php"><b>Type Agence</b></a>
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
                <h2 class="title" style="text-align: center;">Historique De GAB</h2>
                <div class="container my-4">
                <header>
                <div class="col-md-2 float-right" style="display: inline-block;margin-right: 750px;margin-left: 20px;">
                <form action="#" method="post" id="extract-form">
                    <input type="hidden" name="extract" value="true">
                    <button type="submit" class="btn btn-edit float-right" style="display: inline-block; margin-bottom: 6px; padding: 4px 10px;">
                        Extract
                    </button>
                </form></div>
                <div class="col-2 float-right" style="display: inline-block; width: 13%;">
                    <input class="input--style-2" type="text" placeholder="G Serial" id="G_serial">
                </div>
                <div class="col-2 float-right" style="display: inline-block; width: 13%;">
                    <input class="input--style-2" type="text" placeholder="Statut" id="Statut">
                </div>
                <div class="btn btn-search float-right" style="display: inline-block; margin-bottom: 6px; padding: 4px 10px;">
                    <a href="#" style="color: white; text-decoration: none;" id="searchButton">Search</a>
                </div>
            </header>
            <table class="popup-table"style="background-color: #fdf7f0;">
        <thead>
            <tr>
            <th>G Serial</th>
            <th>Code GAB</th>
            <th>Modele</th>
            <th>Fournisseur</th>
            <th>Statut</th>
            <th>Motif</th>
            <th>Date livraison</th>
            <th>Date installation</th>
            <th>Date demarrage</th>
            <th>Date cloture</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $result = $connection->query("CALL liste_historique_gab()");
        while ($data = mysqli_fetch_array($result)) {
            $g_serial = $data['g_serial'];
            $code_gab = $data['code_gab'];
            $modele = $data['modele'];
            $fournisseur = $data['fournisseur'];
            $motif = $data['motif'];
            $date_livraison = $data['date_livraison'];
            $date_installation = $data['date_installation'];
            $date_demarrage = $data['date_demarrage'];
            $statut = $data['statut'];
            $date_cloture = $data['date_cloture'];
            ?>
            <tr>
                <td><b><?php echo $g_serial; ?></b></td>
                <td><b><?php echo $code_gab; ?></b></td>
                <td><b><?php echo $modele; ?></b></td>
                <td><b><?php echo $fournisseur; ?></b></td>
                <td><b><?php echo $statut; ?></b></td>
                <td><b><?php echo $motif; ?></b></td>
                <td><b><?php echo $date_livraison; ?></b></td>
                <td><b><?php echo $date_installation; ?></b></td>
                <td><b><?php echo $date_demarrage; ?></b></td>
                <td><b><?php echo $date_cloture; ?></b></td>
            </tr>
            <?php
        }
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
    const statut = document.getElementById("Statut").value;

    if (gSerial) {
        // Call the search procedure for g_serial and fetch results from server
        fetch("?G_serial=" + gSerial)
            .then(response => response.json())
            .then(data => displayResults(data))
            .catch(error => console.error("Error:", error));
    } else if (statut) {
        // Call the search procedure for Statut and fetch results from server
        fetch("?Statut=" + statut)
            .then(response => response.json())
            .then(data => displayResults(data))
            .catch(error => console.error("Error:", error));
    }
});

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
                            <th>Code GAB</th>
                            <th>Modele</th>
                            <th>Fournisseur</th>
                            <th>Statut</th>
                            <th>Motif</th>
                            <th>Date Livraison</th>
                            <th>Date Installation</th>
                            <th>Date Démarrage</th>
                            <th>Date Clôture</th>`;
    table.appendChild(headerRow);

    // Loop through the results and create table rows
    results.forEach(result => {
        const row = document.createElement("tr");
        row.innerHTML = `<td><b>${result.g_serial}</b></td>
                        <td><b>${result.code_gab}</b></td>
                        <td><b>${result.modele}</b></td>
                        <td><b>${result.fournisseur}</b></td>
                        <td><b>${result.statut}</b></td>
                        <td><b>${result.motif}</b></td>
                        <td><b>${result.date_livraison}</b></td>
                        <td><b>${result.date_installation}</b></td>
                        <td><b>${result.date_demarrage}</b></td>
                        <td><b>${result.date_cloture}</b></td>`;
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
</body>

</html>
