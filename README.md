# To Do Liste

C'est une application web simple, pensé pour gérer une liste de tâches. Elle a été développée en PHP avec une base de données MySQL.

## Table des matières

- [Aperçu](#aperçu)
- [Fonctionnalités](#fonctionnalités)
- [Installation](#installation)
- [Utilisation](#utilisation)
- [Structure du projet](#structure-du-projet)


## Aperçu

Cette application permet aux utilisateurs de créer, lire, mettre à jour et supprimer des tâches. Elle utilise le langage PHP pour le traitement backend et PDO pour interagir avec une base de données MySQL.

## Fonctionnalités

- Ajouter une nouvelle tâche associée à une catégorie.
- Visualiser toutes les tâches dans un tableau sous forme de post-it.
- Mettre à jour une tâche existante.
- Supprimer une tâche directement avec le picto corbeille ou plusieurs tâches en les sélectionnant au préalable et en utilisant le bouton de suppression.

## Installation

### Prérequis

- Serveur web (Apache, Nginx, etc.)
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur

### Étapes d'installation

1. Clonez le dépôt :

    ```bash
    git clone https://github.com/G-Vèn/crud-ToDo.git
    cd to-do-liste
    ```

2. Configurez votre serveur web pour pointer vers le répertoire du projet.

3. Créez la base de données MySQL et la table nécessaire :

    ```sql
    CREATE DATABASE crudToDo;
    USE crudToDo;

    CREATE TABLE liste (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        tache VARCHAR(255) NOT NULL,
        categorie VARCHAR(255) NOT NULL
    );
    ```

4. Configurez la connexion à la base de données dans le fichier PHP :

    ```php
    // Variables utiles pour la connexion à la base de données
    $user = "root"; // Remplacez par votre nom d'utilisateur MySQL
    $pass = "root"; // Remplacez par votre mot de passe MySQL
    ```

5. Démarrez votre serveur web et accédez à l'application via votre navigateur.

## Utilisation

1. Ouvrez votre navigateur et accédez à l'application.
2. Pour ajouter une tâche, remplissez le formulaire en haut de la page et cliquez sur "Ajouter une tâche".
3. Pour mettre à jour une tâche, cliquez sur le bouton de modification à côté de la tâche que vous souhaitez modifier, faites les changements nécessaires, puis validez.
4. Pour supprimer plusieurs tâches, cochez les cases des tâches à supprimer et cliquez sur "Supprimer les tâches sélectionnées". Si vous ne souhaitez supprimer qu'une seule tâche, cliquez sur l'icone "corbeille" à côté de la tâche.

## Structure du projet

- `index.php`: Le fichier principal contenant le code HTML et PHP.
- `style.css`: Le fichier de styles pour la mise en page de l'application.
- `README.md`: Ce fichier, expliquant le projet.
- `pluie.jpg`: Fichier image de fond.
