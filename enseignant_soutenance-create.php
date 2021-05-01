<?php
// Include config file
include_once('header.php');
include_once('navbar.php');
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$id_enseignant = "";
$date = "";
$temps = "";

$id_enseignant_err = "";
$date_err = "";
$temps_err = "";


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_enseignant = trim($_POST["id_enseignant"]);
    $date = trim($_POST["date"]);
    $temps = trim($_POST["temps"]);


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

    $vars = parse_columns('enseignant_soutenance', $_POST);
    $stmt = $pdo->prepare("INSERT INTO enseignant_soutenance (id_enseignant,date,temps) VALUES (?,?,?)");

    if ($stmt->execute([$id_enseignant, $date, $temps])) {
        $stmt = null;
        header("location: enseignant_soutenance-index.php");
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
                    <h2>Affecter une dates de soutenances à un enseignant</h2>
                </div>
                <p>.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                    <div class="form-group">
                        <label>id_enseignant</label>
                        <input type="number" name="id_enseignant" class="form-control" value="<?php echo $id_enseignant; ?>">
                        <span class="form-text"><?php echo $id_enseignant_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>date</label>
                        <input type="date" name="date" class="form-control" value="<?php echo $date; ?>">
                        <span class="form-text"><?php echo $date_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>temps</label>
                        <input type="time" name="temps" class="form-control" value="<?php echo $temps; ?>">
                        <span class="form-text"><?php echo $temps_err; ?></span>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="enseignant_soutenance-index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include_once('footer.php'); ?>