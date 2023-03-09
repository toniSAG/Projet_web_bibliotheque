<?php?>

<!DOCTYPE html>
<html>
    <head>
        <title>Acceuil de la Bibliothèque</title>
        <meta charset = "utf-8">
        <meta name = "viewport" content = "width=device-width, initial-scale=1, user-scalable=no">
        <link rel = "stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
        <link rel = "stylesheet" href = "Bibliotheque_Style.css"/>
        
    </head>

    <body>

    <!-- Page d'acceuil avec  un panneau contenant 3 boutons : 
         - Un bouton Emprunt/retour menant au clic à une page pour gérer les emprunts et les retours de documents
         - Un bouton Ajout de documents menant au clic à une page pour ajouter de nouveaux documents livre ou BD
         - Un bouton Ajout d'abonnés menant au clic à une page pour ajouter de nouveaux abonnés
         - D'autres boutons ? 
-->

<div class = "Bloc_Principal">

<div class = "Panneau_Acceuil">
    <div class = "Bouton_content">
        
    <input class = "btn btn-primary" type = "button" value = "Emprunt/retour" onclick = "window.location.href = 'Bibliotheque_Emprunt.php';"  />
    <input class = "btn btn-primary"  type = "button" value = "Gestion des documents" onclick = "window.location.href = 'Bibliotheque_Document.php';"/>
    <input class = "btn btn-primary"  type = "button" value = "Gestion des abonnés" onclick = "window.location.href = 'Bibliotheque_Abbonne.php';"/>

</div>



</div>

</div>
    </body>
</html>