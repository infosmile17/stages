<?php

session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <head>
        <title>Gestion des stages</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
        <link rel="stylesheet" href="login.css">
    </head>
</head>

<body>
    <?php

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
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = trim($_POST["nom"]);
        $prenom = trim($_POST["prenom"]);
        $tel = trim($_POST["tel"]);
        $email = trim($_POST["email"]);
        $adresse = trim($_POST["adresse"]);
        $cin = trim($_POST["cin"]);
        $login = trim($_POST["login"]);
        $pwd = trim($_POST["pwd"]);
        $approuver = 0;
        $type = trim($_POST["type"]);


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

        $vars = parse_columns('utilisateur', $_POST);
        $stmt = $pdo->prepare("INSERT INTO utilisateur (nom,prenom,tel,email,adresse,cin,login,pwd,approuver,type) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $pdo->beginTransaction();
        $tempp = $stmt->execute([$nom, $prenom, $tel, $email, $adresse, $cin, $login, $pwd, $approuver, $type]);
        if ($tempp) {
            if ($type == 1) {
                $id_etudiant = $pdo->lastInsertId();
                $niveau = 1;
                $vars = parse_columns('etudiant', $_POST);
                $stmt = $pdo->prepare("INSERT INTO etudiant (id_etudiant,niveau) VALUES (?,?)");

                if ($stmt->execute([$id_etudiant, $niveau])) {
                    $stmt = null;
                    header("location: login.php?msg=En attente d'approbation");
                } else {
                    echo "Something went wrong. Please try again later.";
                }
            }
        } else {
            header("location: login.php?msg=En attente d'approbation");
        }
    } else {
    ?>

        <section class="pt-5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 mx-auto">
                        <div class="page-header">
                            <h2>Inscription</h2>
                        </div>
                        <p>Merci d'avoir utiliser notre application .</p>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" name="nom" maxlength="30" class="form-control" value="<?php echo $nom; ?>">
                                <span class="form-text"><?php echo $nom_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Prénom</label>
                                <input type="text" name="prenom" maxlength="30" class="form-control" value="<?php echo $prenom; ?>">
                                <span class="form-text"><?php echo $prenom_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Téléphone</label>
                                <input type="number" name="tel" maxlength="8" class="form-control" value="<?php echo $tel; ?>">
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
                                <label>Login (nom d'utilisateur )</label>
                                <input type="text" name="login" maxlength="30" class="form-control" value="<?php echo $login; ?>">
                                <span class="form-text"><?php echo $login_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Mot de passe</label>
                                <input type="password" name="pwd" maxlength="30" class="form-control" value="<?php echo $pwd; ?>">
                                <span class="form-text"><?php echo $pwd_err; ?></span>
                            </div>
                            <div class="form-group">
                                Vous étes un :
                                <input type="radio" id="etudiant" name="type" value="1" checked>
                                <label for="etudiant">Etudiant</label>
                                <input type="radio" id="enseignant" name="type" value="2">
                                <label for="enseignant">Enseignant</label>
                                <input type="radio" id="admin" name="type" value="3">
                                <label for="admin">Admin</label>

                                <?php //echo $type; 
                                ?>
                                <span class="form-text"><?php echo $type_err; ?></span>
                            </div>

                            <input type="submit" class="btn btn-primary" value="Envoyer">
                            <a href="utilisateur-index.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    <?php
    }
    ?>