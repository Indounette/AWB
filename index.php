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
            "label" => "Stock Recupere",
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
            "label" => "In-Site",
            "y" => $row['in_site']
        );
        $dataPointsType[] = array(
            "label" => "Hors-Site",
            "y" => $row['hors_site']
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

} else {
    echo "Error executing query6: " . $connection->error;
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
		<link rel="stylesheet" href="assets/css/main.css" />
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
                        dataPoints: <?php echo json_encode($dataPointsStock, JSON_NUMERIC_CHECK); ?>
                    }
                ]
            });
            chartStock.render();

            var chartStock = new CanvasJS.Chart("chartContainerType", {
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
            chartStock.render();
			var chartStock = new CanvasJS.Chart("chartContainerMaintenance", {
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
            chartStock.render();

			var chart = new CanvasJS.Chart("chartContainerCommande", {
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
		chart.render();
        }
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
					<a href="commande.php"><b>Commandes GAB</b></a>
					<a href="generic.html"><b>Agence</b></a>
					<a href="index.html"><b>GAB</b></a>
					<a href="generic.html"><b>Piece de rechange</b></a>
					<a href="index.html"><b>Paramétrage</b></a>
				</nav>
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
				<div id="chartContainerStock" style="width: 410px;height: 400px;padding-left: 25px;"></div>
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

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>
