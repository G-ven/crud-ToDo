<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>To Do Liste</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Roboto:wght@400;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Caveat&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/0a8d272569.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1 class="caveat">Ma To Do Liste</h1>

    <?php
    //activer le rapport d'erreurs
    error_reporting(E_ALL);
    ini_set('display_errors', 1);


    // variables utiles pour la connexion à la base de données
    $user = "root";
    $pass = "root";
    $dbPDO = null;

    // on utilise la variable CRUD pour identifier le type de CRUD
    // elle prendra les valeurs 'C' 'R' 'U' 'D' ou '0' si pas de requête
    $Crud = '0';
    

    // Etape 1 : récupération des données des formulaires (selon le bouton appuyé - Create Update ou Delete ?)
    if(isset($_POST["taskSubmit"])){
        if(!empty($_POST["tache"]) && !empty($_POST["categorie"])){
            //C pour create (insert)
            $Crud = 'C';
            $tache = $_POST["tache"];
            $categorie = $_POST["categorie"];
        }
    }

    if(isset($_POST["deleteSubmit"])){
        //D pour delete
        $Crud = 'D';
    }

    if(isset($_POST["updateSubmit"])){
        //2 clics sont nécessaires, 1 pour la saisie (U1) et 1 pour la validation (U2)
        $Crud = 'U1';
    }

    if(isset($_POST["update2Submit"])){
        $Crud = 'U2';
        $tache = $_POST["tache"];
        $categorie = $_POST["categorie"];
        $idUpdate = $_POST["idUpdate"];
    }




    // Etape 2 : connexion à la base de données avec PDO si $Crud != 0;
    $dbPDO = new PDO('mysql:host=localhost;dbname=crudToDo', $user, $pass);
    $dbPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($Crud != '0'){
    
        try{
            if($dbPDO){
                 // on fait un switch pour gérer les 4 requêtes de base du crud
                switch ($Crud){
                    case 'C':
                        $req = "INSERT INTO liste (tache, categorie) VALUES (:tache, :categorie)";
                        $stmt = $dbPDO->prepare($req); // requête préparée pour éviter les injections sql
                        $stmt->bindParam(':tache', $tache); // liaison des paramètres
                        $stmt->bindParam(':categorie', $categorie);

                        if ($stmt->execute()){
                                echo "La tâche : " . htmlspecialchars($tache ?? '') . " (catégorie : " . htmlspecialchars($categorie ?? '') . ") a bien été ajoutée";
                            } else{
                                echo "Erreur lors de l'ajout";
                            }
                            break;
                    
                    case 'R':
                        echo "<p>Vous avez fait une sélection de tâche</p>";
                        # TODO
                        break;

                    case'U1':
                        if(isset($_POST["idUpdate"])){
                            $idUpdate = $_POST["idUpdate"];
                            echo "<h1>Modifier la tâche n° ". htmlspecialchars($idUpdate) ."</h1>";

                        } else{
                            echo "<p> n° de tâche non défini, modification impossible</p>";
                        }
                        break;
                    
                    case 'U2':
                        echo "<p>Validation mise à jour tâche n° " . htmlspecialchars($idUpdate) . "</p>";

                            // vérification que les champs ne sont pas vide
                            if(!empty($tache) && !empty($categorie)){

                                $req = "UPDATE liste SET tache = :tache, categorie = :categorie WHERE ID = :idUpdate";
                                $stmt = $dbPDO->prepare($req); //requête préparée pour éviter les injections SQL
                                $stmt->bindParam(':tache', $tache); //liaison des paramètres
                                $stmt->bindParam(':categorie', $categorie);
                                $stmt->bindParam(':idUpdate', $idUpdate, PDO::PARAM_INT);
            
                                //Exécution de la requête préparée
                                if ($stmt->execute()){
                                    echo "La tâche " . htmlspecialchars($tache) . " , catégorie : " . htmlspecialchars($categorie) ." a bien été modifiée";
                                } else{
                                    echo "Erreur lors de la modification de la tâche";
                                }
                            }
                             break;

                    case 'D':
                        if(isset($_POST["ID"])){
                            $idsToDelete = $_POST["ID"];
                            $idDelete = implode(',', array_map('intval', $idsToDelete));

                            $req = "DELETE FROM liste WHERE ID IN ($idDelete)";
                            $stmt = $dbPDO->prepare($req); //requête préparée pour éviter les injections SQL
                            $stmt->execute();
                
                            if ($stmt->rowCount() > 0){
                                echo "Les tâches ont bien été supprimées";
                            } else{
                                echo "Erreur lors de la suppression des tâches";
                            }
                        } elseif (isset($_POST["idDelete"])){
                            $idDelete = intval($_POST["idDelete"]);
                            $req = "DELETE FROM liste WHERE ID = :idDelete";
                            $stmt = $dbPDO->prepare($req);
                            $stmt->bindParam(':idDelete', $idDelete, PDO::PARAM_INT);
                            if ($stmt->execute()){
                                echo "La tâche n° ". htmlspecialchars($idDelete) . " a bien été supprimée";
                            } else{
                                echo "Erreur lors de la suppression de la tâche";
                            }
                        }
                        break;
                }
            }
        } catch(PDOException $e){
            echo "Erreur : " . $e->getMessage();
        }
    }
    
       
    
    // on affiche le formulaire d'insertion
    ?>
    <form action="" method="post" class="formulaire">
        <div class="formLine">
            <label for="tache" class="roboto-regular">Tâche à faire</label>
            <input type="text" name="tache" required>
        </div>

        <div class="formLine">
            <label for="categorie" class="roboto-regular">Catégorie de la tâche</label>
            <input type="text" name="categorie">
        </div>
        <div class="ajout">
            <div class="button">
                <input type="submit" name="taskSubmit" value="Ajouter une tâche" class="roboto-regular">
            </div>
        </div>      
    </form>
    
    <?php

  

    // affichage des données dans un tableau
    $req = "SELECT * FROM LISTE ORDER BY ID DESC";
    $stmt = $dbPDO->query($req);
    if($stmt){
        ?>
        <form action="" method="post" class="post-it">
            <table>
                <thead class="roboto-black">
                    <th>N°</th>
                    <th>Tâche</th>
                    <th>Catégorie</th>
                </thead>

                <tbody class="caveat">
                    <?php
                    while($Tab = $stmt->fetch()){
                        ?>
                        <tr>
                            <?php
                            if($Crud == 'U1' && isset($idUpdate) && $Tab["ID"] == $idUpdate){

                                //formulaire de modification
                                ?>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="idUpdate" value="<?php echo $Tab["ID"]?>">
                                        <input type="text" name="tache" value="<?php echo $Tab["tache"]?>" autofocus class="inputTask">
                                        <input type="text" name="categorie" value="<?php echo $Tab["categorie"]?>" autofocus class="inputCategorie">
                                        <input type="submit" name="update2Submit" value="Valider" class="valider">
                                    </form>
                                </td>
                                <?php
                            }else{
                                echo "<td>".$Tab["ID"]."</td>";
                                echo "<td>".$Tab["tache"]."</td>";
                                echo "<td>".$Tab["categorie"]."</td>";  
                            }

                            if(!($Crud == 'U1' && isset($idUpdate) && $Tab["ID"] == $idUpdate)){
                                ?>
                                <td> 
                                    <input type="checkbox" id="ID_<?php echo $Tab["ID"] ?>" name="ID[]" value="<?php echo $Tab["ID"]; ?>">
                                    <form action="" method="post" style="display:inline;">
                                        <input type="hidden" name="idUpdate" value="<?php echo $Tab["ID"]; ?>">
                                            <button type="submit" name="updateSubmit">  
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                                </svg>
                                            </button>     
                                        <input type="hidden" name="idDelete" value="<?php echo $Tab["ID"]; ?>">
                                        <button type="submit" name="deleteSubmit"> 
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                            </svg>
                                        </button> 
                                    </form>                                   
                                </td>
                                <?php
                       
                            }
                            ?>   
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <div class="button2">
                <input type="submit" name="deleteSubmit" value="Supprimer les tâches sélectionnées">
            </div>
            <div class="tape"></div>
        </form>
        <?php
    }

    ?>

</body>
</html>