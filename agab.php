<?php
require_once "config.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_gab'])) {
    $delete_gab = $_POST['delete_gab']; 
    // Call the delete_commande procedure
    $query = "CALL delete_gab('$delete_gab')";
    $result = $connection->query($query);
    if (!$result) {
        echo "Error: " . $connection->error;
    } else {
        // Redirect back to the list page or display a success message
        header("Location: agab.php");
        exit;
    }
    $connection->close();
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['G_serial'])) {
    $g_serial = $_GET['G_serial'];

    // Call the search procedure for G_serial
    $query = "CALL Select_Gab_ByG_Serial('$g_serial')";
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
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['Fournisseur'])) {
    $fournisseur = $_GET['Fournisseur'];

    // Call the search procedure for Fournisseur
    $query = "CALL Select_Gab_ByFournisseur('$fournisseur')";
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

    // Call the search procedure for Statut
    $query = "CALL Select_Gab_ByStatut('$statut')";
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['affect_gab'])) {
    $affect_gab = $_POST['affect_gab'];
    $selectedStatut = $_POST['selectedStatut'];

    // Call the affecter_statut procedure
    $query = "CALL 	affecter_statut('$affect_gab', '$selectedStatut')";
    $result = $connection->query($query);

    if (!$result) {
        echo "Error: " . $connection->error;
    } else {
        // Redirect back to the list page or display a success message
        header("Location: agab.php");
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
                <h2 class="title" style="text-align: center;">Gabs</h2>
                <div class="container my-4">
                <header style="margin-left: 500px">
                <div class="col-2 float-right" style="display: inline-block; width: 30%;">
                    <input class="input--style-2" type="text" placeholder="G Serial" id="G_serial">
                </div>
                <div class="col-2 float-right" style="display: inline-block; width: 30%;">
                    <input class="input--style-2" type="text" placeholder="Fournisseur" id="Fournisseur">
                </div>
                <div class="col-2 float-right" style="display: inline-block; width: 30%;">
                    <input class="input--style-2" type="text" placeholder="Statut" id="Statut">
                </div>
                <div class="btn btn-search float-right" style="display: inline-block; margin-bottom: 6px; padding: 4px 10px;">
                    <a href="#" style="color: white; text-decoration: none;" id="searchButton">Search</a>
                </div>
            </header>

            <table class="popup-table"style="background-color: #fdf7f0;" style="width: 940px;">
        <thead>
            <tr>
                                    <th>GAB Serial</th>
                                    <th>Fournisseur</th>
                                    <th>Statut</th>
                                    <th>Module</th>
                                    <th>Modèle</th>
                                    <th>Action</th>
            </tr>
        </thead>
        <tbody>
        
        <?php
        $result = $connection->query("CALL liste_gab()");
        while ($data = mysqli_fetch_array($result)) {
            $gab_serial = $data['g_serial'];
            $fournisseur = $data['fournisseur'];
            $statut = $data['statut'];
            $module_gab = $data['module'];
            $modele_gab = $data['modele'];
            ?>
            <tr>
                <td><b><?php echo $gab_serial; ?></b></td>
                <td><b><?php echo $fournisseur; ?></b></td>
                <td>
                <div class="rs-select2 js-select-simple select--no-search">
                    <select name="selectedStatut" id="selectedStatut">
                        <option disabled="disabled" selected="selected">Statut</option>
                        <option value="stock" <?php if ($statut === 'stock') echo 'selected'; ?>>stock</option>
                        <option value="suspendu" <?php if ($statut === 'suspendu') echo 'selected'; ?>>suspendu</option>
                        <option value="actif" <?php if ($statut === 'actif') echo 'selected'; ?>>actif</option>
                        <option value="cesse" <?php if ($statut === 'cesse') echo 'selected'; ?>>cesse</option>
                    </select>
                    <div class="select-dropdown"></div>
        </div>
                </td>
                <td><b><?php echo $module_gab; ?></b></td>
                <td><b><?php echo $modele_gab; ?></b></td>
                <td>
                <div class="btn btn-edit" style="margin-bottom: 6px; height: 40px">
                <a href="gab.php?edit=<?php echo urlencode($gab_serial); ?>" style="color: white; text-decoration: none;">Edit</a>
                </div>
                <form method="post" action="agab.php" style="display: inline;">
                    <input type="hidden" name="delete_gab" value="<?php echo $gab_serial; ?>">
                    <button type="submit" class="btn btn-delete">Delete</button>
                </form>
                <form method="post" action="agab.php" style="display: inline;">
                <input type="hidden" name="affect_gab" value="<?php echo $gab_serial; ?>">
                <input type="hidden" name="selectedStatut" id="hiddenSelectedStatut" value="<?php echo $statut; ?>">
                <script type="text/javascript">
    // Get references to the select element and the hidden input element
    var statutSelect = document.getElementById("selectedStatut");
    var hiddenSelectedStatut = document.getElementById("hiddenSelectedStatut");

    // Listen for changes to the select element's value
    statutSelect.addEventListener("change", function () {
        // Update the value of the hidden input with the selected option's value
        hiddenSelectedStatut.value = statutSelect.value;
    });
</script>
                    <button type="submit" class="btn btn-affect">Affect</button>
                </form>
                </td>
            </tr>
            <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get references to the select element and the hidden input element
        var statutSelect = document.getElementById("selectedStatut");
        var selectedStatutHidden = document.getElementById("selectedStatutHidden");

        // Listen for changes to the select element's value
        statutSelect.addEventListener("change", function () {
            // Update the value of the hidden input with the selected option's value
            selectedStatutHidden.value = statutSelect.value;
        });
    });
</script>
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

        const gSerial = document.getElementById("G_serial").value;
        const fournisseur = document.getElementById("Fournisseur").value;
        const statut = document.getElementById("Statut").value;

        if (gSerial) {
            // Call the search procedure for G_serial and fetch results from server
            fetch("?G_serial=" + gSerial)
                .then(response => response.json())
                .then(data => displayResults(data))
                .catch(error => console.error("Error:", error));
        } else if (fournisseur) {
            // Call the search procedure for Fournisseur and fetch results from server
            fetch("?Fournisseur=" + fournisseur)
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
                            <th>Fournisseur</th>
                            <th>Module</th>
                            <th>Modèle</th>
                            <th>Statut</th>
                            <th>Action</th>`; // Added header for action
    table.appendChild(headerRow);

    // Loop through the results and create table rows
    results.forEach(result => {
        const row = document.createElement("tr");
        row.innerHTML = `<td><b>${result.g_serial}</b></td>
                         <td><b>${result.fournisseur}</b></td>
                         <td><b>${result.module}</b></td>
                         <td><b>${result.modele}</b></td>
                         <td></td>`;
                         
        // Create a dropdown list for Statut within the last cell
        const statutDropdown = document.createElement("select");
        statutDropdown.id = "popupStatutDropdown";
        statutDropdown.style.width="350px;"
        // Add options to the dropdown
        const statutOptions = [result.statut, "actif", "stock", "suspendu", "cesse"]; // Add more options if needed
        statutOptions.forEach(option => {
            const statutOption = document.createElement("option");
            statutOption.value = option;
            statutOption.textContent = option;

            statutDropdown.appendChild(statutOption);
        });

        // Append the dropdown to the cell
        row.cells[4].appendChild(statutDropdown);

          
    // Append the dropdown to the cell
    row.cells[4].appendChild(statutDropdown);

// Create a <td> element for the action buttons
const actionTd = document.createElement("td");
actionTd.style.width = "220px"; // Set the width for the action cell


// Create the "Edit" button
const editButton = document.createElement("div");
    editButton.className = "btn btn-edit";
    editButton.style.marginBottom = "6px";
    editButton.style.marginRight = "7px"; // Add margin-right
    editButton.style.height = "40px"; // Add height
    const editLink = document.createElement("a");
    editLink.href = `gab.php?edit=${encodeURIComponent(result.g_serial)}`;
    editLink.style.color = "white";
    editLink.style.textDecoration = "none";
    editLink.textContent = "Edit";
    editButton.appendChild(editLink);
    actionTd.appendChild(editButton);

    // Create the "Delete" button
    const deleteForm = document.createElement("form");
    deleteForm.method = "post";
    deleteForm.action = "agab.php";
    deleteForm.style.display = "inline";
    const deleteInput = document.createElement("input");
    deleteInput.type = "hidden";
    deleteInput.name = "delete_gab";
    deleteInput.value = result.g_serial;
    const deleteButton = document.createElement("button");
    deleteButton.type = "submit";
    deleteButton.className = "btn btn-delete";
    deleteButton.textContent = "Delete";
    deleteButton.style.marginRight = "7px"; // Add margin-right
    deleteForm.appendChild(deleteInput);
    deleteForm.appendChild(deleteButton);
    actionTd.appendChild(deleteForm);

    // Create the "Affect" button
    const affectForm = document.createElement("form");
    affectForm.method = "post";
    affectForm.action = "agab.php";
    affectForm.style.display = "inline";
    const gabSerialInput = document.createElement("input");
    gabSerialInput.type = "hidden";
    gabSerialInput.name = "affect_gab";
    gabSerialInput.value = result.g_serial;
    const affectButton = document.createElement("button");
    affectButton.textContent = "Affect";
    affectButton.classList.add("btn-affect");
    affectButton.style.marginRight = "7px"; // Add margin-right
    affectButton.addEventListener("click", () => {
        const selectedStatut = statutDropdown.value;
        const selectedStatutInput = document.createElement("input");
        selectedStatutInput.type = "hidden";
        selectedStatutInput.name = "selectedStatut";
        selectedStatutInput.value = selectedStatut;

    affectForm.appendChild(selectedStatutInput);
    affectForm.submit();
});
affectForm.appendChild(gabSerialInput);
affectForm.appendChild(affectButton);
actionTd.appendChild(affectForm);

// Append the action <td> to the row
row.appendChild(actionTd);

// Append the row to the table
table.appendChild(row);
});

// Append the table to the popup container
popupContainer.appendChild(table);

// Add the close button to the popup container
popupContainer.appendChild(closeButton);

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
