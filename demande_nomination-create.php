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
    $id_etudiant = $_SESSION['users']['id'];
    $nom_pdf = '';
    $date_demande = trim($_POST["date_demande"]);
    $date_reponse = '';
    $etat = 0;


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
include_once('function.php');
$nomm = getEtudiantName($link, $_SESSION['users']['id']);
?>

<section class="pt-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="page-header">
                    <h2>Créer une demande de nomination</h2>
                </div>
                <p>.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                    <div class="form-group">
                        <label>Etudiant</label>
                        <input type="text" name="id_etudiant" class="form-control" disabled value="<?php echo $nomm; ?>">
                        <span class="form-text"><?php echo $id_etudiant_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>date demande</label>
                        <input type="date" name="date_demande" class="form-control" value="<?php echo $date_demande; ?>">
                        <span class="form-text"><?php echo $date_demande_err; ?></span>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Envoyer">
                    <a href="demande_nomination-index.php" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include_once('footer.php'); ?>