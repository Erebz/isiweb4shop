<?php
//Auteurs : GROSJEAN Justin (p1709013) et HAMDI Yacine (p1709958)

$dbHost = "localhost";
$dbUser = "isiweb";
$dbPwd = "isiwebmdp";
$dbName = "isiweb4shop";

/*Cette fonction prend en entrée une requête SQL SELECT et renvoie les résultats
de la requête. Si le résultat est faux, un message d'erreur est affiché*/
function executerSelect($requete) {
  global $dbHost, $dbUser, $dbPwd, $dbName;

  $tab = array();
  $connexion = new mysqli($dbHost, $dbUser, $dbPwd, $dbName);
  if ($connexion->connect_errno) {
    printf("Échec de la connexion : %s", mysqli_connect_error());
  } else {

    if (explode(" ", $requete)[0] == "SELECT") {
      $resultat = mysqli_query($connexion, $requete);
      if ($resultat == false) {
        printf("Échec de la requête \n");
        printf(mysqli_error($connexion));
      } else {

        $finfo = mysqli_fetch_fields($resultat);
        $entete = array();
        foreach ($finfo as $val) {
          array_push($entete, $val->name);
        }
        array_push($tab, $entete);
        while ($ligne = mysqli_fetch_array($resultat, MYSQLI_NUM)) {
          array_push($tab, $ligne);
        }

      }
    } else {
      printf("Erreur : n'est pas une requete SELECT");
    }

    mysqli_close($connexion);
  }
  return $tab;
}


/*Cette fonction prend en entrée une requête SQL INSERT/UPDATE/DELETE/LOAD et renvoie
 true si la mise à jour a fonctionné, sinon un message d'erreur est affiché.*/
function executerUpdate($requete) {
  global $dbHost, $dbUser, $dbPwd, $dbName;

  $succes = false;
  $connexion = new mysqli($dbHost, $dbUser, $dbPwd, $dbName);
  if ($connexion->connect_errno) {
    printf("Échec de la connexion : %s", mysqli_connect_error());
  } else {

    $type = explode(" ", $requete)[0];
    if ($type == 'INSERT' || $type == 'UPDATE' || $type == 'DELETE' || $type == 'LOAD') {
      $succes = mysqli_query($connexion, $requete);
      if ($succes == false) {
        printf("Échec de la requête \n");
        printf(mysqli_error($connexion));
      }
    } else {
      printf("Erreur : n'est pas une requete INSERT/UPDATE/DELETE/LOAD");
    }
    mysqli_close($connexion);
  }
  return $succes;
}

/* Cette fonction retourne un tableau html à partir d'un tableau php */
function arrayToTable($tableau) {
  $tabHtml = "<table>";
  foreach ($tableau as $tuple) {
    $tabHtml .= "<tr>";
    foreach ($tuple as $attribut) {
      $tabHtml .= "<td>" . $attribut . "</td>";
    }
    $tabHtml .= "</tr>";
  }
  $tabHtml .= "</table>";
  return $tabHtml;
}

/* Cette fonction retourne un tableau html DataTable à partir d'un tableau php */
function arrayToDataTable($tableau, $id) {
  if (count($tableau) > 0) {
    $tabHtml = '<div class="table-responsive pt-1 px-1">';
    $tabHtml .= '<table id="' . $id . '" class="table table-sm table-striped table-light table-hover border border-dark">';
    // $tabHtml .= '<table id="' . $id . '" class="table table-sm table-striped table-dark table-hover border border-dark">';

    $tabHtml .= '<thead class="thead-dark">';
    $tabHtml .= '<tr>';
    $entete = $tableau[0];
    $n = 0;
    foreach ($entete as $element) {
      $n++;
      if ($element == '') {
        $tabHtml .= '<th scope="col">COLONNE N°' . $n . '&nbsp;</th>';
      } else {
        $tabHtml .= '<th scope="col">' . $element . '&nbsp;</th>';
      }
    }
    $tabHtml .= '</tr>';
    $tabHtml .= '</thead>';

    $tabHtml .= '<tbody>';
    for ($i = 1; $i < count($tableau); ++$i) {
      $tabHtml .= '<tr>';
      $ligne = $tableau[$i];
      foreach ($ligne as $element) {
        $tabHtml .= '<td>' . $element . '</td>';
      }
      $tabHtml .= '</tr>';
    }
    $tabHtml .= '</tbody>';

    $tabHtml .= '</table>';
    $tabHtml .= '</div>';

    return $tabHtml;
  }
}

?>
