<?php
require_once "config.php";
    // Retrieve the data from the database and populate the array of options
    $resulttype_agence = $connection->query("CALL show_type_agence_gab()");
    $type_agence_options = array();

    if ($resulttype_agence->num_rows > 0) {
        while ($row = $resulttype_agence->fetch_assoc()) {
            $type_agence_options[] = $row['nom_type_agence'];
        }
    }
    // Free the result after executing the stored procedure
    $connection->next_result();

    // Retrieve the data from the database and populate the array of options
    $resultadresse = $connection->query("CALL show_adresse()");
    $adresse_options = array();

    if ($resultadresse->num_rows > 0) {
        while ($row = $resultadresse->fetch_assoc()) {
            $adresse_options[] = $row['nom_adresse'];
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
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
            if (empty($_POST["Libelle"]) || empty($_POST["Bon_commande"]) || empty($_POST["Module"])) {
                echo "Error: Please fill in all the required fields.";
                var_dump($_POST["Libelle"]);
                var_dump($date_livraison);
                var_dump($module);
            } else {
    // Get the value of gab attributes from the form
        $libelle = $_POST["Libelle"];
        $date_ouverture = date('Y-m-d', strtotime($_POST["Date_ouverture"]));
        $code_agence = $_POST["Code_agence"];
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
        $date_achat = date('Y-m-d', strtotime($_POST["Date_achat"]));
        $date_demarrage = date('Y-m-d', strtotime($_POST["Date_demarrage"]));
        $date_cloture = isset($_POST["Date_cloture"]) ? date('Y-m-d', strtotime($_POST["Date_cloture"])) : null;
        $module = $_POST["Module"];
        var_dump($libelle);
        var_dump($bon_commande);
        var_dump($date_achat);
        var_dump($date_demarrage);
        var_dump($date_livraison);
        var_dump($module);
        $type_agence = $_POST["Type_agence"];
        $code_gab = $_POST["Code_gab"];
        $adresse = $_POST["Adresse"];
     // Check if any of the fields contain empty strings
     $emptyFields = array($libelle, $bon_commande, $date_achat, $date_demarrage, $date_livraison, $module);
     if (in_array("", $emptyFields, true)) {
         echo "Error: Please fill in all required fields.";
     } else {
    $queryform = "CALL create_gab('$libelle', '$bon_commande', '$date_ouverture', $code_agence, '$statut', '$debut_garantie', '$fin_garantie', '$os', '$barcode_scanner', '$camera', '$card_reader', '$cash_dispenser', '$journal_printer', $ecryptor, '$cash_acceptor_status', '$depository', '$pin_pad', '$receipt_printer', '$passboo', '$envelope_depository', '$cheque_unit', '$bill_acceptor', '$operator_panel', '$passbook', '$scanner', '$check_acceptor', '$statement_printer', '$uninterruptable_power_supply', $disk, '$cd_rom', $licenses_k3a, '$win32_operatingsystem_status', '$win32_videocontroller_status', $ram, '$windows_license_status', '$neon', '$date_livraison', '$date_achat', '$date_demarrage', '$date_cloture', '$module', '$type_agence', $code_gab, '$adresse')";
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
					<a href="generic.html"><b>Piece de rechange</b></a>
					<a href="index.html"><b>Paramétrage</b></a>
				</nav>
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
                            <input class="input--style-2" type="text" placeholder="Bon commande" name="Bon_commande" required>
                            </div>
                            <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Gab serial" name="Libelle">
                        </div>
                        </div>
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
                            <input class="input--style-2" type="text" placeholder="Code gab" name="Code_gab">
                        </div>
                        </div>
                        <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                                <!--<div class="input-group">-->
                                    <!--<input class="input--style-2 js-datepicker" type="text" placeholder="Année adjucation" name="Date_achat">-->
                                    <input class="input--style-2" type="text" id="datepicker1" placeholder="Date installation" name="Date_ouverture" data-date-format="dd/mm/yyyy">
                                <!--</div>-->
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
                                    <div class="input-group">
                                       <!-- <input class="input--style-2 js-datepicker" type="text" placeholder="Date de commande" name="Date_de_commande">-->
                                        <input class="input--style-2" type="text" id="datepicker2" placeholder="Date achat" name="Date_achat" data-date-format="dd/mm/yyyy">
                                    </div>
                            </div>
                            <div class="col-2">
                                    <div class="input-group">
                                       <!-- <input class="input--style-2 js-datepicker" type="text" placeholder="Date de livraison" name="Date_livraison">-->
                                        <input class="input--style-2" type="text" id="datepicker3" placeholder="Date livraison" name="Date_livraison" data-date-format="dd/mm/yyyy">
                                    </div>
                            </div>
                        </div>
                        <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                                    <div class="input-group">
                                       <input class="input--style-2" type="text" id="datepicker4" placeholder="Date demarrage" name="Date_demarrage" data-date-format="dd/mm/yyyy">
                                    </div>
                            </div>
                            <div class="col-2">
                                    <div class="input-group">
                                        <input class="input--style-2" type="text" id="datepicker5" placeholder="Date cloture" name="Date_cloture" data-date-format="dd/mm/yyyy">
                                    </div>
                            </div>
                        </div>
                        <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                                    <div class="input-group">
                                       <input class="input--style-2" type="text" id="datepicker6" placeholder="Debut garantie" name="Debut_garantie" data-date-format="dd/mm/yyyy">
                                    </div>
                            </div>
                            <div class="col-2">
                                    <div class="input-group">
                                        <input class="input--style-2" type="text" id="datepicker7" placeholder="Fin garantie" name="Fin_garantie" data-date-format="dd/mm/yyyy">
                                    </div>
                            </div>
                        </div>
                        <div class="row row-space" style="margin-bottom: 25px;">
                        <div class="col-2">
                                <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="Statut">
                                        <option disabled="disabled" selected="selected">Statut</option>
                                        <option>Stock</option>
                                        <option>Suspendu</option>
                                        <option>Actif</option>
                                        <option>Cesse</option>
                                    </select>
                                    <div class="select-dropdown"></div>
                                </div>
                        </div>
                            <div class="col-2">
                            <div class="input-group">
                                <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="Module">
                                            <option disabled="disabled" selected="selected">Module</option>
                                            <option>Retrait</option>
                                            <option>Retrait/Depot</option>
                                            <option>Retrait/Change</option>
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
                                    <select name="Adresse">
                                        <option disabled="disabled" selected="selected">Adresse</option>
                                        <?php
                                        // Loop through the array of options and add each one to the dropdown list
                                        foreach ($adresse_options as $option) {
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
                                    <select name="OS">
                                            <option disabled="disabled" selected="selected">OS</option>
                                            <option>Windows XP</option>
                                            <option>Windows 7</option>
                                            <option>Windows 10</option>
                                        </select>
                                    <div class="select-dropdown"></div>
                                </div>
                                 </div>
                            </div>
                        </div>
                        <div class="row row-space" style="margin-bottom: 25px;">
                        <div class="col-2">
                                <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="Cash_dispenser">
                                        <option disabled="disabled" selected="selected">Cassette</option>
                                        <option>4</option>
                                        <option>8</option>
                                    </select>
                                    <div class="select-dropdown"></div>
                                </div>
                        </div>
                        <div class="col-2">
                        <input type="checkbox" name="Neon" id="neon" <?php echo ($neon === "true") ? "checked" : ""; ?>>
                        <label for="neon">Neon</label>
                        </div>
                        </div>
                        <div class="row row-space" style="margin-bottom: 25px;">
                            <div class="col-2">
                                <input type="checkbox" name="Barcode_scanner" id="barcode_scanner" <?php echo ($barcode_scanner === "true") ? "checked" : ""; ?>>
                                <label for="barcode_scanner">Barcode Scanner</label>
                            </div>
                            <div class="col-2">
                                <input type="checkbox" name="Camera" id="camera" <?php echo ($camera === "true") ? "checked" : ""; ?>>
                                <label for="camera">Camera</label>
                            </div>
                        </div>
                        <div class="row row-space" style="margin-bottom: 25px;">
                        <div class="col-2">
                            <input type="checkbox" name="Card_reader" id="card_reader" <?php echo ($card_reader === "true") ? "checked" : ""; ?>>
                            <label for="card_reader">Card Reader</label>
                        </div>
                        <div class="col-2">
                            <input type="checkbox" name="Journal_printer" id="journal_printer" <?php echo ($journal_printer === "true") ? "checked" : ""; ?>>
                            <label for="journal_printer">Journal Printer</label>
                        </div>
                    </div>

                    <div class="row row-space" style="margin-bottom: 25px;">
                        <div class="col-2">
                            <input type="checkbox" name="Ecryptor" id="ecryptor" <?php echo ($ecryptor === "true") ? "checked" : ""; ?>>
                            <label for="ecryptor">Ecryptor</label>
                        </div>

                        <div class="col-2">
                            <input type="checkbox" name="Cash_acceptor_status" id="cash_acceptor_status" <?php echo ($cash_acceptor_status === "true") ? "checked" : ""; ?>>
                            <label for="cash_acceptor_status">Cash Acceptor Status</label>
                        </div>
                    </div>

                    <div class="row row-space" style="margin-bottom: 25px;">
                        <div class="col-2">
                            <input type="checkbox" name="Depository" id="depository" <?php echo ($depository === "true") ? "checked" : ""; ?>>
                            <label for="depository">Depository</label>
                        </div>

                        <div class="col-2">
                            <input type="checkbox" name="Pin_pad" id="pin_pad" <?php echo ($pin_pad === "true") ? "checked" : ""; ?>>
                            <label for="pin_pad">Pin Pad</label>
                        </div>
                    </div>

                    <div class="row row-space" style="margin-bottom: 25px;">
                        <div class="col-2">
                            <input type="checkbox" name="Receipt_printer" id="receipt_printer" <?php echo ($receipt_printer === "true") ? "checked" : ""; ?>>
                            <label for="receipt_printer">Receipt Printer</label>
                        </div>
                        <div class="col-2">
                            <input type="checkbox" name="Passboo" id="passboo" <?php echo ($passboo === "true") ? "checked" : ""; ?>>
                            <label for="passboo">Passboo</label>
                        </div>
                    </div>

                    <div class="row row-space" style="margin-bottom: 25px;">
                        <div class="col-2">
                            <input type="checkbox" name="Envelope_depository" id="envelope-depository" <?php echo ($envelope_depository === "true") ? "checked" : ""; ?>>
                            <label for="envelope-depository">Envelope Depository</label>
                        </div>
                        <div class="col-2">
                            <input type="checkbox" name="Cheque_unit" id="cheque-unit" <?php echo ($cheque_unit === "true") ? "checked" : ""; ?>>
                            <label for="cheque-unit">Cheque Unit</label>
                        </div>
                    </div>

                    <div class="row row-space" style="margin-bottom: 25px;">
                        <div class="col-2">
                            <input type="checkbox" name="Bill_acceptor" id="bill-acceptor" <?php echo ($bill_acceptor === "true") ? "checked" : ""; ?>>
                            <label for="bill-acceptor">Bill Acceptor</label>
                        </div>
                        <div class="col-2">
                            <input type="checkbox" name="Disk" id="disk" <?php echo ($disk === "true") ? "checked" : ""; ?>>
                            <label for="disk">Disk</label>
                        </div>
                    </div>

                    <div class="row row-space" style="margin-bottom: 25px;">
                        <div class="col-2">
                            <input type="checkbox" name="CD_ROM" id="cd-rom" <?php echo ($cd_rom === "true") ? "checked" : ""; ?>>
                            <label for="cd-rom">CD ROM</label>
                        </div>
                        <div class="col-2">
                            <input type="checkbox" name="Licenses_k3a" id="licenses-k3a" <?php echo ($licenses_k3a === "true") ? "checked" : ""; ?>>
                            <label for="licenses-k3a">Licenses k3a</label>
                        </div>
                    </div>

                    <div class="row row-space" style="margin-bottom: 25px;">
                        <div class="col-2">
                            <input type="checkbox" name="Win32_operatingsystem_status" id="win32-operatingsystem-status" <?php echo ($win32_operatingsystem_status === "true") ? "checked" : ""; ?>>
                            <label for="win32-operatingsystem-status">Win32 Operatingsystem Status</label>
                        </div>
                        <div class="col-2">
                            <input type="checkbox" name="Win32_videocontroller_status" id="win32-videocontroller-status" <?php echo ($win32_videocontroller_status === "true") ? "checked" : ""; ?>>
                            <label for="win32-videocontroller-status">Win32 Videocontroller Status</label>
                        </div>
                    </div>
                <div class="row row-space" style="margin-bottom: 25px;">
                    <div class="col-2">
                        <input type="checkbox" name="Operator_panel" id="op-panel" <?php echo ($operator_panel === "true") ? "checked" : ""; ?>>
                        <label for="op-panel">Operator Panel</label>
                    </div>
                    <div class="col-2">
                        <input type="checkbox" name="Passbook" id="passbook" <?php echo ($passbook === "true") ? "checked" : ""; ?>>
                        <label for="passbook">Passbook</label>
                    </div>
                </div>
                <div class="row row-space" style="margin-bottom: 25px;">
                    <div class="col-2">
                        <input type="checkbox" name="Scanner" id="scanner" <?php echo ($scanner === "true") ? "checked" : ""; ?>>
                        <label for="scanner">Scanner</label>
                    </div>
                    <div class="col-2">
                        <input type="checkbox" name="Check_acceptor" id="check-acceptor" <?php echo ($check_acceptor === "true") ? "checked" : ""; ?>>
                        <label for="check-acceptor">Check Acceptor</label>
                    </div>
                </div>
                <div class="row row-space" style="margin-bottom: 25px;">
                    <div class="col-2">
                        <input type="checkbox" name="Statement_printer" id="stmt-printer" <?php echo ($statement_printer === "true") ? "checked" : ""; ?>>
                        <label for="stmt-printer">Statement Printer</label>
                    </div>
                    <div class="col-2">
                        <input type="checkbox" name="Uninterruptable_power_supply" id="ups" <?php echo ($uninterruptable_power_supply === "true") ? "checked" : ""; ?>>
                        <label for="ups">Uninterruptable Power Supply</label>
                    </div>
                </div>
                <div class="row row-space" style="margin-bottom: 25px;">
                    <div class="col-2">
                        <input type="checkbox" name="RAM" id="ram" <?php echo ($ram === "true") ? "checked" : ""; ?>>
                        <label for="ram">RAM</label>
                    </div>
                    <div class="col-2">
                        <input type="checkbox" name="Windows_license_status" id="win-license" <?php echo ($windows_license_status === "true") ? "checked" : ""; ?>>
                        <label for="win-license">Windows License Status</label>
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