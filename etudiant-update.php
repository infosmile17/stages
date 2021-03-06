<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$id_etudiant = "";
$niveau = "";

$id_etudiant_err = "";
$niveau_err = "";


// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

    $id_etudiant = trim($_POST["id_etudiant"]);
    $niveau = trim($_POST["niveau"]);


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

    $vars = parse_columns('etudiant', $_POST);
    $stmt = $pdo->prepare("UPDATE etudiant SET niveau=? WHERE id=?");

    if (!$stmt->execute([$niveau, $id])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: etudiant-read.php?id=$id");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["id"] = trim($_GET["id"]);
    if (isset($_GET["id"]) && !empty($_GET["id"])) {
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM etudiant WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $id;

            // Bind variables to the prepared statement as parameters
            if (is_int($param_id)) $__vartype = "i";
            elseif (is_string($param_id)) $__vartype = "s";
            elseif (is_numeric($param_id)) $__vartype = "d";
            else $__vartype = "b"; // blob
            mysqli_stmt_bind_param($stmt, $__vartype, $param_id);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $id_etudiant = $row["id_etudiant"];
                    // Retrieve individual field value
                    $niveau = $row["niveau"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.<br>" . $stmt->error;
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
include_once('function.php');
$nomm = getEtudiantName($link, $id_etudiant);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mettre ?? jour enregistrement</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>

<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="page-header">
                        <h2>Mettre ?? jour enregistrement</h2>
                    </div>
                    <p>Veuillez modifier les valeurs d'entr??e et soumettre pour mettre ?? jour l'enregistrement.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                        <div class="form-group">
                            <label>Etudiant</label>
                            <input type="text" readonly name="id_etudiant" class="form-control" value="<?php echo $nomm; ?>">
                            <span class="form-text"><?php echo $id_etudiant_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>niveau</label>
                            <input type="radio" id="_1ere" name="niveau" value="1" <?php if ($niveau == 1) echo 'checked'; ?>>
                            <label for="_1ere">1 ere </label>
                            <input type="radio" id="_2eme" name="niveau" value="2" <?php if ($niveau == 2) echo 'checked'; ?>>
                            <label for="_2eme">2 eme</label>
                            <input type="radio" id="_3eme" name="niveau" value="3" <?php if ($niveau == 3) echo 'checked'; ?>>
                            <label for="_3eme">3 eme</label>
                            <input type="radio" id="_4eme" name="niveau" value="4" <?php if ($niveau == 4) echo 'checked'; ?>>
                            <label for="_4eme">4 eme</label>
                            <input type="radio" id="_5eme" name="niveau" value="5" <?php if ($niveau == 5) echo 'checked'; ?>>
                            <label for="_5eme">5 eme</label>
                            <span class="form-text"><?php echo $niveau_err; ?></span>
                        </div>

                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="submit" class="btn btn-primary" value="Envoyer">
                        <a href="etudiant-index.php" class="btn btn-secondary">Annuler</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>

</html>