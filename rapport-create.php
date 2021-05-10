<?php
// Include config file
include_once('header.php');
include_once('navbar.php');
require_once "config.php";
require_once "helpers.php";
require_once "function.php";

// Define variables and initialize with empty values
$titre = "";
$date = "";
$public = '0';

$id_etudiant_err = "";
$titre_err = "";
$nom_pdf_err = "";
$date_err = "";


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = trim($_POST["titre"]);
    $date = trim($_POST["date"]);
    $public = trim($_POST["public"]);


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

    $folder_path = 'rapports/';
    $filename = basename($_FILES['nom_pdf']['name']);
    $newname = $folder_path . $filename;
    $FileType = pathinfo($newname, PATHINFO_EXTENSION);

    $vars = parse_columns('rapport', $_POST);
    $id_etudiant = $_SESSION['users']['id'];

    $sqll = "INSERT INTO rapport (id_etudiant,titre,nom_pdf,date,public) VALUES (" . $id_etudiant . ", '" . $titre . "', '" . $filename . "', '" . $date . "', '" . $public . "')";
    $stmt = $pdo->prepare($sqll);

    // INSERT INTO rapport (id_etudiant,titre,nom_pdf,date,public) VALUES (28,'zefzef','RS.pdf','2021-05-11','0')
    $FileType = pathinfo($newname, PATHINFO_EXTENSION);
    if ($FileType == "pdf") {
        if (move_uploaded_file($_FILES['nom_pdf']['tmp_name'], $newname)) {
            // var_dump([$id_etudiant, $titre, $filename, $date, $public]);
            if ($stmt->execute()) {
                $stmt = null;
                header("location: rapport-index.php");
            }
        } else {

            echo "<p>Upload Failed.</p>";
        }
    }

    //end upload file
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
                <form enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Titre</label>
                        <input type="text" required name="titre" maxlength="250" class="form-control" value="<?php echo $titre; ?>">
                        <span class="form-text"><?php echo $titre_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Rapport (pdf)</label>
                        <input type="file" required name="nom_pdf" maxlength="250" class="form-control" value="<?php echo ''; ?>">
                        <span class="form-text"><?php echo $nom_pdf_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" required name="date" class="form-control" value="<?php echo $date; ?>">
                        <span class="form-text"><?php echo $date_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Visibilité</label><br>
                        <input type="radio" id="prive" name="public" value="0" checked>
                        <label for="prive">Privé</label><br>
                        <input type="radio" id="public" name="public" value="1">
                        <label for="public">Public</label><br>


                        <span class="form-text"><?php echo $date_err; ?></span>
                    </div>


                    <input type="submit" class="btn btn-primary" value="Envoyer">
                    <a href="rapport-index.php" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include_once('footer.php'); ?>