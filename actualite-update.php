<?php
// Include config file
include_once('header.php');
include_once('navbar.php');
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$titre = "";
$description = "";
$date = "";

$titre_err = "";
$description_err = "";
$date_err = "";


// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

    $titre = trim($_POST["titre"]);
    $description = trim($_POST["description"]);
    $date = trim($_POST["date"]);


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

    $vars = parse_columns('actualite', $_POST);
    $stmt = $pdo->prepare("UPDATE actualite SET titre=?,description=?,date=? WHERE id=?");

    if (!$stmt->execute([$titre, $description, $date, $id])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: actualite-read.php?id=$id");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["id"] = trim($_GET["id"]);
    if (isset($_GET["id"]) && !empty($_GET["id"])) {
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM actualite WHERE id = ?";
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

                    // Retrieve individual field value

                    $titre = $row["titre"];
                    $description = $row["description"];
                    $date = $row["date"];
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
?>
<section class="pt-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="page-header">
                    <h2>Mise à jours une actualités</h2>
                </div>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                    <div class="form-group">
                        <label>titre</label>
                        <input type="text" name="titre" maxlength="250" class="form-control" value="<?php echo $titre; ?>">
                        <span class="form-text"><?php echo $titre_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>description</label>
                        <textarea name="description" class="form-control"><?php echo $description; ?></textarea>
                        <span class="form-text"><?php echo $description_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>date</label>
                        <input type="text" name="date" class="form-control" value="<?php echo $date; ?>">
                        <span class="form-text"><?php echo $date_err; ?></span>
                    </div>

                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="actualite-index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include_once('footer.php'); ?>