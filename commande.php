<?php
require_once "config.php";
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the value of "Bon commande" from the form
    $bon_commande = $_POST["Bon_commande"];
    $numero_contrat = $_POST["Numero_de_contrat"];
    $annee_adjucation = date('Y-m-d', strtotime($_POST["Année_adjucation"]));
    $date_commande = date('Y-m-d', strtotime($_POST["Date_de_commande"]));
    $nature_commande = $_POST["nature_commande"];
    $modele = $_POST["modele"];
    $quantite = $_POST["quantite"];
    $commentaire = $_POST["commentaire"];
    $taux = $_POST["taux"];
    $date_livraison = date('Y-m-d', strtotime($_POST["Date_de_livraison"]));
    $module = $_POST["module"];
    // Debug: Output the values to check
var_dump($annee_adjucation);
var_dump($date_commande);
var_dump($date_livraison);
    $query = "CALL create_commande('$bon_commande', '$numero_contrat', '$annee_adjucation', '$date_commande', '$nature_commande', '$modele', '$quantite', '$commentaire', '$taux', '$date_livraison', '$module')";
    if ($connection->query($query) === TRUE) {
        // Data insertion successful
        echo "Data inserted successfully!";
    } else {
        // Data insertion failed
        echo "Error: " . $sql . "<br>" . $connection->error;
    }
    $resultmodele = $connection->query("CALL show_modele_gab()");
    $modele_options = array();
    
    if ($resultmodele->num_rows > 0) {
        while ($row = $resultmodele->fetch_assoc()) {
            $modele_options[] = $row['nom_modele'];
        }
    }
    //free result
    $resultmodele->free();
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
    <title>Au Register Forms by Colorlib</title>

    <!-- Icons font CSS-->
    <link href="assets/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="assets/css/select2.min.css" rel="stylesheet" media="all">
    <link href="assets/css/daterangepicker.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="assets/css/mainform.css" rel="stylesheet" media="all">
    <link rel="stylesheet" href="assets/css/main.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
</head>
<body>
            <script>
            $(function() {
            $('input[name="bon_commande"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 1901,
                maxYear: parseInt(moment().format('YYYY'),10)
            }, function(start, end, label) {
                var years = moment().diff(start, 'years');
                alert("You are " + years + " years old!");
            });
            });
            </script>
    		<!-- Header -->
			<header id="header">
				<div class="inner">
					<a href="index.php" class="logo"><img src="images/logo2.png"></a>
                </div>
			</header>

		<!-- Banner -->
			<section id="banner" style="padding-top: 130px;padding-bottom: 100px;">
				<h1><b>Gestonnaire De GAB</b></h1>
				<nav id="nav">
					<a href="commande.html"><b>Commandes GAB</b></a>
					<a href="generic.html"><b>Agence</b></a>
					<a href="index.html"><b>GAB</b></a>
					<a href="generic.html"><b>Piece de rechange</b></a>
					<a href="index.html"><b>Paramétrage</b></a>
				</nav>
				<a href="#navPanel" class="navPanelToggle"><span class="fa fa-bars"></span></a>
			</section>
    <div class="page-wrapper bg-orange p-t-70 p-b-100 font-robo">
        <div class="wrapper wrapper--w960">
        <sectio id="two" class="wrapper style1 special" style="display: flex; justify-content: space-between; flex-wrap: wrap; padding-left: 100px;padding-right: 100px;">
            <div class="card card-2">
                <h2 class="title" style="text-align: center;">Formulaire Commande</h2>
                    <form method="POST">
                        <div class="row row-space" style="
                        margin-bottom: 25px;
                    ">
                            <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Bon commande" name="Bon_commande">
                            </div>
                            <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Numero de contrat" name="Numero_de_contrat">
                        </div>
                        </div>
                        <div class="row row-space" style="
                        margin-bottom: 25px;
                    ">
                            <div class="col-2">
                                <div class="input-group">
                                    <input class="input--style-2 js-datepicker" type="text" placeholder="Année adjucation" name="Année_adjucation">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="module">
                                            <option disabled="disabled" selected="selected">module</option>
                                            <option>Retrait</option>
                                            <option>Retrait/Depot</option>
                                            <option>Retrait/Change</option>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space" style="
                        margin-bottom: 25px;
                    ">
                            <div class="col-2">
                                    <div class="input-group">
                                        <input class="input--style-2 js-datepicker" type="text" placeholder="Date de commande" name="Date_de_commande">
                                    </div>
                            </div>
                            <div class="col-2">
                                    <div class="input-group">
                                        <input class="input--style-2 js-datepicker" type="text" placeholder="Date de livraison" name="Date_de_livraison">
                                    </div>
                            </div>
                        </div>
                        <div class="row row-space" style="
                        margin-bottom: 25px;
                    ">
                            <div class="col-2">
                                <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="modele">
                                        <option disabled="disabled" selected="selected">Modele de GAB</option>
                                        <option >modele322</option>
                                            <?php
                                            var_dump($modele_options);

                                             // Generate the options for the dropdown
                                             foreach ($modele_options as $option) {
                                                echo '<option value="' . $option . '">' . $option . '</option>';
                                             }
                                            ?>
                                    </select>
                                    <div class="select-dropdown"></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="rs-select2 js-select-simple select--no-search">
                                    <select name="nature_commande">
                                        <option disabled="disabled" selected="selected">Nature de commande</option>
                                        <option>Class 1</option>
                                        <option>Class 2</option>
                                        <option>Class 3</option>
                                    </select>
                                    <div class="select-dropdown"></div>
                                </div>
                        </div>
                        </div>
                        <div class="row row-space" style="
                        margin-bottom: 25px;
                    ">
                            <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Quantite" name="quantite">
                            </div>
                            <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Taux de maintenance" name="taux">
                            </div>
                        </div>
                        <div class="row row-space" style="
                        margin-bottom: 25px;
                    ">
                            <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Commentaire" name="commentaire">
                        </div>
                        
                        <div class="col-2">
                        <div class="p-t-30">
                            <button class="btn btn--radius btn--green" type="submit">Search</button>
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
    <script src="assets/js/daterangepicker.js"></script>

    <!-- Main JS-->
    <script src="assets/js/global.js"></script>

</body>

</html>
