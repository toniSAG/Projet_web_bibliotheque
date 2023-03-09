<?php
include ("Bibliotheque_Connect_Database.php");



@$nom_abonne_bibliotheque = $_POST["nom_abonne_bibliotheque"];
@$prenom_abonne_bibliotheque = $_POST["prenom_abonne_bibliotheque"];
@$id_abonne_bibliotheque = $_POST["id_abonne_bibliotheque"];


@$enregistrer = $_POST["enregistrer"];
@$supprimer = $_POST["supprimer"];
@$rechercher = $_POST["rechercher"];
@$effacer = "";

$erreur = "";

/*
*Code PHP et requêtes SQL
*Pour l'ajout de nouveaux
*abonnés dans la BDD
*/

if(isset($enregistrer)){

    if(empty($nom_abonne_bibliotheque)) $erreur = "Veuillez renseigner le nom de votre nouvel abonné";
    elseif(empty($prenom_abonne_bibliotheque)) $erreur = "Veuillez renseigner le prénom de votre nouvel abonné";

    else {

        
        
        $insertAbonne = $pdo->prepare("INSERT INTO abonne_bibliotheque (nom_abonne_bibliotheque, prenom_abonne_bibliotheque) VALUES(:nom_abonne_bibliotheque, :prenom_abonne_bibliotheque)");
        $insertAbonne->execute(array(
        "nom_abonne_bibliotheque" => $nom_abonne_bibliotheque,
        "prenom_abonne_bibliotheque" => $prenom_abonne_bibliotheque ));
        $tab = $insertAbonne->fetchAll();

        if($insertAbonne){
            echo"<script>alert('Un nouvel abonné a été enregistré')</script>";
        }else{

        echo "<script>alert('Cette personne est déjà enregistré en tant qu'abonné')</script>";
        }
      



    }
}

/*
*Code PHP et requêtes SQL
*Pour la supression d'abonnés
*existants dans la BDD
*/

elseif(isset($supprimer)){

    //Sélection de l'id des abonnés pour faciliter la supression 

    $queryAbonne = "SELECT id_abonne_bibliotheque FROM abonne_bibliotheque
                    WHERE nom_abonne_bibliotheque = ?
                    AND prenom_abonne_bibliotheque = ?";
    $selectAbonne = $pdo->prepare($queryAbonne);
    $selectAbonne->execute([
        $nom_abonne_bibliotheque, $prenom_abonne_bibliotheque
    ]);
    $row_abonne = $selectAbonne->fetch(PDO::FETCH_ASSOC);

    if($row_abonne !== FALSE){
        $id_abonne_bibliotheque = $row_abonne["id_abonne_bibliotheque"];
    }else{
        echo "Aucun abonné trouvé avec les informations spécifiées";
    }

    $queryDeleteAbonne = "DELETE FROM abonne_bibliotheque 
    WHERE id_abonne_bibliotheque = $id_abonne_bibliotheque";
    $deleteAbonne = $pdo->prepare($queryDeleteAbonne);
    $deleteAbonne->execute();
    $delete = $deleteAbonne->fetchAll(); 
}

/*
*Code PHP et requêtes SQL
*pour l'affichage des abonnés
*enregistrés dans la BDD
*/



$queryPrintAbonne = "SELECT nom_abonne_bibliotheque, prenom_abonne_bibliotheque 
                     FROM abonne_bibliotheque
                     ORDER BY nom_abonne_bibliotheque";
$listAbonne = $pdo->query($queryPrintAbonne);

$pdo = null;

?>

<!DOCTYPE html>

<html>
    <head>
        <title>Inscription des abbonés</title>
        <meta charset = "utf-8">
        <meta name = "viewport" content = "width=device-width, initial-scale=1, user-scalable=no">        
        <link rel = "stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
        <link rel = "stylesheet" href = "Bibliotheque_Style.css"/>
    </head>

    <body>

    <header>
           <nav class = "navbar navbar-expand-sm bg-light navbar-light">

            <div class = "container-fluid">

              <ul class = "navbar-nav">

                <li class = "nav-item">
                  <a class = "nav-link active" href="Bibliotheque_Acceuil.php">Retourner à l'acceuil</a>
                </li>

                <li class = "nav-item">
                    <a class = "nav-link" href = "Bibliotheque_Emprunt.php">Gestion des emprunts</a>
                </li>

                <li class = "nav-item">
                    <a class = "nav-link" href = "Bibliotheque_Document.php">Gestion des document</a>
                </li>

                <li class = "nav-item">
                    <a class = "nav-link active" href = "Bibliotheque_Abbonne.php">Gestion des abonnés</a>
                </li>

              </ul>
            </div>

           </nav>

         </header>


    <div class = "erreur"><?php echo $erreur;?></div>

    <div class = "row">

    <!-- Formulaire pour ajouter de nouveaux abonnés -->

    <p>
        <button class = "btn btn-primary" type = "button" data-bs-toggle = "collapse" data-bs-target = "#AbonneSave" aria-expanded = "false" aria-controls = "AbonneSave">
            Ajouter de nouveaux abonnés
        </button>
    </p>

    <div class = "container collapse" id = "AbonneSave">

    <form class = "Form" name = "fo" method = "post" action = "">

   <div class = "row">

    <div class = "justify-content-*-center">   
        <label>Ajouter de nouveaux abonnés</label>
          <input class = "form-control" type = "text" name = "nom_abonne_bibliotheque" placeholder = "Nom de l'abonné">
          <input class = "form-control" type = "text" name = "prenom_abonne_bibliotheque" placeholder = "Prénom de l'abonné">
          <button class = "btn btn-primary" type = "submit" name = "enregistrer">Enregistrer</button>
       
    
    </div>

   </div>

   </form>

    </div>

    <!-- Formulaire pour supprimer des abonnés -->

    <p>
        <button class = "btn btn-primary" type = "button" data-bs-toggle = "collapse" data-bs-target = "#AbonneDelete" aria-expanded = "false" aria-controls = "AbonneDelete">
            Supprimer des abonnés
        </button>
    </p>

    <div class = "container collapse" id = "AbonneDelete">

    <form class = "Form" name = "fo" method = "post" action = "">

   <div class = "row">

    <div class = "justify-content-*-center">   
        <label>Supprimer des abonnés</label>
          <input class = "form-control" type = "text" name = "nom_abonne_bibliotheque" placeholder = "Nom de l'abonné">
          <input class = "form-control" type = "text" name = "prenom_abonne_bibliotheque" placeholder = "Prénom de l'abonné">
          <button class = "btn btn-primary" type = "submit" name = "supprimer">Supprimer</button>
       
    
    </div>

   </div>

   </form>

    </div>

</div>

<!-- Panneau qui affiche les abonnés de la bibliothèque -->

<div class = "container-fluid">

    
        

        <table class = "table table-striped table-bordered">
        <label>Tableau des abonnés</label>
            <thead>
                <tr>
                    <th class = "table-primary">Prénom</th>
                    <th class = "table-primary">nom</th>
                </tr>
            </thead>
            <tbody>

            
                <?php while($donnees = $listAbonne->fetch()){?>
                <tr class = "scroll_container">
                    
                    <td class = "table-primary"><?php echo htmlspecialchars($donnees["prenom_abonne_bibliotheque"]);?></td>
                    <td class = "table-primary"><?php echo htmlspecialchars($donnees["nom_abonne_bibliotheque"]);?></td>
                </tr>
        <?php } ?>

    
</tbody>
</table>
    

</div>
<script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>