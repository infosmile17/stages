<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Gestion des Stages</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <a href="actualite-index.php" role="button">Actualite</a>
            </li>

            <?php if ($_SESSION['users']['type'] != 2) { ?>

                <li class="nav-item">
                    <a href="demande_stage-index.php" role="button">Demande stage</a>
                </li>

                <li class="nav-item">
                    <a href="demande_nomination-index.php" role="button">Demande nomination</a>
                </li>

                <li class="nav-item">
                    <a href="etudiant_soutenance-index.php" role="button">Etudiant soutenance</a>
                </li>

                <li class="nav-item">
                    <a href="etudiant-index.php" role="button">Etudiant</a>
                </li>
            <?php } ?>
            <li class="nav-item">
                <a href="enseignant_soutenance-index.php" role="button">Enseignant soutenance</a>
            </li>
            <li class="nav-item">
                <a href="rapport-index.php" role="button">Rapports</a>
            </li>
            <li class="nav-item">
                <a href="utilisateur-index.php" role="button">Utilisateurs</a>
            </li>




            <li class="nav-item">
                <a href="deconnexion.php" role="button">
                    <i class="fas fa-times"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">