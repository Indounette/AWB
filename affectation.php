<?php
require_once "config.php";
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

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submitting"])) {
            if (empty($_POST["Code_agence"]) ||  empty($_POST["Code_gab"])) {
                echo "Error: Please fill in all the required fields.";
            } else {
    // Get the value of gab attributes from the form
        $code_agence = $_POST["Code_agence"];
        $code_gab = $_POST["Code_gab"];
     // Check if any of the fields contain empty strings
     $emptyFields = array($code_agence, $code_gab);
     if (in_array("", $emptyFields, true)) {
         echo "Error: Please fill in all required fields.";
     } else {
        $querycheck = "CALL show_affectation('$code_gab')";    
		if ($querycheck =1 ) { // if exists 
        $queryform = "CALL update_affectation('$code_agence', '$code_gab')";}    
    else {
    $queryform = "CALL create_affectation('$code_agence', '$code_gab')";}
    if ($connection->query($queryform) === TRUE) {
        // Data insertion successful
       header("Location: affectation.php");
        exit;
    } else {
        // Data insertion failed
        echo "Error: " . $sql . "<br>" . $connection->error;
    } }}   
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_affectation'])) {
    $delete_affectation = $_POST['delete_affectation']; 
    // Call the delete_agence procedure
    $query = "CALL delete_affectation('$delete_affectation')";
    $result = $connection->query($query);
    if (!$result) {
        echo "Error: " . $connection->error;
    } else {
        // Redirect back to the list page or display a success message
        header("Location: affectation.php");
        exit;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['Code_agence'])) {
    $code_agence = $_GET['Code_agence'];
    
    // Call the search procedure
    $query = "CALL search_affectation_bycode_agence('$code_agence')";
    $result = $connection->query($query);
    
    $search_results = array();
    
    while ($data = mysqli_fetch_array($result)) {
        $search_results[] = $data;
    }
    // Free the result set
    $result->free();

    // Return search results as JSON
    header('Content-Type: application/json');
    echo json_encode($search_results);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['Code_gab'])) {
    $code_gab = $_GET['Code_gab'];
    
    // Call the search procedure
    $query = "CALL search_affectation_bycode_gab('$code_gab')";
    $result = $connection->query($query);
    
    $search_results = array();
    
    while ($data = mysqli_fetch_array($result)) {
        $search_results[] = $data;
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
                <h2 class="title" style="text-align: center;">Formulaire Affectation</h2>
                    <form method="POST">
                        <div class="row row-space" style="margin-bottom: 25px; ">
                        <div class="col-2">
                            <input class="input--style-2" type="text" placeholder="Code GAB" name="Code_gab" value="<?php echo isset($_GET['edit']) ? htmlspecialchars($_GET['edit']) : ''; ?>" required>
                            <?php
                            if (isset($_GET['edit'])) {
                                $editValue = $_GET['edit'];
                            
                                // Call the stored procedure to populate other fields
                                $populateResult = $connection->query("CALL search_affectation_bycode_gab('$editValue')");
                            
                                if ($populateResult && $populateResult->num_rows > 0) {
                                    $data = $populateResult->fetch_assoc();
                                    // Populate other input fields using the returned data
                                    $Code_agence = $data['code_agence'];
                                }
                            }
                            ?>
                        </div>
                        <div class="col-2">
                            <div class="input-group">
                                <div class="rs-select2 js-select-simple select--no-search">
                                <select name="Code_agence" class="js-select2">
                                <option disabled="disabled" selected="selected">Code agence</option>
                                <?php
                                // Loop through the array of options and add each one to the dropdown list
                                foreach ($agence_options as $option) {
                                    // Check if $Code_agence variable is set and compare it with the current option
                                    $isSelected = isset($Code_agence) && $Code_agence === $option ? 'selected' : '';
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
                        <div class="p-t-30" style="padding-left: 200px;">
                        <button type="submit" name="submitting" class="btn btn--radius btn--orange">Ajouter \ Modifier</button> 
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
                <h2 class="title" style="text-align: center;">affectations</h2>
                <div class="container my-4">
                <header style="margin-left: 870px">
                <div class="col-2 float-right" style="display: inline-block; width: 30%;">
                    <input class="input--style-2" type="text" placeholder="Code agence" id="Code_agence">
                </div>
                <div class="col-2 float-right" style="display: inline-block; width: 30%;">
                    <input class="input--style-2" type="text" placeholder="Code GAB" id="Code_gab">
                </div>
                <div class="btn btn-search float-right" style="display: inline-block; margin-bottom: 6px; padding: 4px 10px;">
                    <a href="#" style="color: white; text-decoration: none;" id="searchButton">Search</a>
                </div>
            </header>
            <table class="popup-table"style="background-color: #fdf7f0;">
        <thead>
            <tr>
            <th>Code Agence</th>
            <th>Code GAB</th>
            <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $resultaffectation = $connection->query("CALL liste_affectation()");
        while ($data = mysqli_fetch_array($resultaffectation)) {
            $code_agence = $data['code_agence'];
            $code_gab = $data['code_gab']; // New attribute
            ?>
            <tr>
                <td><b><?php echo $code_agence; ?></b></td>
                <td><b><?php echo $code_gab; ?></b></td>

                <td >
                <div class="btn btn-edit" style="margin-bottom: 6px; height: 40px">
                <a href="affectation.php?edit=<?php echo urlencode($code_gab); ?>" style="color: white; text-decoration: none;">Edit</a>
                </div>
                <form method="post" action="affectation.php" style="display: inline;">
                    <input type="hidden" name="delete_affectation" value="<?php echo $code_gab; ?>">
                    <button type="submit" class="btn btn-edit">Delete</button>
                </form>
                </td>
            </tr>
            <?php
        }
        // Free the result set
        $resultaffectation->free();
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

        const codeAgence = document.getElementById("Code_agence").value;
        const codeGab = document.getElementById("Code_gab").value;

        if (codeAgence) {
            // Call the search procedure for Code agence and fetch results from server
            fetch("?Code_agence=" + codeAgence)
                .then(response => response.json())
                .then(data => displayResults(data))
                .catch(error => console.error("Error:", error));
        } else if (codeGab) {
            // Call the search procedure for Code GAB and fetch results from server
            fetch("?Code_gab=" + codeGab)
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
        headerRow.innerHTML = `<th>Code Agence</th>
                                <th>Code GAB</th>
                                <th>Action</th>`; // Added header for action
        table.appendChild(headerRow);

        // Loop through the results and create table rows
        results.forEach(result => {
            const row = document.createElement("tr");
            row.innerHTML = `<td><b>${result.code_agence}</b></td>
                            <td><b>${result.code_gab}</b></td>
                             <td style="min-width: 140px;">
                         <div class="btn btn-edit" style="margin-bottom: 6px;">
                         <a href="affectation.php?edit=${encodeURIComponent(result.code_gab)}" style="color: white; text-decoration: none;">Edit</a>
                         </div>
                         <form method="post" action="affectation.php" style="display: inline;">
                             <input type="hidden" name="delete_affectation" value="${result.code_gab}">
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
