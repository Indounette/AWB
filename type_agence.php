<?php
require_once "config.php";

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
            if (empty($_POST["Type_agence"])) {
                echo "Error: Please fill in all the required fields.";
            } else {
    // Get the value of gab attributes from the form
    $Type_agence = $_POST["Type_agence"];
    $libelle = $_POST["Libelle"];
     // Check if any of the fields contain empty strings
     $emptyFields = array($Type_agence);
     if (in_array("", $emptyFields, true)) {
         echo "Error: Please fill in all required fields.";
     } else {
        // Check the count using a separate query
        $querycheck = $connection->query("CALL querychecktype_agence('$Type_agence')");

        if ($querycheck) {
            $row = $querycheck->fetch_row();
            $count = $row[0]; // The result of COUNT(*) will be in the first column of the row
            $connection->next_result();
        } 
        if ($count == 1) {
            $queryform = "CALL update_type_agence('$Type_agence', '$libelle')";
        } else {
    $queryform = "CALL create_type_agence('$Type_agence', '$libelle')";}
    if ($connection->query($queryform) === TRUE) {
        // Data insertion successful
       header("Location: type_agence.php");
        exit;
    } else {
        // Data insertion failed
        echo "Error: " . $sql . "<br>" . $connection->error;
    } }}   
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_type_agence'])) {
    $delete_type_agence = $_POST['delete_type_agence']; 
    // Call the delete_type_agence procedure
    $query = "CALL delete_type_agence('$delete_type_agence')";
    $result = $connection->query($query);
    if (!$result) {
        echo "Error: " . $connection->error;
    } else {
        // Redirect back to the list page or display a success message
        header("Location: type_agence.php");
        exit;
    }
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
        .labelform {
            text-shadow: 0 0 5px #ffd6ad;
            color: #b7243f;
            margin-left: 10px;
            font-size: 15px;
            margin-bottom: 5px;
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
            <div class="card card-2" style="margin-bottom: 25px;">
                <h2 class="title" style="text-align: center;">Formulaire Type Agence</h2>
                    <form method="POST">
                        <div class="row row-space" style="margin-bottom: 25px; ">
                        <div class="col-2">
                        <label class="labelform" for="Type_agence">Type agence</label>
                        <input class="input--style-2" type="text" placeholder="Type agence" name="Type_agence" value="<?php echo isset($_GET['edit']) ? htmlspecialchars($_GET['edit']) : ''; ?>" required>
                        <?php
                            if (isset($_GET['edit'])) {
                        $editValue = $_GET['edit'];
                        
                        // Call the stored procedure to populate other fields
                        $populateResult = $connection->query("CALL details_type_agence('$editValue')");
                        
                        if ($populateResult && $populateResult->num_rows > 0) {
                            $data = $populateResult->fetch_assoc();
                            // Populate other input fields using the returned data
                            $libelle = $data['libelle'];
                            // Free the result after executing the stored procedure
                            $connection->next_result();
                        }}?>
                    </div>
                        <div class="col-2">
                            <label class="labelform" for="Motif">Libelle</label>
                            <input class="input--style-2" type="text" placeholder="Libelle" name="Libelle" value="<?php echo isset($libelle) ? htmlspecialchars($libelle) : ''; ?>">
                        </div></div>
                        <div class="row row-space" style="margin-bottom: 25px;">
                        <div class="col-2">
                        <div class="p-t-30"style="padding-left: 18px;">
                            <button class="btn btn--radius btn--orange" type="submit" name="submit">Ajouter \ Modifier</button>
                        </div>
                        </div></div>
                        </div>
                        <div class="wrapper wrapper--w960">
        <sectio id="two" class="wrapper style1 special" style="display: flex; justify-content: space-between; flex-wrap: wrap; padding-left: 100px;padding-right: 100px;">
            <div class="card card-2" style="width : 1100px;">
                <h2 class="title" style="text-align: center;">Types Agence</h2>
                <div class="container my-4">
            <table class="popup-table"style="background-color: #fdf7f0;">
        <thead>
            <tr>
            <th>Type agence</th>
            <th>Libelle</th>
            <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $resulttype = $connection->query("CALL liste_type_agence()");
        while ($data = mysqli_fetch_array($resulttype)) {
            $Type_agence = $data['type_agence'];
            $libelle = $data['libelle'];
            ?>
            <tr>
                <td><b><?php echo $Type_agence; ?></b></td>
                <td><b><?php echo $libelle; ?></b></td>
                <td>
                    <div class="btn btn-edit" style="margin-bottom: 6px; height: 40px">
                        <a href="type_agence.php?edit=<?php echo urlencode($Type_agence); ?>" style="color: white; text-decoration: none;">Edit</a>
                    </div>
                    <form method="post" action="type_agence.php" style="display: inline;">
                    <input type="hidden" name="delete_type_agence" value="<?php echo $Type_agence; ?>">
                    <button type="submit" class="btn btn-delete">Delete</button>
                </form>
                </td>
            </tr>
            <?php
        }
        // Free the result set
        $resulttype->free();
        ?>
        </tbody>
        </table>
    </div></div>
                        </div>
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
