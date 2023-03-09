<?php
include("Bibliotheque_Connect_Database.php");

@$date_emprunt = $_POST["date_emprunt"];
@$date_retour = $_POST["date_retour"];
@$id_abonne_bibliotheque = $_POST["id_abonne_bibliotheque"];
@$nom_abonne_bibliotheque = $_POST["nom_abonne_bibliotheque"];
@$prenom_abonne_bibliotheque = $_POST["prenom_abonne_bibliotheque"];
@$id_employe_bibliotheque = $_POST["id_employe_bibliotheque"];
@$nom_employe_bibliotheque = $_POST["nom_employe_bibliotheque"];
@$prenom_employe_bibliotheque = $_POST["prenom_employe_bibliotheque"];
@$ISBN_document = $_POST["ISBN_document"];
@$titre_document = $_POST["titre_document"];


@$emprunt = $_POST["emprunt"];
@$retour = $_POST["retour"];

@$afficher = $_POST["afficher"];

$erreur = "";

if(isset($emprunt)){

    //selection des abonnes pour insertion de l'id abonne dans la table emprunt

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
        echo "Aucun abonné trouvé avec les informations spécifiés.";
    }

    


    //selection des employes pour insertion de l'id employe dans la table emprunt

    $queryEmploye = "SELECT id_employe_bibliotheque FROM employe_bibliotheque
    WHERE nom_employe_bibliotheque = ?
    AND prenom_employe_bibliotheque = ?";
    $selectEmploye = $pdo->prepare($queryEmploye);
    $selectEmploye->execute([
    $nom_employe_bibliotheque, $prenom_employe_bibliotheque
    ]);
    $row_employe = $selectEmploye->fetch(PDO::FETCH_ASSOC);

    if($row_employe !== FALSE){

        $id_employe_bibliotheque = $row_employe["id_employe_bibliotheque"];

    }else{
        echo "Aucun employé trouvé avec les informations spécifiés";
    }


    //selection des documents pour insertion de l'ISBN document dans la table emprunt

    $queryDocument = "SELECT ISBN_document FROM document_bibliotheque
    WHERE titre_document = ?";
    $selectDocument = $pdo->prepare($queryDocument);
    $selectDocument->execute([$titre_document]);
    $row_document = $selectDocument->fetch(PDO::FETCH_ASSOC);

    if($selectDocument->rowCount() > 0){

        $ISBN_document = $row_document["ISBN_document"];

    }else{
        echo "Aucun document trouvé avec les informations spécifiés";
    }

    
    
    $queryEmprunt = "INSERT INTO emprunt (date_emprunt, date_retour, id_abonne_bibliotheque, id_employe_bibliotheque, ISBN_document)
    VALUES (:date_emprunt, :date_retour, :id_abonne_bibliotheque, :id_employe_bibliotheque, :ISBN_document)";
    $insertEmprunt = $pdo->prepare($queryEmprunt);
    $insertEmprunt ->execute([
        ':date_emprunt' => $date_emprunt,
        ':date_retour' => $date_retour,
        ':id_abonne_bibliotheque' => $id_abonne_bibliotheque,
        ':id_employe_bibliotheque' => $id_employe_bibliotheque,
        ':ISBN_document' => $ISBN_document
    ]);
    $execute = $insertEmprunt->fetchAll();
    
    if($insertEmprunt){
        echo "<script>alert('Nouvel emprunt enregistré avec succès.')</script>";
    }else{
        echo "<script>alert('Erreur d'enregistrement de l'emprunt.')</script>";
    }




 

}

if(isset($retour)){
    $retourEmprunt = $pdo->prepare("DELETE FROM emprunt WHERE ISBN_document = $ISBN_document");
    $retourEmprunt ->execute();
    $tab = $retourEmprunt->fetchAll();

    
}



    $queryPrintEmprunt = "SELECT abonne_bibliotheque.nom_abonne_bibliotheque, abonne_bibliotheque.prenom_abonne_bibliotheque,
    employe_bibliotheque.nom_employe_bibliotheque, employe_bibliotheque.prenom_employe_bibliotheque, document_bibliotheque.titre_document,
    emprunt.ISBN_document, emprunt.date_emprunt, emprunt.date_retour
    FROM emprunt
    JOIN abonne_bibliotheque
    ON emprunt.id_abonne_bibliotheque = abonne_bibliotheque.id_abonne_bibliotheque
    JOIN employe_bibliotheque
    ON emprunt.id_employe_bibliotheque = employe_bibliotheque.id_employe_bibliotheque
    JOIN document_bibliotheque
    ON emprunt.ISBN_document = document_bibliotheque.ISBN_document
    ORDER BY emprunt.id_emprunt";
    $listEmprunt = $pdo->query($queryPrintEmprunt);
    
$pdo = null;



?>

<!DOCTYPE html>

<html>
    <head>
        <title>Gestion des emprunts et des retours</title>
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
                    <a class = "nav-link active" href = "Bibliotheque_Emprunt.php">Gestion des emprunts</a>
                </li>

                <li class = "nav-item">
                    <a class = "nav-link" href = "Bibliotheque_Document.php">Gestion des document</a>
                </li>

                <li class = "nav-item">
                    <a class = "nav-link" href = "Bibliotheque_Abbonne.php">Gestion des abonnés</a>
                </li>

              </ul>
            </div>

           </nav>

         </header>

<div class = "row">



<p>
<button class = "btn btn-primary" type = "button" data-bs-toggle = "collapse" data-bs-target = "#formulaireEmprunt" aria-expanded = "false" aria-controls = "formulaireEmprunt">
    Emprunt de document
</button>
</p>
    <div class = "container collapse" id = "formulaireEmprunt">

           
           <form class = "Form" name = "fo" method = "post" action = "">

           

           <div class = "row">
            
             <div class = "justify-content-*-center">
             <label for = "emprunt">Emprunt de document</label>
              
                 <input class = "form-control" type = "date" required pattern = "\d{4}-\d{2}-\d{2}" name = "date_emprunt">
                 <input class = "form-control" type = "date" required pattern = "\d{4}-\d{2}-\d{2}" name = "date_retour">
                 <input id = "emprunt" class = "form-control" type = "text" name = "nom_abonne_bibliotheque" placeholder = "Nom de l'abonné">
                 <input id = "emprunt" class = "form-control" type = "text" name = "prenom_abonne_bibliotheque" placeholder = "Prénom de l'abonné">
                 <input id = "emprunt" class = "form-control" type = "text" name = "nom_employe_bibliotheque" placeholder = "Nom de l'employé">
                 <input id = "emprunt" class = "form-control" type = "text" name = "prenom_employe_bibliotheque" placeholder = "Prénom de l'employé">
                 <input id = "emprunt" class = "form-control" type = "text" name = "titre_document" placeholder = "Document emprunté">
            

                 <button class = "btn btn-success" type = "submit" name = "emprunt">Valider l' emprunt</button>

             </div>

            </div>

               </form>
    
    </div>

    <p>
    <button class = "btn btn-primary" type = "button" data-bs-toggle = "collapse" data-bs-target = "#formulaireRetour" aria-expanded = "false" aria-controls = "formulaireRetour">
    Retour de document
    </button></p>

    <div class = "container collapse" id = "formulaireRetour">
        
            <form id = "retour" class = "Form" name = "fo" method = "post" action = "">

            <div class = "row">

                <div class = "justify-content-*-center">

            <label for = "retour">Retour de document</label>
                <input class = "form-control" type = "text" name = "titre_document" placeholder = "Document retourné">
                <input class = "form-control" type = "text" name = "ISBN_document" placeholder = "ISBN du document">
                <input class = "form-control" type = "text" name = "nom_abonne_bibliotheque" placeholder = "Nom de l'abonné">
                <input class = "form-control" type = "text" name = "prenom_abonne_bibliotheque" placeholder = "Prénom de l'abonné">
                
                <button class = "btn btn-primary" type = "submit" name = "retour">Valider le retour</button>

                </div>
            </div>

            </form>
    </div>

</div>

<div class = "container-fluid">
  
                <table class="table table-striped table-bordered">
                    <label>Tableau des emprunts:</label>
                <thead>
                    <tr>
                        <th class = "table-primary">Prénom de l'abonné</th>
                        <th class = "table-primary">Nom de l'abonné</th>
                        <th class = "table-primary">titre du document</th>
                        <th class = "table-primary">ISBN du document</th>
                        <th class = "table-primary">date d'emprunt</th>
                        <th class = "table-primary">date de retour</th>
                        <th class = "table-primary">Prénom de l'employé</th>
                        <th class = "table-primary">Nom de l'employé</th>
                        
                    </tr>
                </thead>
                <tbody>

                <?php while($donnees = $listEmprunt->fetch()){?>
                    <tr class = "scroll_container">
                        <td class = "table-primary"><?php echo htmlspecialchars($donnees["prenom_abonne_bibliotheque"]); ?></td>
                        <td class = "table-primary"><?php echo htmlspecialchars($donnees["nom_abonne_bibliotheque"]); ?></td>
                        <td class = "table-primary"><?php echo htmlspecialchars($donnees["titre_document"]); ?></td>
                        <td class = "table-primary"><?php echo htmlspecialchars($donnees["ISBN_document"]); ?></td>
                        <td class = "table-primary"><?php echo htmlspecialchars($donnees["date_emprunt"]); ?></td>
                        <td class = "table-primary"><?php echo htmlspecialchars($donnees["date_retour"]); ?></td>
                        <td class = "table-primary"><?php echo htmlspecialchars($donnees["prenom_employe_bibliotheque"]); ?></td>
                        <td class = "table-primary"><?php echo htmlspecialchars($donnees["nom_employe_bibliotheque"]);?></td>
                    </tr>
                
                <?php } ?>

                </tbody>
                </table>
        </form>
                
</div>

<script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>