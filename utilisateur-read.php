<?php
// Check existence of id parameter before processing further

include_once('header.php');
include_once('navbar.php');

$_GET["id"] = trim($_GET["id"]);
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    // Include config file
    require_once "config.php";
    require_once "helpers.php";

    // Prepare a select statement
    $sql = "SELECT * FROM utilisateur WHERE id = ?";

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
<section class="pt-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="page-header">
                    <h1>Details : <?php echo $row["nom"] . ' ' . $row["prenom"]; ?></h1>
                </div>

                <div class="form-group">
                    <label>Téléphone</label>
                    <p class="form-control-static"><?php echo $row["tel"]; ?></p>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <p class="form-control-static"><?php echo $row["email"]; ?></p>
                </div>
                <div class="form-group">
                    <label>Adresse</label>
                    <p class="form-control-static"><?php echo $row["adresse"]; ?></p>
                </div>
                <div class="form-group">
                    <label>CIN</label>
                    <p class="form-control-static"><?php echo $row["cin"]; ?></p>
                </div>
                <div class="form-group">
                    <label>Login</label>
                    <p class="form-control-static"><?php echo $row["login"]; ?></p>
                </div>
                <div class="form-group">
                    <label>Approuver</label>
                    <p class="form-control-static"><?php

                                                    $approuver = 'approuver';
                                                    if ($row['approuver'] == '0') {
                                                        $approuver = 'non approuver';
                                                    }

                                                    echo $approuver; ?></p>
                </div>
                <div class="form-group">
                    <label>type</label>
                    <p class="form-control-static"><?php
                                                    switch ($row['type']) {
                                                        case "1":
                                                            echo "Etudiant";
                                                            break;
                                                        case "2":
                                                            echo "Enseignant";
                                                            break;
                                                        case "3":
                                                            echo "Admin";
                                                            break;
                                                    } ?></p>
                </div>

                <p><a href="utilisateur-index.php" class="btn btn-primary">Listes des utilisateurs</a></p>
            </div>
        </div>
    </div>
</section>

</body>

</html>