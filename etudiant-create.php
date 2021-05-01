<?php
include_once('header.php');
include_once('navbar.php');
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$id_etudiant = "";
$niveau = "";

$id_etudiant_err = "";
$niveau_err = "";


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_etudiant = trim($_POST["id_etudiant"]);
    $niveau = trim($_POST["niveau"]);


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

    $vars = parse_columns('etudiant', $_POST);
    $stmt = $pdo->prepare("INSERT INTO etudiant (id_etudiant,niveau) VALUES (?,?)");

    if ($stmt->execute([$id_etudiant, $niveau])) {
        $stmt = null;
        header("location: etudiant-index.php");
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
                    <h2>Modifier le niveau d'un etudiant</h2>
                </div>
                <p>.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                    <div class="form-group">
                        <label>Selectionner un etudiant</label>
                        <select name="id_etudiant" id="id_etudiant">
                            <?php

                            // Attempt select query execution
                            $sql_users = "SELECT * FROM utilisateur WHERE type=1";

                            if ($result = mysqli_query($link, $sql_users)) {
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['nom'] . ' ' . $row['prenom'] . "</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Niveau</label>
                        <input type="radio" id="_1ere" name="niveau" value="1" checked>
                        <label for="_1ere">1 ere </label>
                        <input type="radio" id="_2eme" name="niveau" value="2">
                        <label for="_2eme">2 eme</label>
                        <input type="radio" id="_3eme" name="niveau" value="3">
                        <label for="_3eme">3 eme</label>
                        <input type="radio" id="_4eme" name="niveau" value="4">
                        <label for="_4eme">4 eme</label>
                        <input type="radio" id="_5eme" name="niveau" value="5">
                        <label for="_5eme">5 eme</label>
                        <span class="form-text"><?php echo $niveau_err; ?></span>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Enregistrer">
                    <a href="etudiant-index.php" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</section><?php include_once('footer.php'); ?>