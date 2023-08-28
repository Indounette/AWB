<?php
require_once "config.php";
require 'vendor/autoload.php'; // Path to autoload.php for PhpSpreadsheet
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

    // Fetch data from show_commande procedure
$resultcommande = $connection->query("CALL show_commande()");
$commande_options = array();

if ($resultcommande->num_rows > 0) {
    while ($row = $resultcommande->fetch_assoc()) {
        $commande_options[] = $row['bon_commande'];
    }
}
// Free the result after executing the stored procedure
$connection->next_result();

    // Initialize variables with empty values
    $barcode_scanner = "false";
    $camera = "false";
    $card_reader = "false";
    $journal_printer = "false";
    $ecryptor = "false";
    $cash_acceptor_status = "false";
    $depository = "false";
    $pin_pad = "false";
    $receipt_printer = "false";
    $passboo = "false";
    $envelope_depository = "false";
    $cheque_unit = "false";
    $bill_acceptor = "false";
    $operator_panel = "false";
    $passbook = "false";
    $scanner = "false";
    $check_acceptor = "false";
    $statement_printer = "false";
    $uninterruptable_power_supply = "false";
    $disk = "false";
    $cd_rom = "false";
    $licenses_k3a = "false";
    $win32_operatingsystem_status = "false";
    $win32_videocontroller_status = "false";
    $ram = "false";
    $windows_license_status = "false";
    $neon = "false";
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_POST["submit"])) {
            if (empty($_POST["G_serial"]) ) {
                echo "Error: Please fill in all the required fields.";
            } else {   
    // Get the value of gab attributes from the form
        $g_serial = $_POST["G_serial"];
        $bon_commande = $_POST["Bon_commande"];
        $date_installation = date('Y-m-d', strtotime($_POST["Date_installation"]));
        $statut = $_POST["Statut"];
        $os = $_POST["OS"];
        $barcode_scanner = isset($_POST["Barcode_scanner"]) ? "true" : "false";
        $camera = isset($_POST["Camera"]) ? "true" : "false";
        $card_reader = isset($_POST["Card_reader"]) ? "true" : "false";
        $cash_dispenser = $_POST["Cash_dispenser"];
        $journal_printer = isset($_POST["Journal_printer"]) ? "true" : "false";
        $ecryptor = isset($_POST["Ecryptor"]) ? "true" : "false";
        $cash_acceptor_status = isset($_POST["Cash_acceptor_status"]) ? "true" : "false";
        $depository = isset($_POST["Depository"]) ? "true" : "false";
        $pin_pad = isset($_POST["Pin_pad"]) ? "true" : "false";
        $receipt_printer = isset($_POST["Receipt_printer"]) ? "true" : "false";
        $passboo = isset($_POST["Passboo"]) ? "true" : "false";
        $envelope_depository = isset($_POST["Envelope_depository"]) ? "true" : "false";
        $cheque_unit = isset($_POST["Cheque_unit"]) ? "true" : "false";
        $bill_acceptor = isset($_POST["Bill_acceptor"]) ? "true" : "false";
        $operator_panel = isset($_POST["Operator_panel"]) ? "true" : "false";
        $passbook = isset($_POST["Passbook"]) ? "true" : "false";
        $scanner = isset($_POST["Scanner"]) ? "true" : "false";
        $check_acceptor = isset($_POST["Check_acceptor"]) ? "true" : "false";
        $statement_printer = isset($_POST["Statement_printer"]) ? "true" : "false";
        $uninterruptable_power_supply = isset($_POST["Uninterruptable_power_supply"]) ? "true" : "false";
        $disk = isset($_POST["Disk"]) ? "true" : "false";
        $cd_rom = isset($_POST["CD_ROM"]) ? "true" : "false";
        $licenses_k3a = isset($_POST["Licenses_k3a"]) ? "true" : "false";
        $win32_operatingsystem_status = isset($_POST["Win32_operatingsystem_status"]) ? "true" : "false";
        $win32_videocontroller_status = isset($_POST["Win32_videocontroller_status"]) ? "true" : "false";
        $ram = isset($_POST["RAM"]) ? "true" : "false";
        $windows_license_status = isset($_POST["Windows_license_status"]) ? "true" : "false";
        $neon = isset($_POST["Neon"]) ? "true" : "false";
        $date_livraison = date('Y-m-d', strtotime($_POST["Date_livraison"]));
        $date_demarrage = date('Y-m-d', strtotime($_POST["Date_demarrage"]));
        $date_cloture = isset($_POST["Date_cloture"]) ? date('Y-m-d', strtotime($_POST["Date_cloture"])) : null;
        $modele = $_POST["Modele"];
        
        $date_cloture = $_POST["Date_cloture"];
        if ($date_cloture === '1970-01-01') {
            $date_cloture = '0000-00-00';
        }

        $date_installation = $_POST["Date_installation"];
        if ($date_installation === '1970-01-01') {
            $date_installation = '0000-00-00';
        }

        $date_livraison = $_POST["Date_livraison"];
        if ($date_livraison === '1970-01-01') {
            $date_livraison = '0000-00-00';
        }

        $date_demarrage = $_POST["Date_demarrage"];
        if ($date_demarrage === '1970-01-01') {
            $date_demarrage = '0000-00-00';
        }

     // Check if any of the fields contain empty strings
     $emptyFields = array($g_serial, $bon_commande);
     if (in_array("", $emptyFields, true)) {
         echo "Error: Please fill in all required fields.";
     } else {
         // Check the count using a separate query
         $querycheck = $connection->query("CALL querycheckgab('$g_serial')");

         if ($querycheck) {
             $row = $querycheck->fetch_row();
             $count = $row[0]; // The result of COUNT(*) will be in the first column of the row
             $connection->next_result();
         } 
         if ($count == 1) {
             $queryform = "CALL update_gab('$g_serial', '$bon_commande', '$date_installation', '$statut', '$os', '$barcode_scanner', '$camera', '$card_reader', '$cash_dispenser', '$journal_printer', '$ecryptor', '$cash_acceptor_status', '$depository', '$pin_pad', '$receipt_printer', '$passboo', '$envelope_depository', '$cheque_unit', '$bill_acceptor', '$operator_panel', '$passbook', '$scanner', '$check_acceptor', '$statement_printer', '$uninterruptable_power_supply', $disk, '$cd_rom', $licenses_k3a, '$win32_operatingsystem_status', '$win32_videocontroller_status', $ram, '$windows_license_status', '$neon', '$date_livraison', '$date_demarrage', '$date_cloture', '$modele')";
         } else {
             $queryform = "CALL create_gab('$g_serial', '$bon_commande', '$date_installation', '$statut', '$os', '$barcode_scanner', '$camera', '$card_reader', '$cash_dispenser', '$journal_printer', '$ecryptor', '$cash_acceptor_status', '$depository', '$pin_pad', '$receipt_printer', '$passboo', '$envelope_depository', '$cheque_unit', '$bill_acceptor', '$operator_panel', '$passbook', '$scanner', '$check_acceptor', '$statement_printer', '$uninterruptable_power_supply', $disk, '$cd_rom', $licenses_k3a, '$win32_operatingsystem_status', '$win32_videocontroller_status', $ram, '$windows_license_status', '$neon', '$date_livraison', '$date_demarrage', '$date_cloture', '$modele')";
         }
         try { 
            $result = $connection->query($queryform);
        
            if (!$result) {
                // Data insertion failed
                $error_message = $connection->error;
        
                // Check if the error message matches the trigger message
                if (strpos($error_message, "Cannot change from suspendu to actif/stock/cede without setting date_fin.") !== false) {
                    echo "<script>alert('Cannot change from suspendu to actif/stock/cede without setting date_fin.');</script>";
                } else {
                    echo "<div class='error-box'>" . $error_message . "</div>";
                }
            } else {
                // Data insertion successful
               header("Location: agab.php");
               exit;
            }
        } catch (mysqli_sql_exception $e) {   
            // Display the error message
            $error_message = $e->getMessage();
            echo "<div class='error-box'>" . $error_message . "</div>";
        }        
  
     }
 }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $uploadedFile = $_FILES["file"];
    
    if ($uploadedFile["error"] === UPLOAD_ERR_OK) {
        $fileName = $uploadedFile["name"];
        $tempFilePath = $uploadedFile["tmp_name"];
        
        // Load the Excel file using PhpSpreadsheet
        $spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load($tempFilePath);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            
        $firstRow = true; // Flag to indicate the first row
        $dataRows = [];   // Array to store data from each row
         // Count of total rows and current row index
         $totalRows = count($dataRows);
         $currentIndex = 0; 

        foreach ($sheetData as $row) {
            if ($firstRow) {
                $firstRow = false; // Set the flag to false for subsequent rows
                continue; // Skip processing for the first row
            }
            $data = [];
            $data["g_serial"] = $row["A"];
            $data["bon_commande"] = $row["B"];
            $data["date_installation"] = date('Y-m-d', strtotime($row["D"]));
            $data["statut"] = $row["E"];
            $data["os"] = $row["K"];
            $data["barcode_scanner"] = isset($row["L"]) ? "true" : "false";
            $data["camera"] = isset($row["M"]) ? "true" : "false";
            $data["card_reader"] = isset($row["N"]) ? "true" : "false";
            $data["cash_dispenser"] = $row["O"];
            $data["journal_printer"] = isset($row["P"]) ? "true" : "false";
            $data["ecryptor"] = isset($row["Q"]) ? "true" : "false";
            $data["cash_acceptor_status"] = isset($row["R"]) ? "true" : "false";
            $data["depository"] = isset($row["S"]) ? "true" : "false";
            $data["pin_pad"] = isset($row["T"]) ? "true" : "false";
            $data["receipt_printer"] = isset($row["U"]) ? "true" : "false";
            $data["passbook"] = isset($row["V"]) ? "true" : "false";
            $data["envelope_depository"] = isset($row["W"]) ? "true" : "false";
            $data["cheque_unit"] = isset($row["X"]) ? "true" : "false";
            $data["bill_acceptor"] = isset($row["Y"]) ? "true" : "false";
            $data["operator_panel"] = isset($row["Z"]) ? "true" : "false";
            $data["passbook"] = isset($row["AA"]) ? "true" : "false";
            $data["scanner"] = isset($row["AB"]) ? "true" : "false";
            $data["check_acceptor"] = isset($row["AC"]) ? "true" : "false";
            $data["statement_printer"] = isset($row["AD"]) ? "true" : "false";
            $data["uninterruptable_power_supply"] = isset($row["AE"]) ? "true" : "false";
            $data["disk"] = isset($row["AF"]) ? "true" : "false";
            $data["cd_rom"] = isset($row["AG"]) ? "true" : "false";
            $data["licenses_k3a"] = isset($row["AH"]) ? "true" : "false";
            $data["win32_operatingsystem_status"] = isset($row["AI"]) ? "true" : "false";
            $data["win32_videocontroller_status"] = isset($row["AJ"]) ? "true" : "false";
            $data["ram"] = isset($row["AK"]) ? "true" : "false";
            $data["windows_license_status"] = isset($row["AL"]) ? "true" : "false";
            $data["neon"] = isset($row["AM"]) ? "true" : "false";
            $data["date_livraison"] = date('Y-m-d', strtotime($row["F"]));
            $data["date_demarrage"] = date('Y-m-d', strtotime($row["G"]));
            $data["date_cloture"] = date('Y-m-d', strtotime($row["AL"]));
            $data["modele"] = $row["J"];

            
                if ($data['date_cloture'] === '1970-01-01') {
                    $data['date_cloture'] = '0000-00-00';
                }

                if ($data['date_installation'] === '1970-01-01') {
                    $data['date_installation'] = '0000-00-00';
                }

                if ($data['date_livraison'] === '1970-01-01') {
                    $data['date_livraison'] = '0000-00-00';
                }

                if ($data['date_demarrage'] === '1970-01-01') {
                    $data['date_demarrage'] = '0000-00-00';
                }
            
            $dataRows[] = $data; // Store data for this row in the array
            // Check the count using a separate query
         $querycheck = $connection->query("CALL querycheckgab('{$data["g_serial"]}')");

         if ($querycheck) {
             $row = $querycheck->fetch_row();
             $count = $row[0]; // The result of COUNT(*) will be in the first column of the row
             $connection->next_result();
         } 
         if ($count == 1) {
            $queryform = "CALL update_gab('{$data["g_serial"]}', '{$data["bon_commande"]}', '{$data["date_installation"]}', '{$data["statut"]}', '{$data["os"]}', '{$data["barcode_scanner"]}', '{$data["camera"]}', '{$data["card_reader"]}', '{$data["cash_dispenser"]}', '{$data["journal_printer"]}', '{$data["ecryptor"]}', '{$data["cash_acceptor_status"]}', '{$data["depository"]}', '{$data["pin_pad"]}', '{$data["receipt_printer"]}', '{$data["passboo"]}', '{$data["envelope_depository"]}', '{$data["cheque_unit"]}', '{$data["bill_acceptor"]}', '{$data["operator_panel"]}', '{$data["passbook"]}', '{$data["scanner"]}', '{$data["check_acceptor"]}', '{$data["statement_printer"]}', '{$data["uninterruptable_power_supply"]}', {$data["disk"]}, '{$data["cd_rom"]}', {$data["licenses_k3a"]}, '{$data["win32_operatingsystem_status"]}', '{$data["win32_videocontroller_status"]}', {$data["ram"]}, '{$data["windows_license_status"]}', '{$data["neon"]}', '{$data["date_livraison"]}', '{$data["date_demarrage"]}', '{$data["date_cloture"]}', '{$data["modele"]}')";
        } else {
            $queryform = "CALL create_gab('{$data["g_serial"]}', '{$data["bon_commande"]}', '{$data["date_installation"]}', '{$data["statut"]}', '{$data["os"]}', '{$data["barcode_scanner"]}', '{$data["camera"]}', '{$data["card_reader"]}', '{$data["cash_dispenser"]}', '{$data["journal_printer"]}', '{$data["ecryptor"]}', '{$data["cash_acceptor_status"]}', '{$data["depository"]}', '{$data["pin_pad"]}', '{$data["receipt_printer"]}', '{$data["passboo"]}', '{$data["envelope_depository"]}', '{$data["cheque_unit"]}', '{$data["bill_acceptor"]}', '{$data["operator_panel"]}', '{$data["passbook"]}', '{$data["scanner"]}', '{$data["check_acceptor"]}', '{$data["statement_printer"]}', '{$data["uninterruptable_power_supply"]}', {$data["disk"]}, '{$data["cd_rom"]}', {$data["licenses_k3a"]}, '{$data["win32_operatingsystem_status"]}', '{$data["win32_videocontroller_status"]}', {$data["ram"]}, '{$data["windows_license_status"]}', '{$data["neon"]}', '{$data["date_livraison"]}', '{$data["date_demarrage"]}', '{$data["date_cloture"]}', '{$data["modele"]}')";
        }
        try { 
            $result = $connection->query($queryform);
        
            if (!$result) {
                // Data insertion failed
                $error_message = $connection->error;
        
                // Check if the error message matches the trigger message
                if (strpos($error_message, "Cannot change from suspendu to actif/stock/cede without setting date_fin.") !== false) {
                    echo "<script>alert('Cannot change from suspendu to actif/stock/cede without setting date_fin.');</script>";
                } else {
                    echo "<div class='error-box'>" . $error_message . "</div>";
                }
            } else {
                if ($currentIndex === $totalRows - 1) { // Check if this is the last row
                    // Redirect to another page after processing the last row
                    header("Location: asite.php");
                    exit; // Make sure to exit after sending the redirect header
                }

            }
        } catch (mysqli_sql_exception $e) {   
            // Display the error message
            $error_message = $e->getMessage();
            echo "<div class='error-box'>" . $error_message . "</div>";
        }        
            $currentIndex++;
     }
 }
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
   <style>
    .error-box {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    padding: 10px;
    margin: 10px 0;
    border-radius: 4px;
}
    </style>
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
                <h2 class="title" style="text-align: center;">Formulaire GAB</h2>
                    <form method="POST">
                        <div class="row row-space" style="
                        margin-bottom: 25px; ">
                        <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Gab serial" name="G_serial" value="<?php echo isset($_GET['edit']) ? htmlspecialchars($_GET['edit']) : ''; ?>" required>
                            <?php
                            if (isset($_GET['edit'])) {
    $editValue = $_GET['edit'];
    
    // Call the stored procedure to populate other fields
    $populateResult = $connection->query("CALL details_gab('$editValue')");
    
    if ($populateResult && $populateResult->num_rows > 0) {
        $data = $populateResult->fetch_assoc();
        // Populate other input fields using the returned data
        $bon_commande = $data['bon_commande'];
        $Date_installation = $data['date_installation'];
        $Statut = $data['statut'];
        $OS = $data['os'];
        $barcode_scanner = $data['barcode_scanner'];
        $camera = $data['camera'];
        $card_reader = $data['card_reader'];
        $Cash_dispenser = $data['cash_dispenser'];
        $journal_printer = $data['journal_printer'];
        $ecryptor = $data['ecryptor'];
        $cash_acceptor_status = $data['cash_acceptor_status'];
        $depository = $data['depository'];
        $pin_pad = $data['pin_pad'];
        $receipt_printer = $data['receipt_printer'];
        $passboo = $data['passboo'];
        $envelope_depository = $data['envelope_depository'];
        $cheque_unit = $data['cheque_unit'];
        $bill_acceptor = $data['bill_acceptor'];
        $operator_panel = $data['operator_panel'];
        $passbook = $data['passbook'];
        $scanner = $data['scanner'];
        $check_acceptor = $data['check_acceptor'];
        $statement_printer = $data['statement_printer'];
        $uninterruptable_power_supply = $data['uninterruptable_power_supply'];
        $disk = $data['disk'];
        $cd_rom = $data['cd_rom'];
        $licenses_k3a = $data['licenses_k3a'];
        $win32_operatingsystem_status = $data['win32_operatingsystem_status'];
        $win32_videocontroller_status = $data['win32_videocontroller_status'];
        $ram = $data['ram'];
        $windows_license_status = $data['windows_license_status'];
        $neon = $data['neon'];
        $Date_livraison = $data['date_livraison'];
        $Date_demarrage = $data['date_demarrage'];
        $Date_cloture = $data['date_cloture'];
        $modele = $data['modele'];
                        }}?></div>
                            <div class="col-2">
                            <div class="input-group">
                                <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="Bon_commande" class="js-select2">
                                        <option disabled="disabled" selected="selected">Bon commande</option>
                                        <?php
                                        // Loop through the array of options and add each one to the dropdown list
                                        foreach ($commande_options as $option) {
                                            // Check if $bon_commande variable is set and compare it with the current option
                                            $isSelected = isset($bon_commande) && $bon_commande === $option ? 'selected' : '';
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
                            <input class="input--style-2" type="text" id="datepicker1" placeholder="Date installation" name="Date_installation" value="<?php echo isset($Date_installation) ? htmlspecialchars($Date_installation) : ''; ?>" data-date-format="dd/mm/yyyy">
                            </div>
                            <div class="col-2">
                            <div class="input-group">
                                <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="Modele" class="js-select2">
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
                                <input class="input--style-2" type="text" id="datepicker2" placeholder="Date livraison" name="Date_livraison" value="<?php echo isset($Date_livraison) ? htmlspecialchars($Date_livraison) : ''; ?>" data-date-format="dd/mm/yyyy">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="input-group">
                                <input class="input--style-2" type="text" id="datepicker3" placeholder="Date demarrage" name="Date_demarrage" value="<?php echo isset($Date_demarrage) ? htmlspecialchars($Date_demarrage) : ''; ?>" data-date-format="dd/mm/yyyy">
                            </div>
                        </div>
                         </div>
                            <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                                    <div class="input-group">
                                    <input class="input--style-2" type="text" id="datepicker4" placeholder="Date cloture" name="Date_cloture" value="<?php echo isset($Date_cloture) ? htmlspecialchars($Date_cloture) : ''; ?>" data-date-format="dd/mm/yyyy">
                                    </div>
                            </div>
                            <div class="col-2">
                            <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="Statut">
                                            <option disabled="disabled" selected="selected">Statut</option>
                                            <option <?php if(isset($Statut) && $Statut == 'stock') echo 'selected'; ?>>stock</option>
                                            <option <?php if(isset($Statut) && $Statut == 'suspendu') echo 'selected'; ?>>suspendu</option>
                                            <option <?php if(isset($Statut) && $Statut == 'actif') echo 'selected'; ?>>actif</option>
                                            <option <?php if(isset($Statut) && $Statut == 'cede') echo 'selected'; ?>>cede</option>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                        </div></div>
                        <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                            <div class="input-group">
                            <div class="rs-select2 js-select-simple select--no-search">
                                <select name="OS">
                                    <option disabled="disabled" selected="selected">OS</option>
                                    <option <?php if(isset($OS) && $OS == 'Windows XP') echo 'selected'; ?>>Windows XP</option>
                                    <option <?php if(isset($OS) && $OS == 'Windows 7') echo 'selected'; ?>>Windows 7</option>
                                    <option <?php if(isset($OS) && $OS == 'Windows 10') echo 'selected'; ?>>Windows 10</option>
                                </select>
                                <div class="select-dropdown"></div>
                            </div>
                                                            </div>
                            </div>
                        <div class="col-2">
                        <div class="rs-select2 js-select-simple select--no-search">
                        <select name="Cash_dispenser">
                            <option disabled="disabled" selected="selected">Cassette</option>
                            <option <?php if(isset($Cash_dispenser) && $Cash_dispenser == '4') echo 'selected'; ?>>4</option>
                            <option <?php if(isset($Cash_dispenser) && $Cash_dispenser == '8') echo 'selected'; ?>>8</option>
                        </select>
                        <div class="select-dropdown"></div>
                          </div>   
                            </div></div>
                            <div class="row row-space" style="margin-bottom: 25px;">
                                <div class="col-2">
                                    <input type="checkbox" name="RAM" id="ram" <?php echo (isset($ram) && $ram === "true") ? "checked" : ""; ?>>
                                    <label for="ram">RAM</label>
                                </div>
                                <div class="col-2">
                                    <input type="checkbox" name="Neon" id="neon" <?php echo (isset($neon) && $neon === "true") ? "checked" : ""; ?>>
                                    <label for="neon">Neon</label>
                                </div>
                            </div>
                            <div class="row row-space" style="margin-bottom: 25px;">
                                <div class="col-2">
                                    <input type="checkbox" name="Barcode_scanner" id="barcode_scanner" <?php echo (isset($barcode_scanner) && $barcode_scanner === "true") ? "checked" : ""; ?>>
                                    <label for="barcode_scanner">Barcode Scanner</label>
                                </div>
                                <div class="col-2">
                                    <input type="checkbox" name="Camera" id="camera" <?php echo (isset($camera) && $camera === "true") ? "checked" : ""; ?>>
                                    <label for="camera">Camera</label>
                                </div>
                            </div>
                            <div class="row row-space" style="margin-bottom: 25px;">
                                <div class="col-2">
                                    <input type="checkbox" name="Card_reader" id="card_reader" <?php echo (isset($card_reader) && $card_reader === "true") ? "checked" : ""; ?>>
                                    <label for="card_reader">Card Reader</label>
                                </div>
                                <div class="col-2">
                                    <input type="checkbox" name="Journal_printer" id="journal_printer" <?php echo (isset($journal_printer) && $journal_printer === "true") ? "checked" : ""; ?>>
                                    <label for="journal_printer">Journal Printer</label>
                                </div>
                            </div>
                            <div class="row row-space" style="margin-bottom: 25px;">
                                <div class="col-2">
                                    <input type="checkbox" name="Ecryptor" id="ecryptor" <?php echo (isset($ecryptor) && $ecryptor === "true") ? "checked" : ""; ?>>
                                    <label for="ecryptor">Ecryptor</label>
                                </div>
                                <div class="col-2">
                                    <input type="checkbox" name="Cash_acceptor_status" id="cash_acceptor_status" <?php echo (isset($cash_acceptor_status) && $cash_acceptor_status === "true") ? "checked" : ""; ?>>
                                    <label for="cash_acceptor_status">Cash Acceptor Status</label>
                                </div>
                            </div>

                            <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                                <input type="checkbox" name="Depository" id="depository" <?php echo (isset($depository) && $depository === "true") ? "checked" : ""; ?>>
                                <label for="depository">Depository</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" name="Pin_pad" id="pin_pad" <?php echo (isset($pin_pad) && $pin_pad === "true") ? "checked" : ""; ?>>
                                <label for="pin_pad">Pin Pad</label>
                            </div>
                        </div>

                        <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                                <input type="checkbox" name="Receipt_printer" id="receipt_printer" <?php echo (isset($receipt_printer) && $receipt_printer === "true") ? "checked" : ""; ?>>
                                <label for="receipt_printer">Receipt Printer</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" name="Passboo" id="passboo" <?php echo (isset($passboo) && $passboo === "true") ? "checked" : ""; ?>>
                                <label for="passboo">Passboo</label>
                            </div>
                        </div>

                        <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                                <input type="checkbox" name="Envelope_depository" id="envelope-depository" <?php echo (isset($envelope_depository) && $envelope_depository === "true") ? "checked" : ""; ?>>
                                <label for="envelope-depository">Envelope Depository</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" name="Cheque_unit" id="cheque-unit" <?php echo (isset($cheque_unit) && $cheque_unit === "true") ? "checked" : ""; ?>>
                                <label for="cheque-unit">Cheque Unit</label>
                            </div>
                        </div>

                        <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                                <input type="checkbox" name="Bill_acceptor" id="bill-acceptor" <?php echo (isset($bill_acceptor) && $bill_acceptor === "true") ? "checked" : ""; ?>>
                                <label for="bill-acceptor">Bill Acceptor</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" name="Disk" id="disk" <?php echo (isset($disk) && $disk === "true") ? "checked" : ""; ?>>
                                <label for="disk">Disk</label>
                            </div>
                        </div>

                        <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                                <input type="checkbox" name="CD_ROM" id="cd-rom" <?php echo (isset($cd_rom) && $cd_rom === "true") ? "checked" : ""; ?>>
                                <label for="cd-rom">CD ROM</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" name="Licenses_k3a" id="licenses-k3a" <?php echo (isset($licenses_k3a) && $licenses_k3a === "true") ? "checked" : ""; ?>>
                                <label for="licenses-k3a">Licenses k3a</label>
                            </div>
                        </div>

                        <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                                <input type="checkbox" name="Win32_operatingsystem_status" id="win32-operatingsystem-status" <?php echo (isset($win32_operatingsystem_status) && $win32_operatingsystem_status === "true") ? "checked" : ""; ?>>
                                <label for="win32-operatingsystem-status">Win32 Operatingsystem Status</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" name="Win32_videocontroller_status" id="win32-videocontroller-status" <?php echo (isset($win32_videocontroller_status) && $win32_videocontroller_status === "true") ? "checked" : ""; ?>>
                                <label for="win32-videocontroller-status">Win32 Videocontroller Status</label>
                            </div>
                        </div>

                        <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                                <input type="checkbox" name="Operator_panel" id="op-panel" <?php echo (isset($operator_panel) && $operator_panel === "true") ? "checked" : ""; ?>>
                                <label for="op-panel">Operator Panel</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" name="Passbook" id="passbook" <?php echo (isset($passbook) && $passbook === "true") ? "checked" : ""; ?>>
                                <label for="passbook">Passbook</label>
                            </div>
                        </div>

                        <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                                <input type="checkbox" name="Scanner" id="scanner" <?php echo (isset($scanner) && $scanner === "true") ? "checked" : ""; ?>>
                                <label for="scanner">Scanner</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" name="Check_acceptor" id="check-acceptor" <?php echo (isset($check_acceptor) && $check_acceptor === "true") ? "checked" : ""; ?>>
                                <label for="check-acceptor">Check Acceptor</label>
                            </div>
                        </div>

                        <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                                <input type="checkbox" name="Statement_printer" id="stmt-printer" <?php echo (isset($statement_printer) && $statement_printer === "true") ? "checked" : ""; ?>>
                                <label for="stmt-printer">Statement Printer</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" name="Uninterruptable_power_supply" id="ups" <?php echo (isset($uninterruptable_power_supply) && $uninterruptable_power_supply === "true") ? "checked" : ""; ?>>
                                <label for="ups">Uninterruptable Power Supply</label>
                            </div>
                        </div>

                        <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                                <input type="checkbox" name="Windows_license_status" id="win-license" <?php echo (isset($windows_license_status) && $windows_license_status === "true") ? "checked" : ""; ?>>
                                <label for="win-license">Windows License Status</label>
                            </div>
                    <div class="col-2">
                    <div class="p-t-30" style="padding-left: 18px; display: flex; align-items: center;">
                         <form method="post">
                            <button class="btn btn--radius btn--orange" type="submit" name="submit_form" style="margin-bottom: 43px;">Ajouter \ Modifier</button>
                        </form>
                        <form id="uploadForm" enctype="multipart/form-data" style="width: 340px;">
                        <label for="fileInput" class="btn btn--radius btn--orange" style="margin-left: 10px; cursor: pointer;">Upload</label>
                        <input type="file" name="file" id="fileInput" style="display: none;" onchange="uploadFile()" style="margin-bottom: 5px;">
                        </form>
                        </div>
                        <script>
                        function uploadFile() {
                            const fileInput = document.getElementById("fileInput");
                            const file = fileInput.files[0];
                            
                            if (file) {
                                const formData = new FormData();
                                formData.append("file", file);
                                
                                fetch("<?php echo $_SERVER['PHP_SELF']; ?>", { // Use PHP_SELF to post to the same script
                                    method: "POST",
                                    body: formData,
                                })
                                .then(response => response.text())
                                .then(result => {
                                    // Redirect to another page after processing is complete
                                    if (result.includes("Data inserted successfully!")) {
                                        window.location.href = "agab.php";
                                    }
                                })
                                .catch(error => {
                                    console.error("Error:", error);
                                });
                            } else {
                                console.error("No file selected.");
                            }
                        }
                    </script>
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
    $(document).ready(function() {
        $('.js-select2').select2();
    });
</script>
</body>

</html>
