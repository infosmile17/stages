<?php
include_once('header.php');
include_once('navbar.php');
// Process delete operation after confirmation
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Include config file
    require_once "config.php";
    require_once "helpers.php";

    // Prepare a delete statement
    $sql = "DELETE FROM actualite WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Set parameters
        $param_id = trim($_POST["id"]);

        // Bind variables to the prepared statement as parameters
        if (is_int($param_id)) $__vartype = "i";
        elseif (is_string($param_id)) $__vartype = "s";
        elseif (is_numeric($param_id)) $__vartype = "d";
        else $__vartype = "b"; // blob
        mysqli_stmt_bind_param($stmt, $__vartype, $param_id);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Records deleted successfully. Redirect to landing page
            header("location: actualite-index.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.<br>" . $stmt->error;
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
} else {
    // Check existence of id parameter
    $_GET["id"] = trim($_GET["id"]);
    if (empty($_GET["id"])) {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
<section class="pt-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="page-header">
                    <h1>Supprimer l'actualitée</h1>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <div class="alert alert-danger fade-in">
                        <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>" />
                        <p>Êtes-vous sûr de vouloir supprimer cette actualitée ?</p><br>
                        <p>
                            <input type="submit" value="Oui" class="btn btn-danger">
                            <a href="actualite-index.php" class="btn btn-secondary">Non</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include_once('footer.php'); ?>