<?php
include_once('header.php');
include_once('navbar.php');
?>
<section class="pt-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header clearfix">
                    <h2 class="float-left">Listes des utilisateurs</h2>
                    <?php if ($_SESSION['users']['type'] == 3) {
                    ?>

                        <a href="utilisateur-create.php" class="btn btn-success float-right">Ajouter un utilisateur</a>

                    <?php
                    } ?>

                    <a href="utilisateur-index.php" class="btn btn-info float-right mr-2">Réinitialiser la vue</a>
                </div>

                <div class="form-row">
                    <form action="utilisateur-index.php" method="get">
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Rechercher ..." name="search">
                        </div>
                </div>
                </form>
                <br>

                <?php
                // Include config file
                require_once "config.php";
                require_once "helpers.php";

                //Get current URL and parameters for correct pagination
                $protocol = $_SERVER['SERVER_PROTOCOL'];
                $domain     = $_SERVER['HTTP_HOST'];
                $script   = $_SERVER['SCRIPT_NAME'];
                $parameters   = $_SERVER['QUERY_STRING'];
                $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https')
                    === FALSE ? 'http' : 'https';
                $currenturl = $protocol . '://' . $domain . $script . '?' . $parameters;

                //Pagination
                if (isset($_GET['pageno'])) {
                    $pageno = $_GET['pageno'];
                } else {
                    $pageno = 1;
                }

                //$no_of_records_per_page is set on the index page. Default is 10.
                $offset = ($pageno - 1) * $no_of_records_per_page;

                $total_pages_sql = "SELECT COUNT(*) FROM utilisateur";
                $result = mysqli_query($link, $total_pages_sql);
                $total_rows = mysqli_fetch_array($result)[0];
                $total_pages = ceil($total_rows / $no_of_records_per_page);

                //Column sorting on column name
                $orderBy = array('nom', 'prenom', 'tel', 'email', 'adresse', 'cin', 'login', 'pwd', 'approuver', 'type');
                $order = 'id';
                if (isset($_GET['order']) && in_array($_GET['order'], $orderBy)) {
                    $order = $_GET['order'];
                }

                //Column sort order
                $sortBy = array('asc', 'desc');
                $sort = 'desc';
                if (isset($_GET['sort']) && in_array($_GET['sort'], $sortBy)) {
                    if ($_GET['sort'] == 'asc') {
                        $sort = 'desc';
                    } else {
                        $sort = 'asc';
                    }
                }

                // Attempt select query execution
                $sql = "SELECT * FROM utilisateur ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";
                $count_pages = "SELECT * FROM utilisateur";


                if (!empty($_GET['search'])) {
                    $search = ($_GET['search']);
                    $sql = "SELECT * FROM utilisateur
                            WHERE CONCAT (nom,prenom,tel,email,adresse,cin,login,pwd,approuver,type)
                            LIKE '%$search%'
                            ORDER BY $order $sort
                            LIMIT $offset, $no_of_records_per_page";
                    $count_pages = "SELECT * FROM utilisateur
                            WHERE CONCAT (nom,prenom,tel,email,adresse,cin,login,pwd,approuver,type)
                            LIKE '%$search%'
                            ORDER BY $order $sort";
                } else {
                    $search = "";
                }

                if ($result = mysqli_query($link, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        if ($result_count = mysqli_query($link, $count_pages)) {
                            $total_pages = ceil(mysqli_num_rows($result_count) / $no_of_records_per_page);
                        }
                        $number_of_results = mysqli_num_rows($result_count);
                        echo " " . $number_of_results . " resultats - Page " . $pageno . " de " . $total_pages;

                        echo "<table class='table table-bordered table-striped'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th><a href=?search=$search&sort=&order=nom&sort=$sort>Nom</th>";
                        echo "<th><a href=?search=$search&sort=&order=prenom&sort=$sort>Prenom</th>";
                        echo "<th><a href=?search=$search&sort=&order=tel&sort=$sort>Téléphone</th>";
                        echo "<th><a href=?search=$search&sort=&order=email&sort=$sort>Email</th>";
                        echo "<th><a href=?search=$search&sort=&order=adresse&sort=$sort>Adresse</th>";
                        echo "<th><a href=?search=$search&sort=&order=cin&sort=$sort>CIN</th>";
                        echo "<th><a href=?search=$search&sort=&order=login&sort=$sort>Login</th>";

                        if ($_SESSION['users']['type'] != 2) {
                            echo "<th><a href=?search=$search&sort=&order=approuver&sort=$sort>Approuver</th>";
                        }

                        echo "<th><a href=?search=$search&sort=&order=type&sort=$sort>Type</th>";

                        echo "<th>Action</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            $type = "Etudiant";
                            switch ($row['type']) {
                                case "2":
                                    $type = "Enseignant";
                                    break;
                                case "3":
                                    $type = "Admin";
                                    break;
                            }
                            echo "<tr>";
                            echo "<td>" . $row['nom'] . "</td>";
                            echo "<td>" . $row['prenom'] . "</td>";
                            echo "<td>" . $row['tel'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $row['adresse'] . "</td>";
                            if ($_SESSION['users']['type'] != 2) {
                                echo "<td>" . $row['cin'] . "</td>";
                                echo "<td>" . $row['login'] . "</td><td>";
                                if ($row['approuver'] == '0') {
                                    echo "Non approuver :<a href='actions.php?approuver=" . $row['id'] . "'>Activer</a>";
                                } else {
                                    echo "Approuver :<a href='actions.php?desapprouver=" . $row['id'] . "'>Desactiver</a>";
                                }
                            } elseif ($_SESSION['users']['id'] == $row['id']) {
                                echo "<td>" . $row['cin'] . "</td>";
                                echo "<td>" . $row['login'] . "</td><td>";
                            } else {
                                echo "<td></td>";
                                echo "<td></td><td>";
                            }

                            echo "</td><td>" . $type . "</td>";
                            echo "<td>";
                            echo "<a href='utilisateur-read.php?id=" . $row['id'] . "' title='View Record' data-toggle='tooltip'><i class='far fa-eye'></i></a>";
                            if ($_SESSION['users']['type'] != 2) {
                                echo "<a href='utilisateur-update.php?id=" . $row['id'] . "' title='Update Record' data-toggle='tooltip'><i class='far fa-edit'></i></a>";
                                echo "<a href='utilisateur-delete.php?id=" . $row['id'] . "' title='Delete Record' data-toggle='tooltip'><i class='far fa-trash-alt'></i></a>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                ?>
                        <ul class="pagination" align-right>
                            <?php
                            $new_url = preg_replace('/&?pageno=[^&]*/', '', $currenturl);
                            ?>
                            <li class="page-item"><a class="page-link" href="<?php echo $new_url . '&pageno=1' ?>">Debut</a></li>
                            <li class="page-item <?php if ($pageno <= 1) {
                                                        echo 'disabled';
                                                    } ?>">
                                <a class="page-link" href="<?php if ($pageno <= 1) {
                                                                echo '#';
                                                            } else {
                                                                echo $new_url . "&pageno=" . ($pageno - 1);
                                                            } ?>">Précédent</a>
                            </li>
                            <li class="page-item <?php if ($pageno >= $total_pages) {
                                                        echo 'disabled';
                                                    } ?>">
                                <a class="page-link" href="<?php if ($pageno >= $total_pages) {
                                                                echo '#';
                                                            } else {
                                                                echo $new_url . "&pageno=" . ($pageno + 1);
                                                            } ?>">Suivant</a>
                            </li>
                            <li class="page-item <?php if ($pageno >= $total_pages) {
                                                        echo 'disabled';
                                                    } ?>">
                                <a class="page-item"><a class="page-link" href="<?php echo $new_url . '&pageno=' . $total_pages; ?>">Dernier</a>
                            </li>
                        </ul>
                <?php
                        // Free result set
                        mysqli_free_result($result);
                    } else {
                        echo "<p class='lead'><em>Aucun enregistrement n'a été trouvé.</em></p>";
                    }
                } else {
                    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                }

                // Close connection
                mysqli_close($link);
                ?>
            </div>
        </div>
    </div>
</section>
<?php
include_once('footer.php');
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>