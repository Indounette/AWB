<?php
require_once "config.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_bon_commande'])) {
    $delete_bon_commande = $_POST['delete_bon_commande']; 
        // Call the delete_commande procedure
        $query = "CALL delete_commande('$delete_bon_commande')";
        $result = $connection->query($query);
        if (!$result) {
            echo "Error: " . $connection->error;
        } else {
            // Redirect back to the list page or display a success message
            header("Location: acommande.php");
            exit;
        }
        $connection->close();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['Bon_commande'])) {
        $bon_commande = $_GET['Bon_commande'];
    
        // Call the search procedure
        $query1 = "CALL search_commande_by_bon('$bon_commande')";
        $result = $connection->query($query1);
    
        $search_results = array();
    
        while ($data = mysqli_fetch_array($result)) {
            $search_results[] = $data;
        }
        // Return search results as JSON
        header('Content-Type: application/json');
        echo json_encode($search_results);
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['Année'])) {
        $annee = $_GET['Année'];
        
        // Call the search procedure for Année
        $query2 = "CALL search_commande_by_annee('$annee')"; // Update the stored procedure name
        $result = $connection->query($query2);
        
        $search_results = array();
        
        while ($data = mysqli_fetch_array($result)) {
            $search_results[] = $data;
        }
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
            <div class="card card-2">
                <h2 class="title" style="text-align: center;">Commandes</h2>
                <div class="container my-4">
                <header style="margin-left: 800px">
                <div class="col-2 float-right" style="display: inline-block; width: 30%;">
                    <input class="input--style-2" type="text" placeholder="Bon Commande" id="Bon_commande">
                </div>
                <div class="col-2 float-right" style="display: inline-block; width: 30%;">
                    <input class="input--style-2" type="text" placeholder="Année" id="Annee_commande">
                </div>
                <div class="btn btn-search float-right" style="display: inline-block; margin-bottom: 6px; padding: 4px 10px;">
                    <a href="#" style="color: white; text-decoration: none;" id="searchButton">Search</a>
                </div>
            </header>
            <table class="popup-table"style="background-color: #fdf7f0;">
        <thead>
            <tr>
            <th>Bon Commande</th>
            <th>Annee Adjucation</th>
            <th>Date Commande</th>
            <th>Nature Commande</th>
            <th>Modele</th>
            <th>Quantite</th>
            <th>Prix Unitaire</th>
            <th>Commentaire</th>
            <th>Taux Maintenance</th>
            <th>Date Livraison</th>
            <th>Module</th>
            <th>Action</th>
            </tr>
        </thead>
        <tbody>
        
        <?php
        $result = $connection->query("CALL list_commande()");
        while ($data = mysqli_fetch_array($result)) {
            $bon_commande = $data['bon_commande'];
            $annee_adjucation = $data['annee_adjucation'];
            $date_commande = $data['date_commande'];
            $nature_commande = $data['nature_commande'];
            $modele = $data['modele'];
            $quantite = $data['quantite'];
            $prix_unitaire = $data['prix_unitaire'];
            $commentaire = $data['commentaire'];
            $taux_maintenance = $data['taux_maintenance'];
            $date_livraison = $data['date_livraison'];
            $module = $data['module'];
             ?>
            <tr>
            <td><b><?php echo $bon_commande; ?></b></td>
            <td><b><?php echo $annee_adjucation; ?></b></td>
            <td><b><?php echo $date_commande; ?></b></td>
            <td><b><?php echo $nature_commande; ?></b></td>
            <td><b><?php echo $modele; ?></b></td>
            <td><b><?php echo $quantite; ?></b></td>
            <td><b><?php echo $prix_unitaire; ?></b></td>
            <td><b><?php echo $commentaire; ?></b></td>
            <td><b><?php echo $taux_maintenance; ?></b></td>
            <td><b><?php echo $date_livraison; ?></b></td>
            <td><b><?php echo $module; ?></b></td>
            
                <td style="min-width: 140px;">
                <div class="btn btn-edit" style="margin-bottom: 6px; height: 40px">
                    <a href="commande.php?edit=<?php echo urlencode($bon_commande); ?>" style="color: white; text-decoration: none;">Edit</a>
                </div>
                <form method="post" action="acommande.php" style="display: inline;">
                    <input type="hidden" name="delete_bon_commande" value="<?php echo $bon_commande; ?>">
                    <button type="submit" class="btn btn-edit">Delete</button>
                </form>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
        </table>
    </div></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
    const searchButton = document.getElementById("searchButton");

    searchButton.addEventListener("click", (event) => {
        event.preventDefault(); // Prevent the default form submission

        const bonCommande = document.getElementById("Bon_commande").value;
        const anneeCommande = document.getElementById("Annee_commande").value;

        if (bonCommande) {
            // Call the search procedure for Bon_commande and fetch results from server
            fetch("?Bon_commande=" + bonCommande)
                .then(response => response.json())
                .then(data => displayResults(data))
                .catch(error => console.error("Error:", error));
        } else if (anneeCommande) {
            // Call the search procedure for Année and fetch results from server
            fetch("?Année=" + anneeCommande)
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
        headerRow.innerHTML = `<th>Bon Commande</th>
                                <th>Année Adjucation</th>
                                <th>Date Commande</th>
                                <th>Nature Commande</th>
                                <th>Modèle</th>
                                <th>Quantité</th>
                                <th>Prix Unitaire</th>
                                <th>Commentaire</th>
                                <th>Taux Maintenance</th>
                                <th>Date Livraison</th>
                                <th>Module</th>
                                <th>Action</th>`; // Added header for action
        table.appendChild(headerRow);

        // Loop through the results and create table rows
        results.forEach(result => {
            const row = document.createElement("tr");
            row.innerHTML = `<td><b>${result.bon_commande}</b></td>
                             <td><b>${result.annee_adjucation}</b></td>
                             <td><b>${result.date_commande}</b></td>
                             <td><b>${result.nature_commande}</b></td>
                             <td><b>${result.modele}</b></td>
                             <td><b>${result.quantite}</b></td>
                             <td><b>${result.prix_unitaire}</b></td>
                             <td><b>${result.commentaire}</b></td>
                             <td><b>${result.taux_maintenance}</b></td>
                             <td><b>${result.date_livraison}</b></td>
                             <td><b>${result.module}</b></td>
                             <td style="min-width: 140px;">
                         <div class="btn btn-edit" style="margin-bottom: 6px;">
                         <a href="commande.php?edit=${encodeURIComponent(result.bon_commande)}" style="color: white; text-decoration: none;">Edit</a>
                         </div>
                         <form method="post" action="acommande.php" style="display: inline;">
                             <input type="hidden" name="delete_bon_commande" value="${result.bon_commande}">
                             <button type="submit" class="btn btn-edit">Delete</button>
                         </form>
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
</body>

</html>
