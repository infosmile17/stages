<?php
include_once('header.php');
include_once('navbar.php');
// Check existence of id parameter before processing further
$_GET["id"] = trim($_GET["id"]);
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    // Include config file
    require_once "config.php";
    require_once "helpers.php";

    // Prepare a select statement
    $sql = "SELECT * FROM actualite WHERE id = ?";

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
            <div class="col-md-2 mx-auto"></div>
            <div class="col-md-8 mx-auto">
                <div class="page-header">
                    <h1>Détails actualitée</h1>
                </div>
                <div class="card">
                    <div class="header">
                        <h1><?php echo $row["titre"]; ?></h1>
                    </div>

                    <div class="description">
                        <p><?php echo $row["description"]; ?></p>
                    </div>
                    <div class="footer">
                        <?php echo $row["date"]; ?>
                    </div>
                </div>
                <br>
                <p><a href="actualite-index.php" class="btn btn-primary">Listes des actualités</a></p>
            </div>
        </div>
    </div>
</section>
<style>
    .card {
        width: 250px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        text-align: center;
        width: 100%;
    }

    .card .header {
        background-color: #4CAF50;
        color: white;
        padding: 10px;
        font-size: 40px;

    }

    .card .footer {
        background-color: #4CAF50;
        color: white;
        padding: 3px;
        font-size: 22px;
    }

    .card .description {
        padding: 10px;
        font-size: 22px;
    }
</style>
<?php include_once('footer.php'); ?>