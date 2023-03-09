<?php
include("Bibliotheque_Connect_Database.php");

// informations des documents

@$ISBN_document = $_POST["ISBN_document"];
@$titre_document = $_POST["titre_document"];
@$date_parrution_document = $_POST["date_parrution_document"];
@$libelle_type_document = $_POST["libelle_type_document"];
@$id_type_document = $_POST["id_type_document"];

// informations des créateurs

@$nom_createur_bibliotheque = $_POST["nom_createur_bibliotheque"];
@$prenom_createur_bibliotheque = $_POST["prenom_createur_bibliotheque"];
@$libelle_type_createur = $_POST["libelle_type_createur"];
@$id_createur_bibliotheque = $_POST["id_createur_bibliotheque"];
@$id_type_createur = $_POST["id_type_createur"];


//variable de bouton et d'erreur
@$enregistrer_livre = $_POST["enregistrer_livre"];
@$enregistrer_BD = $_POST["enregistrer_BD"];
$erreur ="";

//Si l'utilisateur appuie sur le bouton "enregistrer" du formulaire

if(isset($_POST["enregistrer_livre"])){

    //récupération de l'id livre pour insertion

    $queryTypeDoc = "SELECT id_type_document FROM type_document_bibliotheque WHERE libelle_type_document = 'livre'";
    $selectTypeDoc = $pdo->prepare($queryTypeDoc);
    $selectTypeDoc->execute([$libelle_type_document]);
    $row_typeDoc = $selectTypeDoc->fetch(PDO::FETCH_ASSOC);

    if($row_typeDoc !== FALSE){
        $id_type_document = $row_typeDoc['id_type_document'];
    }else{
        echo "Aucun document de ce type n'est référencé";
    }

    
        //insertion dans la table document

       $queryLivre = "INSERT INTO document_bibliotheque (ISBN_document, titre_document, date_parrution_document, id_type_document)
       VALUES(:ISBN_document, :titre_document, :date_parrution_document, :id_type_document)"; 
       $insertLivre = $pdo->prepare($queryLivre);

         $donnees = $insertLivre->execute([
            ':ISBN_document' => $ISBN_document,
            ':titre_document' => $titre_document,
            ':date_parrution_document' => $date_parrution_document,
            ':id_type_document' => $id_type_document

         ]);

        
         //récupération de l'id type d'auteur pour insertion

         $queryTypeCreateur = "SELECT id_type_createur FROM type_createur WHERE libelle_type_createur = ?";
         $selectTypeCreateur = $pdo->prepare($queryTypeCreateur);
         $selectTypeCreateur->execute([$libelle_type_createur]);
         $row_TypeCreateur = $selectTypeCreateur->fetch(PDO::FETCH_ASSOC);

         if($row_TypeCreateur !== FALSE){
            $id_type_createur = $row_TypeCreateur['id_type_createur'];
         }

         //insertion dans la table createur_bibliotheque

         $queryInsertCreateur = "INSERT INTO createur_bibliotheque (nom_createur_bibliotheque, prenom_createur_bibliotheque, id_type_createur) 
         VALUES (:nom_createur_bibliotheque, :prenom_createur_bibliotheque, :id_type_createur);";
         $insertAuteur = $pdo->prepare(
            $queryInsertCreateur
         );

         $insertAuteur->execute([
            ':nom_createur_bibliotheque' => $nom_createur_bibliotheque,
            ':prenom_createur_bibliotheque' => $prenom_createur_bibliotheque,
            ':id_type_createur' => $id_type_createur
         ]);

        

         
         //sélection de l'ISBN document dans la table document
         $queryISBN = "SELECT ISBN_document FROM document_bibliotheque WHERE titre_document = ?";
         $selectISBN = $pdo->prepare($queryISBN);
         $selectISBN->execute([$titre_document]);
         $row_ISBN = $selectISBN->fetch(PDO::FETCH_ASSOC);

         if($row_ISBN !== FALSE){
            $ISBN_document = $row_ISBN['ISBN_document'];
         }

        
         //sélection de l'id créateur dans la table créateur
         $queryIDCreateur = "SELECT id_createur_bibliotheque FROM createur_bibliotheque WHERE nom_createur_bibliotheque = ? AND prenom_createur_bibliotheque = ?";
         $selectIDCreateur = $pdo->prepare($queryIDCreateur);
         $selectIDCreateur->execute([$nom_createur_bibliotheque, $prenom_createur_bibliotheque]);
         $row_IDCreateur = $selectIDCreateur->fetch(PDO::FETCH_ASSOC);

         if($row_IDCreateur !== FALSE){
            $id_createur_bibliotheque = $row_IDCreateur['id_createur_bibliotheque'];
         }
        

         //insertion dans la table createur_document_bibliotheque

         
         $queryCreaDoc = "INSERT INTO createur_document_bibliotheque (ISBN_document, id_createur_bibliotheque) VALUES (:ISBN_document, :id_createur_bibliotheque);";
         $insertCreaDoc = $pdo->prepare(
            $queryCreaDoc
         );

        $insertCreaDoc->execute([
            ':ISBN_document' => $ISBN_document,
            ':id_createur_bibliotheque' => $id_createur_bibliotheque
         ]);
        
        
        // $insert = $donnees->fetchAll();

        if($insertCreaDoc){
            echo "<script>alert('Nouveau document enregistré avec succès.')</script>";
        }else{
            echo "<script>alert('Erreur lors de l'enregistrement du document.')</script>";
        }

    
         
         
}
/******************************************************************************************************************************************************************* */
//Requêtes du formulaire d'enregistrement des BD

elseif(isset($_POST["enregistrer_BD"])){

    $queryTypeDoc = "SELECT id_type_document FROM type_document_bibliotheque WHERE libelle_type_document = 'Bande dessinée'";
    $selectTypeDoc = $pdo->prepare($queryTypeDoc);
    $selectTypeDoc->execute([$libelle_type_document]);
    $row_typeDoc = $selectTypeDoc->fetch(PDO::FETCH_ASSOC);

    if($row_typeDoc !== FALSE){
        $id_type_document = $row_typeDoc['id_type_document'];
    }else{
        echo "Aucun document de ce type n'est référencé";
    }


    //insertion dans la table document

    $queryBD = "INSERT INTO document_bibliotheque (ISBN_document, titre_document, date_parrution_document, id_type_document)
    VALUES(:ISBN_document, :titre_document, :date_parrution_document, :id_type_document)"; 
    $insertBD = $pdo->prepare($queryBD);

      $donnees = $insertBD->execute([
         ':ISBN_document' => $ISBN_document,
         ':titre_document' => $titre_document,
         ':date_parrution_document' => $date_parrution_document,
         ':id_type_document' => $id_type_document

      ]);
    

    //récupération des id auteur et dessinateur
    
    $querySelectAuteur = "SELECT id_type_createur FROM type_createur WHERE id_type_createur = 'auteur'";
    $selectTypeCreateurAuteur = $pdo->prepare($querySelectAuteur);
    $selectTypeCreateurAuteur->execute();
    $row_TypeCreateurAuteur = $selectTypeCreateurAuteur->fetch(PDO::FETCH_ASSOC);

    $querySelectDessinateur = "SELECT id_type_createur FROM type_createur WHERE id_type_createur = 'dessinateur'";
    $selectTypeCreateurDessinateur = $pdo->prepare($querySelectDessinateur);
    $selectTypeCreateurDessinateur->execute();
    $row_TypeCreateurDessinateur = $selectTypeCreateurDessinateur->fetch(PDO::FETCH_ASSOC);

    if($row_TypeCreateurAuteur !== FALSE){
        $id_type_createur_auteur = $row_TypeCreateurAuteur['id_type_createur'];
    }

    if($row_TypeCreateurDessinateur !== FALSE){
        $id_type_createur_dessinateur = $row_TypeCreateurDessinateur['id_type_createur'];
    }

    //insertion dans la table createur_bibliothèque

    $queryInsertAuteur = "INSERT INTO createur_bibliotheque (nom_createur_bibliotheque, prenom_createur_bibliotheque, id_type_createur) 
    VALUES (:nom_createur_bibliotheque, :prenom_createur_bibliotheque, :id_type_createur)";
    $queryInsertDessinateur = "INSERT INTO createur_bibliotheque (nom_createur_bibliotheque, prenom_createur_bibliotheque, id_type_createur) 
    VALUES (:nom_createur_bibliotheque, :prenom_createur_bibliotheque, :id_type_createur)";
    $insertCreateur = $pdo->prepare($queryInsertAuteur, $queryInsertDessinateur);

    $donnees = $insertCreateur->execute([
        ':nom_createur_bibliotheque' => $nom_createur_bibliotheque,
        ':prenom_createur_bibliotheque' => $prenom_createur_bibliotheque,
        ':id_type_createur' => $id_type_createur
    ]);

    //Sélection de l'ISBN BD pour insertion dans la table créateur_document_bibliothèque
    $queryISBN = "SELECT ISBN_document FROM document_bibliotheque WHERE ISBN_document = ?";
    $selectISBN = $pdo->prepare($queryISBN);
    $selectISBN->execute([$ISBN_document]);
    $row_ISBN = $selectISBN->fetch(PDO::FETCH_ASSOC);

    if($row_ISBN !== FALSE){
        $ISBN_document = $row_ISBN['ISBN_document'];
    }

    //sélection de l'ID créateur pour insertion dans la table créateur_document_bibliothèque
    $queryIDCreateur = "SELECT id_createur_bibliotheque FROM createur_bibliotheque WHERE nom_createur_bibliotheque = ? AND prenom_createur_bibliotheque = ?";
         $selectIDCreateur = $pdo->prepare($queryIDCreateur);
         $selectIDCreateur->execute([$nom_createur_bibliotheque, $prenom_createur_bibliotheque]);
         $row_IDCreateur = $selectIDCreateur->fetch(PDO::FETCH_ASSOC);

         if($row_IDCreateur !== FALSE){
            $id_createur_bibliotheque = $row_IDCreateur['id_createur_bibliotheque'];
         }

         $queryCreaDoc = "INSERT INTO createur_document_bibliotheque (ISBN_document, id_createur_bibliotheque) VALUES (:ISBN_document, :id_createur_bibliotheque);";
         $insertCreaDoc = $pdo->prepare(
            $queryCreaDoc
         );

        $insertCreaDoc->execute([
            ':ISBN_document' => $ISBN_document,
            ':id_createur_bibliotheque' => $id_createur_bibliotheque
         ]);
        
        
        // $insert = $donnees->fetchAll();

        if($insertCreaDoc){
            echo "<script>alert('Nouveau document enregistré avec succès.')</script>";
        }else{
            echo "<script>alert('Erreur lors de l'enregistrement du document.')</script>";
        }

}

/******************************************************************************************************************************************************************* */

//Requête SQL qui permet d'afficher les documents par ISBN, titre, date de parrution, type, nom et prénom de créateur et type de document

    $queryPrintDocument = "SELECT document_bibliotheque.ISBN_document, document_bibliotheque.titre_document,
    document_bibliotheque.date_parrution_document, type_document_bibliotheque.libelle_type_document,
    createur_bibliotheque.nom_createur_bibliotheque, createur_bibliotheque.prenom_createur_bibliotheque,
    type_createur.libelle_type_createur
    FROM document_bibliotheque
    JOIN createur_document_bibliotheque
    ON document_bibliotheque.ISBN_document = createur_document_bibliotheque.ISBN_document
    JOIN type_document_bibliotheque
    ON document_bibliotheque.id_type_document = type_document_bibliotheque.id_type_document
    JOIN createur_bibliotheque
    ON createur_document_bibliotheque.id_createur_bibliotheque = createur_bibliotheque.id_createur_bibliotheque
    JOIN type_createur
    ON createur_bibliotheque.id_type_createur = type_createur.id_type_createur
    ORDER BY document_bibliotheque.id_type_document, document_bibliotheque.date_parrution_document ASC,
    createur_bibliotheque.id_type_createur ASC";

    $listDocument = $pdo->query($queryPrintDocument);

    $pdo = null;


?>

<!DOCTYPE html>
<html>
    <head>
        <title>Documents de la Bibliothèque</title>
        <meta charset = "utf-8">
        <meta name = "viewport" content = "width=device-width, initial-scale=1, user-scalable=no">
        <link rel = "stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"/>
        <link rel = "stylesheet" href = "Bibliotheque_Style.css"/>
    </head>

    <body>

    <!-- Page permettant d'afficher la liste des documents existants (livre ou BD)
         et d'en ajouter de nouveaux -->

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
                    <a class = "nav-link active" href = "Bibliotheque_Document.php">Gestion des document</a>
                </li>

                <li class = "nav-item">
                    <a class = "nav-link" href = "Bibliotheque_Abbonne.php">Gestion des abonnés</a>
                </li>

              </ul>
            </div>

           </nav>

         </header>

         <div class = "erreur"><?php echo $erreur;?></div>
         

         <p>
            <button class = "btn btn-primary" type = "button" data-bs-toggle = "collapse" data-bs-target = "#Document" aria-expanded = "false" aria-controls = "Document">
                Enregistrer des Livres
            </button>
         </p>

    <div class = "container collapse" id = "Document">

        <form class = "Form" name = "fo" method = "post" action = "">
            

          <div class = "row">

            <div class = "justify-content-*-center">
            <label>Enregistrer des Livres</label>
            
              <input class = "form-control" type = "text" name = "ISBN_document" placeholder = "ISBN"/>
              <input class = "form-control" type = "text" name = "titre_document" placeholder = "Titre"/>
              <input class = "form-control" type = "date" required pattern = "\d{4}-\d{2}-\d{2}" name = "date_parrution_document" placeholder = "Année de publication"/>
              <label for = "type_document">Type de document</label>
              <select class = "form-select" id = "type_document" name = "libelle_type_document">
              <option></option>
              <option>livre</option>
              <option>Bande dessinée</option>
              </select>

              <input class = "form-control" type = "text" name = "nom_createur_bibliotheque" placeholder = "Nom du créateur"/>
              <input class = "form-control" type = "text" name = "prenom_createur_bibliotheque" placeholder = "Prénom du créateur"/>
              <label for = "role_createur">Rôle du créateur</label>
              <select class = "form-select" id = "role_createur" name = "libelle_type_createur">
              <option></option>
              <option>auteur</option>
              <option>dessinateur</option>
              <option>coloriste</option>
              </select>

              <input class = "btn btn-primary" type = "submit" name = "enregistrer_livre" value="Enregistrer">

            </div>

          </div>

        </form>

    </div>

          
    <p>
            <button class = "btn btn-primary" type = "button" data-bs-toggle = "collapse" data-bs-target = "#BD" aria-expanded = "false" aria-controls = "BD">
                Enregistrer des Bandes Dessinées
            </button>
         </p>

    <div class = "container collapse" id = "BD">

        <form class = "Form" name = "fo" method = "post" action = "">
            

          <div class = "row">

            <div class = "justify-content-*-center">
            <label>Enregistrer des Bandes Dessinées</label>
            
              <input class = "form-control" type = "text" name = "ISBN_document" placeholder = "ISBN"/>
              <input class = "form-control" type = "text" name = "titre_document" placeholder = "Titre"/>
              <input class = "form-control" type = "date" required pattern = "\d{4}-\d{2}-\d{2}" name = "date_parrution_document" placeholder = "Année de publication"/>
              <label for = "type_document">Type de document</label>
              <select class = "form-select" id = "type_document" name = "libelle_type_document">
              <option></option>
              <option>livre</option>
              <option>Bande dessinée</option>
              </select>

              <input class = "form-control" type = "text" name = "nom_createur_bibliotheque" placeholder = "Nom du créateur"/>
              <input class = "form-control" type = "text" name = "prenom_createur_bibliotheque" placeholder = "Prénom du créateur"/>
              <label for = "role_createur">Rôle du créateur</label>
              <select class = "form-select" id = "role_createur" name = "libelle_type_createur">
              <option>auteur</option>
              </select>

              <input class = "form-control" type = "text" name = "nom_createur_bibliotheque" placeholder = "Nom du créateur"/>
              <input class = "form-control" type = "text" name = "prenom_createur_bibliotheque" placeholder = "Prénom du créateur"/>
              <label for = "role_createur">Rôle du créateur</label>
              <select class = "form-select" id = "role_createur" name = "libelle_type_createur">
              <option>dessinateur</option>
              </select>

              <input class = "btn btn-primary" type = "submit" name = "enregistrer_BD" value="Enregistrer">

            </div>

          </div>

        </form>

    </div>
        
      
            

            <div class = "container-fluid">

            

                    <table class = "table table-striped table-bordered">
                        <label>Tableau des documents:</label>
                        <thead>
                            <tr>
                                <th class = "table-primary">ISBN du document</th>
                                <th class = "table-primary">Titre du document</th>
                                <th class = "table-primary">Date de parrution</th>
                                <th class = "table-primary">Type de document</th>
                                <th class = "table-primary">Prénom du créateur</th>
                                <th class = "table-primary">Nom du créateur</th>
                                <th class = "table-primary">Type de créateur</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while($donnees = $listDocument->fetch()){?>
                                <tr class = "scroll_container">
                                    <td class = "table-primary"><?php echo htmlspecialchars($donnees["ISBN_document"]);?></td>
                                    <td class = "table-primary"><?php echo htmlspecialchars($donnees["titre_document"]);?></td>
                                    <td class = "table-primary"><?php echo htmlspecialchars($donnees["date_parrution_document"]);?></td>
                                    <td class = "table-primary"><?php echo htmlspecialchars($donnees["libelle_type_document"]);?></td>
                                    <td class = "table-primary"><?php echo htmlspecialchars($donnees["prenom_createur_bibliotheque"]);?></td>
                                    <td class = "table-primary"><?php echo htmlspecialchars($donnees["nom_createur_bibliotheque"]);?></td>
                                    <td class = "table-primary"><?php echo htmlspecialchars($donnees["libelle_type_createur"]);?></td>
                                </tr>
                                <?php }?>
                        </tbody>
                    </table>
            

            </div>
         

         </div>


         <script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>