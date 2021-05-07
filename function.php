<?php
function getEtudiantName($link, $id)
{
    $nom = '';
    $sql = "SELECT nom,prenom FROM utilisateur WHERE id = $id";
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Retrieve individual field value

                $nom = $row["nom"] . ' ' . $row["prenom"];
            }
        }
    }
    return $nom;
}
function getEtat($etat)
{
    if ($etat) {
        return 'traitée';
    } else {
        return 'non traitée';
    }
}
