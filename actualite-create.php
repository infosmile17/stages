<?php
// Include config file
require_once "config.php";
require_once "helpers.php";
include_once('header.php');
include_once('navbar.php');
// Define variables and initialize with empty values
$titre = "";
$description = "";
$date = "";

$titre_err = "";
$description_err = "";
$date_err = "";


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = trim($_POST["titre"]);
    $description = trim($_POST["description"]);
    $date = trim($_POST["date"]);


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
        exit('Something weird happened'); //something a user can understand
    }

    $vars = parse_columns('actualite', $_POST);
    $stmt = $pdo->prepare("INSERT INTO actualite (titre,description,date) VALUES (?,?,?)");

    if ($stmt->execute([$titre, $description, $date])) {
        $stmt = null;
        header("location: actualite-index.php");
    } else {
        echo "Something went wrong. Please try again later.";
    }
}
?>
<section class="pt-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="page-header">
                    <h2>Creation d'une nouvelle actualitée</h2>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                    <div class="form-group">
                        <label>Titre</label>
                        <input type="text" required name="titre" maxlength="250" class="form-control" value="<?php echo $titre; ?>">
                        <span class="form-text"><?php echo $titre_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" required class="form-control"><?php echo $description; ?></textarea>
                        <span class="form-text"><?php echo $description_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" required class="form-control" value="<?php echo $date; ?>">
                        <span class="form-text"><?php echo $date_err; ?></span>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Envoyer">
                    <a href="actualite-index.php" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include_once('footer.php'); ?>