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

    if (isset($_POST['connexion'])) {
        if (isset($_POST['login']) && isset($_POST['pwd'])) {
            if (!empty($_POST['login']) && !empty($_POST['pwd'])) {
                $login = $_POST['login'];
                $pwd = $_POST['pwd'];
                include_once('config.php');
                require_once "helpers.php";

                // Prepare a select statement
                $sql = "SELECT * FROM utilisateur WHERE login = '$login' and pwd = '$pwd'";


                if ($stmt = mysqli_prepare($link, $sql)) {
                    // Set parameters

                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        $result = mysqli_stmt_get_result($stmt);

                        // var_dump($result);
                        // die;

                        if (mysqli_num_rows($result) == 1) {
                            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                            if ($row['approuver'] == 1) {
                                $_SESSION['users'] = $row;

                                header("location: actualite-index.php");
                            } else {
                                header("location: login.php?msg=En cours d'approbation par un administrateur!");
                            }


                            //var_dump($_SESSION['users']['type']);
                            //die();
                        } else {
                            // URL doesn't contain valid id parameter. Redirect to error page
                            header("location: error.php");
                            exit();
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.<br>" . $stmt->error;
                    }
                }
            }
        }
    } else {
    ?>

        <div class="container h-100">
            <div class="d-flex justify-content-center h-100">
                <div class="user_card">
                    <div class="d-flex justify-content-center">
                        <div class="brand_logo_container">
                            <img src="images/stage_transparent.jpg" class="brand_logo" alt="Logo">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center form_container">
                        <form action="login.php" method="POST">
                            <?php if (isset($_GET['msg'])) { ?>
                                <p style="color: whitesmoke;"><?php echo $_GET['msg']; ?></p>

                            <?php } ?>

                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="login" class="form-control input_user" value="" placeholder="Login">
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" name="pwd" class="form-control input_pass" value="" placeholder="Mot de passe">
                            </div>
                            <div class="d-flex justify-content-center mt-3 login_container">
                                <input type="submit" name="connexion" class="btn login_btn" value="Connexion">
                            </div>
                            <div class="d-flex justify-content-center mt-3 login_container">
                                <a style="color: white;" href="inscription.php">inscrivez-vous</a>
                            </div>
                        </form>


                    </div>

                    <div class="mt-4">
                        <div class="d-flex justify-content-center links">
                            Merci d'avoir utilisé notre application !
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>