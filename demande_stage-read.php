<?php
// Check existence of id parameter before processing further
$_GET["id"] = trim($_GET["id"]);
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    // Include config file
    require_once "config.php";
    require_once "helpers.php";

    // Prepare a select statement
    $sql = "SELECT * FROM demande_stage WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Set parameters
        $param_id = trim($_GET["id"]);

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
            } else {
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.<br>" . $stmt->error;
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
} else {
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Afficher enregistrement</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>

<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="page-header">
                        <h1>Afficher enregistrement</h1>
                    </div>

                    <div class="form-group">
                        <label>id_etudiant</label>
                        <p class="form-control-static"><?php echo $row["id_etudiant"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>nom_pdf</label>
                        <p class="form-control-static"><?php echo $row["nom_pdf"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>date_demande</label>
                        <p class="form-control-static"><?php echo $row["date_demande"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>date_reponse</label>
                        <p class="form-control-static"><?php echo $row["date_reponse"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>etat</label>
                        <p class="form-control-static"><?php echo $row["etat"]; ?></p>
                    </div>

                    <p><a href="demande_stage-index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>
        </div>
    </section>

</body>

</html>