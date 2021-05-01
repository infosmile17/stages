<?php
// Include config file
include_once('header.php');
include_once('navbar.php');
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_etudiant = trim($_POST["id_etudiant"]);
    $nom_pdf = trim($_POST["nom_pdf"]);
    $date_demande = trim($_POST["date_demande"]);
    $date_reponse = trim($_POST["date_reponse"]);
    $etat = trim($_POST["etat"]);


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

    $vars = parse_columns('demande_nomination', $_POST);
    $stmt = $pdo->prepare("INSERT INTO demande_nomination (id_etudiant,nom_pdf,date_demande,date_reponse,etat) VALUES (?,?,?,?,?)");

    if ($stmt->execute([$id_etudiant, $nom_pdf, $date_demande, $date_reponse, $etat])) {
        $stmt = null;
        header("location: demande_nomination-index.php");
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
                        <label>nom_pdf</label>
                        <input type="text" name="nom_pdf" maxlength="250" class="form-control" value="<?php echo $nom_pdf; ?>">
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

                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="demande_nomination-index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include_once('footer.php'); ?>