<?php
// Include config file

include_once('header.php');
include_once('navbar.php');
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$nom = "";
$prenom = "";
$tel = "";
$email = "";
$adresse = "";
$cin = "";
$login = "";
$pwd = "";
$approuver = "";
$type = "";

$nom_err = "";
$prenom_err = "";
$tel_err = "";
$email_err = "";
$adresse_err = "";
$cin_err = "";
$login_err = "";
$pwd_err = "";
$approuver_err = "";
$type_err = "";


// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $tel = trim($_POST["tel"]);
    $email = trim($_POST["email"]);
    $adresse = trim($_POST["adresse"]);
    $cin = trim($_POST["cin"]);
    $login = trim($_POST["login"]);
    $pwd = trim($_POST["pwd"]);
    $approuver = trim($_POST["approuver"]);
    $type = trim($_POST["type"]);


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

    $vars = parse_columns('utilisateur', $_POST);
    $stmt = $pdo->prepare("UPDATE utilisateur SET nom=?,prenom=?,tel=?,email=?,adresse=?,cin=?,login=?,pwd=?,type=? WHERE id=?");

    if (!$stmt->execute([$nom, $prenom, $tel, $email, $adresse, $cin, $login, $pwd, $type, $id])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: utilisateur-read.php?id=$id");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["id"] = trim($_GET["id"]);
    if (isset($_GET["id"]) && !empty($_GET["id"])) {
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM utilisateur WHERE id = ?";
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

                    $nom = $row["nom"];
                    $prenom = $row["prenom"];
                    $tel = $row["tel"];
                    $email = $row["email"];
                    $adresse = $row["adresse"];
                    $cin = $row["cin"];
                    $login = $row["login"];
                    $pwd = $row["pwd"];
                    $type = $row["type"];
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
                    <h2>Mettre à jour l'enregistrement</h2>
                </div>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="nom" maxlength="30" class="form-control" value="<?php echo $nom; ?>">
                        <span class="form-text"><?php echo $nom_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Prenom</label>
                        <input type="text" name="prenom" maxlength="30" class="form-control" value="<?php echo $prenom; ?>">
                        <span class="form-text"><?php echo $prenom_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="number" name="tel" class="form-control" value="<?php echo $tel; ?>">
                        <span class="form-text"><?php echo $tel_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" name="email" maxlength="50" class="form-control" value="<?php echo $email; ?>">
                        <span class="form-text"><?php echo $email_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Adresse</label>
                        <input type="text" name="adresse" maxlength="50" class="form-control" value="<?php echo $adresse; ?>">
                        <span class="form-text"><?php echo $adresse_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>CIN</label>
                        <input type="text" name="cin" maxlength="10" class="form-control" value="<?php echo $cin; ?>">
                        <span class="form-text"><?php echo $cin_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Login</label>
                        <input readonly type="text" name="login" maxlength="30" class="form-control" value="<?php echo $login; ?>">
                        <span class="form-text"><?php echo $login_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Mot de passe</label>
                        <input type="password" name="pwd" maxlength="30" class="form-control" value="<?php echo $pwd; ?>">
                        <span class="form-text"><?php echo $pwd_err; ?></span>
                    </div>
                    <div class="form-group">
                        Vous étes un :
                        <input type="radio" id="etudiant" name="type" value="1" <?php if ($type == 1) echo 'checked'; ?>>
                        <label for="etudiant">Etudiant</label>
                        <input type="radio" id="enseignant" name="type" value="2" <?php if ($type == 2) echo 'checked'; ?>>
                        <label for="enseignant">Enseignant</label>
                        <input type="radio" id="admin" name="type" value="3" <?php if ($type == 3) echo 'checked'; ?>>
                        <label for="admin">Admin</label>
                    </div>

                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <input type="submit" class="btn btn-primary" value="Enregistrer">
                    <a href="utilisateur-index.php" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include_once('footer.php'); ?>