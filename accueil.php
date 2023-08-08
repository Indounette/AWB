<?php
require_once "config.php";

// Execute the first query to retrieve the etat_stock_gab results
$query1 = "CALL etat_des_gab()"; //etat du stock du gab
if ($connection->multi_query($query1)) {
    // Retrieve the result set
    $resultetat = $connection->store_result();

    // Process the result set
	$dataPoints = array();
	while ($row = $resultetat->fetch_assoc()) {
		$dataPoints[] = array(
			"label" => "GAB Actif",
			"y" => $row['active_gab']
		);
		$dataPoints[] = array(
			"label" => "GAB Suspendu",
			"y" => $row['suspendu_gab']
		);
		$dataPoints[] = array(
			"label" => "GAB Stocké",
			"y" => $row['stock_gab']
		);
		$dataPoints[] = array(
			"label" => "GAB Cessé",
			"y" => $row['cesse_gab']
		);
		$totalAgences = $row['total_agence']; // Get the total_agence value
	}	

    // Free up the result set
    $resultetat->free();

    // Move to the next result set
    $connection->next_result();
} else {
    echo "Error executing query1: " . $connection->error;
}

// Execute the second query to retrieve the total_gab results
$query2 = "CALL total_gab()"; // afficher total des gabs
if ($resulttotal = $connection->query($query2)) {
    // Process the result set
    if ($row = $resulttotal->fetch_row()) {
        $totalGABs = $row[0];
    }

    // Free up the result set
    $resulttotal->free();

	// Move to the next result set
    $connection->next_result();
	
} else {
    echo "Error executing query2: " . $connection->error;
}
// Execute the third query 
$query3 = "CALL etat_stock_gab()"; // query to retrieve nouveau_stock and recupere_stock
if ($resultstock = $connection->query($query3)) {
    // Process the result set for the second chart
    $dataPointsStock = array();
    while ($row = $resultstock->fetch_assoc()) {
        $dataPointsStock[] = array(
            "label" => "Stock Nouveau",
            "y" => $row['nouveau_stock']
        );
        $dataPointsStock[] = array(
            "label" => "Stock Recupéré",
            "y" => $row['recupere_stock']
        );
    }

    // Free up the result set
    $resultstock->free();

	// Move to the next result set
	$connection->next_result();

} else {
    echo "Error executing query3: " . $connection->error;
}
// Execute the fourth query 
$query4 = "CALL type_gabs()"; // query to show types of gab
if ($resulttype = $connection->query($query4)) {
    // Process the result set for the second chart
    $dataPointsType = array();
    while ($row = $resulttype->fetch_assoc()) {
        $dataPointsType[] = array(
            "label" => "AC",
            "y" => $row['AC']
        );
		$dataPointsType[] = array(
            "label" => "CASHLESS",
            "y" => $row['CASHLESS']
        );
        $dataPointsType[] = array(
            "label" => "CS",
            "y" => $row['CS']
        );
		$dataPointsType[] = array(
            "label" => "DR",
            "y" => $row['DR']
        );
		$dataPointsType[] = array(
            "label" => "LSB",
            "y" => $row['LSB']
        );
		$dataPointsType[] = array(
            "label" => "DAM",
            "y" => $row['DAM']
        );
    }
    // Free up the result set
    $resulttype->free();

	// Move to the next result set
	$connection->next_result();

} else {
    echo "Error executing query4: " . $connection->error;
}
// Execute the fifth query 
$query5 = "CALL gab_maintenance()"; // query to retrieve nouveau_stock and recupere_stock
if ($resultmaintenance = $connection->query($query5)) {
    // Process the result set for the second chart
    $dataPointsMaintenance = array();
    while ($row = $resultmaintenance->fetch_assoc()) {
        $dataPointsMaintenance [] = array(
            "label" => "Sans Maintenance",
            "y" => $row['sans_maintenance']
        );
        $dataPointsMaintenance [] = array(
            "label" => "Avec Maintenance",
            "y" => $row['avec_maintenance']
        );
    }

    // Free up the result set
    $resultmaintenance->free();

	// Move to the next result set
	$connection->next_result();

} else {
    echo "Error executing query5: " . $connection->error;
}
// Execute the sixth query 
$query6 = "CALL commande_per_year()"; // query to retrieve total prix of commande for each year
if ($resultcommande = $connection->query($query6)) {
    // Process the result set for the second chart
    $dataPointscommande = array();
    while ($row = $resultcommande->fetch_assoc()) {
        $dataPointscommande [] = array(
			"label" => $row['year'], // Use the year as the label for the x-axis
            "y" => $row['prix_total'] // Use the prix_total as the value for the y-axis
        );
    }

    // Free up the result set
    $resultcommande->free();
		// Move to the next result set
		$connection->next_result();

} else {
    echo "Error executing query6: " . $connection->error;
}
// Execute the seventh query 
$query7 = "CALL stock_door_gab()"; // query to retrieve door type from stock
if ($resultdoor = $connection->query($query7)) {
    // Process the result set for the second chart
    $dataPointsDoor = array();
    while ($row = $resultdoor->fetch_assoc()) {
        $dataPointsDoor[] = array(
            "label" => "Indoor",
            "y" => $row['Indoor']
        );
        $dataPointsDoor[] = array(
            "label" => "Outdoor",
            "y" => $row['Outdoor']
        );
    }

    // Free up the result set
    $resultdoor->free();
} else {
    echo "Error executing query7: " . $connection->error;
}
// Close the database connection
$connection->close();
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Gestionnaire de GAB</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="assets/css/maintest.css" />
		<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	</head>
	<body>
	<script>
	window.onload = function () {
		var chart = new CanvasJS.Chart("chartContainer", {
			animationEnabled: true,
			title: {
				text: "Etat des GAB"
			},
			subtitles: [
                {
                    text: "Total GABs: <?php echo $totalGABs; ?> | Total Agences: <?php echo $totalAgences; ?>",
                    fontColor: "maroon",
                    fontSize: 18
                }
            ],

		
	data: [{
    type: "pie",
    yValueFormatString: "#,##0",
    indexLabel: "{y} ({label})",
    dataPoints:  <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
}]

});
		chart.render();

            var chartStock = new CanvasJS.Chart("chartContainerStock", {
                animationEnabled: true,
                title: {
                    text: "Etat du Stock GAB"
                },
                data: [
                    {
                        type: "doughnut",
                        yValueFormatString: "#,##0",
                        indexLabel: "{y} ({label})",
						innerRadius: "80%", // Set the inner radius for the doughnut (adjust as needed)
                        dataPoints: <?php echo json_encode($dataPointsStock, JSON_NUMERIC_CHECK); ?>
                    }
                ]
            });

			var chartDoor = new CanvasJS.Chart("chartContainerDoor", {
				backgroundColor: "transparent", // Set the background color to transparent
				colorSet: "colorSet2",
                animationEnabled: true,
                data: [
                    {
                        type: "doughnut",
                        yValueFormatString: "#,##0",
                        indexLabel: "{y} ({label})",
						indexLabelPlacement: "outside", // Set index label placement
            			indexLabelFontColor: "black", // Set index label font color
						indexLabelMaxWidth: 55, // Set the maximum width for index labels
						innerRadius: "70%", // Set the inner radius for the doughnut (adjust as needed)
                        dataPoints: <?php echo json_encode($dataPointsDoor, JSON_NUMERIC_CHECK); ?>
                    }
                ]
            });
            chartStock.render();
            chartDoor.render();

            var chartType = new CanvasJS.Chart("chartContainerType", {
            animationEnabled: true,
            title: {
                  text: "Type GAB"
             },
             data: [
                  {
                     type: "column",
                     yValueFormatString: "#,##0",
                     indexLabel: "{y} ({label})",
                    dataPoints: <?php echo json_encode($dataPointsType, JSON_NUMERIC_CHECK); ?>
                 }
             ]
            });
            chartType.render();

			var chartMaintenance = new CanvasJS.Chart("chartContainerMaintenance", {
                animationEnabled: true,
                title: {
                    text: "Maintenance GAB"
                },
                data: [
                    {
                        type: "doughnut",
                        yValueFormatString: "#,##0",
                        indexLabel: "{y} ({label})",
                        dataPoints: <?php echo json_encode($dataPointsMaintenance, JSON_NUMERIC_CHECK); ?>
                    }
                ]
            });
            chartMaintenance.render();

			var chartCommande = new CanvasJS.Chart("chartContainerCommande", {
			title: {
				text: "Total commande par année"
			},
			axisY: {
			title: "Total",
			minimum: 0 // Set the minimum value of the y-axis to 0
			},
			axisX: {
				title: "Année"
			},

			data: [{
				type: "line",
				dataPoints: <?php echo json_encode($dataPointscommande, JSON_NUMERIC_CHECK); ?>
			}]
		});
		chartCommande.render();
        }
    </script>
		<!-- Header -->
			<header id="header">
				<div class="inner">
					<a href="index.php" class="logo"><img src="images/logo.png"></a>
					<a href="#navPanel" class="navPanelToggle"><span class="fa fa-bars"></span></a>
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
		<!-- One -->
			<section id="one" class="wrapper">
				<div class="inner" style="margin-left: 290px;">
					<div class="flex flex-2">
						<article><div id="chartContainer" style="width: 380px; height: 380px;"></div>
						</article>
						<article><div id="chartContainerType" style="width: 380px; height: 380px;"></div></article>
					</div>
  </div>
</div>
				</div>
			</section>

		<!-- Two -->
				<section id="two" class="wrapper style1 special" style="display: flex; justify-content: space-between; flex-wrap: wrap; padding-left: 20px;padding-right: 15px;">
				<div class="box person" style="flex-grow: 1; width: 25%; margin: 10px;">
				<div id="chartContainerStock" style="width: 410px; height: 400px; padding-left: 25px; position: absolute;"></div>
				<div id="chartContainerDoor" style="width: 220px; height: 260px; padding-left: 25px; position: absolute; margin-top: 92px; margin-left: 95px;"></div>
			</div>

			<div class="box person" style="flex-grow: 1; width: 25%; margin: 10px;">
				<div id="chartContainerMaintenance" style="width: 410px;height: 400px;padding-left: 25px;"></div>
			</div>
			<div class="box person" style="flex-grow: 1; width: 25%; margin: 10px; padding-right: 20px;padding-left: 5px;">
				<div id="chartContainerCommande" style="width: 460px; height: 400px;"></div>
			</div>
		</section>

		<!-- Three -->
			<section id="three" class="wrapper special">
				<div class="inner">
					<header class="align-center">
						<h2>Nunc Dignissim</h2>
						<p>Aliquam erat volutpat nam dui </p>
					</header>
					<div class="flex flex-2">
						<article>
							<div class="image fit">
								<img src="images/pic01.jpg" alt="Pic 01" />
							</div>
							<header>
								<h3>Praesent placerat magna</h3>
							</header>
							<p>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor lorem ipsum.</p>
							<footer>
								<a href="#" class="button special">More</a>
							</footer>
						</article>
						<article>
							<div class="image fit">
								<img src="images/pic02.jpg" alt="Pic 02" />
							</div>
							<header>
								<h3>Fusce pellentesque tempus</h3>
							</header>
							<p>Sed adipiscing ornare risus. Morbi est est, blandit sit amet, sagittis vel, euismod vel, velit. Pellentesque egestas sem. Suspendisse commodo ullamcorper magna non comodo sodales tempus.</p>
							<footer>
								<a href="#" class="button special">More</a>
							</footer>
						</article>
					</div>
				</div>
			</section>

		<!-- Footer -->
			<footer id="footer">
				<div class="inner">
					<div class="flex">
						<ul class="icons">
							<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
							<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
							<li><a href="#" class="icon fa-linkedin"><span class="label">linkedIn</span></a></li>
							<li><a href="#" class="icon fa-pinterest-p"><span class="label">Pinterest</span></a></li>
							<li><a href="#" class="icon fa-vimeo"><span class="label">Vimeo</span></a></li>
						</ul>
					</div>
				</div>
			</footer>

		<div class="copyright">
			Site made with: <a href="https://templated.co/">TEMPLATED.CO</a>
		</div>
       <!-- <div id="navPanel" class>
						<a href="index.html" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Home</a>
						<a href="generic.html" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Generic</a>
						<a href="elements.html" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Elements</a>
					<a href="#navPanel" class="close" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></a></div>-->
		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>
