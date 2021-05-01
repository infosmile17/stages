<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$id_etudiant = "";
$nom_pdf = "";
$date_demande = "";
$date_reponse = "";
$etat = "";

$id_etudiant_err = "";
$nom_pdf_err = "";
$date_demande_err = "";
$date_reponse_err = "";
$etat_err = "";


// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];

    $id_etudiant = trim($_POST["id_etudiant"]);
		$nom_pdf = trim($_POST["nom_pdf"]);
		$date_demande = trim($_POST["date_demande"]);
		$date_reponse = trim($_POST["date_reponse"]);
		$etat = trim($_POST["etat"]);
		

    // Prepare an update statement
    $dsn = "mysql:host=$db_server;dbname=$db_name;charset=utf8mb4";
    $options = [
        PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
    ];
    try {
        $pdo = new PDO($dsn, $db_user, $db_password, $options);
    } catch (Exception $e) {
        error_log($e->getMessage());
        exit('Something weird happened');
    }

    $vars = parse_columns('demande_stage', $_POST);
    $stmt = $pdo->prepare("UPDATE demande_stage SET id_etudiant=?,nom_pdf=?,date_demande=?,date_reponse=?,etat=? WHERE id=?");

    if(!$stmt->execute([ $id_etudiant,$nom_pdf,$date_demande,$date_reponse,$etat,$id  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: demande_stage-read.php?id=$id");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["id"] = trim($_GET["id"]);
    if(isset($_GET["id"]) && !empty($_GET["id"])){
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM demande_stage WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $id;

            // Bind variables to the prepared statement as parameters
			if (is_int($param_id)) $__vartype = "i";
			elseif (is_string($param_id)) $__vartype = "s";
			elseif (is_numeric($param_id)) $__vartype = "d";
			else $__vartype = "b"; // blob
			mysqli_stmt_bind_param($stmt, $__vartype, $param_id);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $id_etudiant = $row["id_etudiant"];
					$nom_pdf = $row["nom_pdf"];
					$date_demande = $row["date_demande"];
					$date_reponse = $row["date_reponse"];
					$etat = $row["etat"];
					

                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.<br>".$stmt->error;
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="page-header">
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                        <div class="form-group">
                                <label>id_etudiant</label>
                                <input type="number" name="id_etudiant" class="form-control" value="<?php echo $id_etudiant; ?>">
                                <span class="form-text"><?php echo $id_etudiant_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>nom_pdf</label>
                                <input type="text" name="nom_pdf" maxlength="250"class="form-control" value="<?php echo $nom_pdf; ?>">
                                <span class="form-text"><?php echo $nom_pdf_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>date_demande</label>
                                <input type="text" name="date_demande" class="form-control" value="<?php echo $date_demande; ?>">
                                <span class="form-text"><?php echo $date_demande_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>date_reponse</label>
                                <input type="text" name="date_reponse" class="form-control" value="<?php echo $date_reponse; ?>">
                                <span class="form-text"><?php echo $date_reponse_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>etat</label>
                                <input type="number" name="etat" class="form-control" value="<?php echo $etat; ?>">
                                <span class="form-text"><?php echo $etat_err; ?></span>
                            </div>

                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="demande_stage-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
