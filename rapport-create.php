<?php
// Include config file
include_once('header.php');
include_once('navbar.php');
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$id_etudiant = "";
$titre = "";
$nom_pdf = "";
$date = "";

$id_etudiant_err = "";
$titre_err = "";
$nom_pdf_err = "";
$date_err = "";


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_etudiant = trim($_POST["id_etudiant"]);
    $titre = trim($_POST["titre"]);
    $nom_pdf = trim($_POST["nom_pdf"]);
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

    $vars = parse_columns('rapport', $_POST);
    $stmt = $pdo->prepare("INSERT INTO rapport (id_etudiant,titre,nom_pdf,date) VALUES (?,?,?,?)");

    if ($stmt->execute([$id_etudiant, $titre, $nom_pdf, $date])) {
        $stmt = null;
        header("location: rapport-index.php");
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
                    <h2>Créer un enregistrement</h2>
                </div>
                <p>.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                    <div class="form-group">
                        <label>id_etudiant</label>
                        <input type="number" name="id_etudiant" class="form-control" value="<?php echo $id_etudiant; ?>">
                        <span class="form-text"><?php echo $id_etudiant_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>titre</label>
                        <input type="text" name="titre" maxlength="250" class="form-control" value="<?php echo $titre; ?>">
                        <span class="form-text"><?php echo $titre_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Rapport (pdf)</label>
                        <input type="text" name="nom_pdf" maxlength="250" class="form-control" value="<?php echo $nom_pdf; ?>">
                        <span class="form-text"><?php echo $nom_pdf_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>date</label>
                        <input type="date" name="date" class="form-control" value="<?php echo $date; ?>">
                        <span class="form-text"><?php echo $date_err; ?></span>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="rapport-index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include_once('footer.php'); ?>