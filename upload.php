<?php
require_once "config.php";

// Retrieve the JSON object from the request payload
$data = json_decode(file_get_contents('php://input'), true);
var_dump($_FILES['fileInput']);
// Check if the data is not empty
if (!empty($data)) {
    // Loop through each row in the JSON object and call the stored procedure
    foreach ($data as $row) {
        // Get the values from the row
        $bon_commande = $row["Bon_commande"];
        $numero_contrat = $row["Numero_de_contrat"];
        $annee_adjucation = date('Y-m-d', strtotime($row["Année_adjucation"]));
        $date_commande = date('Y-m-d', strtotime($row["Date_de_commande"]));
        $nature_commande = $row["nature_commande"];
        $modele = $row["modele"];
        $quantite = $row["quantite"];
        $commentaire = $row["commentaire"];
        $taux = $row["taux"];
        $date_livraison = date('Y-m-d', strtotime($row["Date_de_livraison"]));
        $module = $row["module"];

        // Use var_dump to check the values
        var_dump($bon_commande, $numero_contrat, $annee_adjucation, $date_commande, $nature_commande, $modele, $quantite, $commentaire, $taux, $date_livraison, $module);
        
        // Add this line to print the contents of the uploaded file
        var_dump($_FILES['fileInput']);

        // Add this line to print the temporary file path of the uploaded file
        var_dump($_FILES['fileInput']['tmp_name']);
        // Check if any of the fields contain empty strings
        $emptyFields = array($bon_commande, $numero_contrat, $annee_adjucation, $date_commande, $date_livraison, $module);
        if (in_array("", $emptyFields, true)) {
            echo "Error: Please fill in all required fields.";
        } else {
            // Call the stored procedure to insert the row into the database
            $queryexcel = "CALL create_commande('$bon_commande', '$numero_contrat', '$annee_adjucation', '$date_commande', '$nature_commande', '$modele', '$quantite', '$commentaire', '$taux', '$date_livraison', '$module')";
            if ($connection->query($queryexcel) === TRUE) {
                // Data insertion successful
                echo "Data inserted successfully";
            } else {
                // Data insertion failed
                echo "Error: " . $queryexcel . "<br>" . $connection->error;
            }
        }
    }
} else {
    echo "Error: No data received.";
}

// Close the database connection
$connection->close();
?>
