<?php
include_once('header.php');
include_once('navbar.php');
include_once('function.php');
?>
<section class="pt-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header clearfix">
                    <h2 class="float-left">Listes des rapports de stages</h2>
                    <?php if ($_SESSION['users']['type'] == 1) { ?>
                        <a href="rapport-create.php" class="btn btn-success float-right">Ajouter un rapport</a>
                    <?php } ?>
                    <a href="rapport-index.php" class="btn btn-info float-right mr-2">RĂ©initialiser la vue</a>

                </div>

                <div class="form-row">
                    <form action="rapport-index.php" method="get">

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

                $total_pages_sql = "SELECT COUNT(*) FROM rapport";
                $result = mysqli_query($link, $total_pages_sql);
                $total_rows = mysqli_fetch_array($result)[0];
                $total_pages = ceil($total_rows / $no_of_records_per_page);

                //Column sorting on column name
                $orderBy = array('id_etudiant', 'titre', 'nom_pdf', 'date');
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
                if ($_SESSION['users']['type'] == 3) {
                    $sql = "SELECT * FROM rapport ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";
                    $count_pages = "SELECT * FROM rapport";
                } else {
                    $sql = "SELECT * FROM rapport WHERE (id_etudiant=" . $_SESSION['users']['id'] . " and public ='1') OR public ='0' ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";
                    $count_pages = "SELECT * FROM rapport  WHERE (id_etudiant=" . $_SESSION['users']['id'] . " and public ='1') OR public ='0' ";
                }

                if (!empty($_GET['search'])) {
                    $search = ($_GET['search']);
                    $sql = "SELECT * FROM rapport
                            WHERE CONCAT (id_etudiant,titre,nom_pdf,date)
                            LIKE '%$search%'
                            ORDER BY $order $sort
                            LIMIT $offset, $no_of_records_per_page";
                    $count_pages = "SELECT * FROM rapport
                            WHERE CONCAT (id_etudiant,titre,nom_pdf,date)
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
                        echo "<th><a href=?search=$search&sort=&order=id_etudiant&sort=$sort>Etudiant</th>";
                        echo "<th><a href=?search=$search&sort=&order=titre&sort=$sort>Titre</th>";
                        echo "<th><a href=?search=$search&sort=&order=nom_pdf&sort=$sort>Lien PDF</th>";
                        echo "<th><a href=?search=$search&sort=&order=date&sort=$sort>Date</th>";

                        echo "<th>Action</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . getEtudiantName($link, $row['id_etudiant']) . "</td>";
                            echo "<td>" . $row['titre'] . "</td>";
                            echo "<td><a href='/stages_/stages/rapports/" . $row['nom_pdf'] . "'>" . $row['nom_pdf'] . "</a></td>";
                            echo "<td>" . $row['date'] . "</td>";
                            echo "<td>";
                            echo "<a href='rapport-read.php?id=" . $row['id'] . "' title='Afficher enregistrement' data-toggle='tooltip'><i class='far fa-eye'></i></a>";
                            if ($_SESSION['users']['type'] == 3) {
                                // echo "<a href='rapport-update.php?id=" . $row['id'] . "' title='Mettre Ă  jour enregistrement' data-toggle='tooltip'><i class='far fa-edit'></i></a>";
                                echo "<a href='rapport-delete.php?id=" . $row['id'] . "' title='Supprimer enregistrement' data-toggle='tooltip'><i class='far fa-trash-alt'></i></a>";
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
                                                            } ?>">PrĂ©cĂ©dent</a>
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
                        echo "<p class='lead'><em>Aucun enregistrement n'a Ă©tĂ© trouvĂ©.</em></p>";
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
</body>

</html>