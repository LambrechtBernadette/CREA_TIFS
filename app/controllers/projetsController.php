<?php

namespace App\Controllers\ProjetsController;

use \PDO;
use \App\Models\ProjetsModel;

// note : les ?? sont une coalescence de null, elles permettent de fournir une valeur celle de droite par défaut si la variable est null ou non définie.

function indexAction(PDO $connexion)
{
    include_once '../app/models/projetsModel.php';
    $pagination = 10;
    //nombre de projets définis par page
    $page = (int) ($_GET['page'] ?? 1);
    //Récupère le numéro de page depuis l'URL (?page=2). Si absent, page 1 par défaut. Le (int) sécurise contre une valeur non numérique.
    $totalPages = max(1, (int) ceil(ProjetsModel\countAll($connexion) / $pagination));
    //Calcule le nombre total de pages : countAll() compte tous les projets en base, divisé par 10, arrondi au supérieur (ceil). Le max(1, ...) évite d'avoir 0 pages si la table est vide.
    $page = min(max(1, $page), $totalPages);
    //Sécurise $page pour qu'elle reste dans les bornes [1, $totalPages] (empêche un utilisateur de mettre ?page=999 ou ?page=-5).
    $projets = ProjetsModel\findAll($connexion, $page, $pagination);
    //Récupère les projets pour la page courante avec la pagination définie.
    global $title, $content;
    $title = 'Projets';
    ob_start();
    include '../app/views/projets/index.php';
    $content = ob_get_clean();
}

function showAction(PDO $connexion, int $id)
{
    include_once '../app/models/projetsModel.php';
    $projet = ProjetsModel\findOneById($connexion, $id);
    //Récupère le projet correspondant à l'ID fourni. Si aucun projet n'est trouvé, $projet sera null.
    global $content, $title, $showHero;
    //Indique que la section "hero" ne doit pas être affichée pour cette page.
    $showHero = false;
    // indique au template global (default.php) de ne pas afficher la section "hero" (bannière d'accueil) sur cette page.
    $title = $projet['titre'];
    ob_start();
    include '../app/views/projets/show.php';
    $content = ob_get_clean();
    //$content contient le HTML de la liste des projets, prêt à être inséré dans le template global (header/nav/footer communs à toutes les pages).Comme il n'y a pas de système propre pour "retourner" ces données au template, le contrôleur utilise global pour écrire directement dans des variables globales partagées par tout le script.
}

function addFormAction(PDO $connexion): void
{
    include_once '../app/models/creatifsModel.php';
    $projet = null;
    $edit = false;
    $creatifs = \App\Models\CreatifsModel\findAll($connexion);
    // initialisent le formulaire en mode ajout vide, car addFormAction() sert à créer un nouveau projet

    global $content, $title, $showHero;
    $showHero = false;
    $title = 'Ajouter un projet';
    ob_start();
    include '../app/views/projets/form.php';
    $content = ob_get_clean();
}

function editFormAction(PDO $connexion, int $id): void
{
    include_once '../app/models/projetsModel.php';
    include_once '../app/models/creatifsModel.php';
    $projet = ProjetsModel\findOneById($connexion, $id);
    $edit = $projet !== null;
    $creatifs = \App\Models\CreatifsModel\findAll($connexion);

    global $content, $title, $showHero;
    $showHero = false;
    $title = $edit ? 'Modifier un projet' : 'Ajouter un projet';
    ob_start();
    include '../app/views/projets/form.php';
    $content = ob_get_clean();
    // edit un projet existant : si $projet est null, le formulaire sera affiché en mode ajout.
}

function insertAction(PDO $connexion, array $data, array $files): void
//$data=$_POST champs envoyés par le form.php
//$files = $_FILES les fichiers envoyés (ex: image).
{
    include_once '../app/models/projetsModel.php';
    $projetId = ProjetsModel\create($connexion, [
        'titre' => $data['title'] ?? '',
        'texte' => $data['text'] ?? '',
        'image' => uploadImage($files['image'] ?? null),
        'creatif' => (int) ($data['category_id'] ?? 0),
    ]);
    ProjetsModel\replaceTags($connexion, $projetId, $data['tags'] ?? []);
    redirectToHome();
}
/*$projetId est défini ici, dans insertAction. La fonction insère le nouveau projet en base avec une requête préparée (INSERT INTO projets).
Elle retourne ensuite $connexion->lastInsertId() : l'ID auto-incrémenté généré par MySQL pour la ligne qu'on vient d'insérer.
Donc $projetId = l'identifiant du projet fraîchement créé. Il sert juste après à lier les tags au bon projet :
*/


function updateAction(PDO $connexion, int $id, array $data, array $files): void
{
    include_once '../app/models/projetsModel.php';
    $projet = ProjetsModel\findOneById($connexion, $id);

    ProjetsModel\update($connexion, $id, [
        'titre' => $data['title'] ?? $projet['titre'],
        'texte' => $data['text'] ?? $projet['texte'],
        'image' => uploadImage($files['image'] ?? null) ?? $projet['image'],
        'creatif' => (int) ($data['category_id'] ?? $projet['creatif']),
    ]);
    ProjetsModel\replaceTags($connexion, $id, $data['tags'] ?? []);
    redirectToHome();
}

function deleteAction(PDO $connexion, int $id): void
{
    include_once '../app/models/projetsModel.php';
    ProjetsModel\delete($connexion, $id);
    redirectToHome();
}

function uploadImage(?array $file): ?string
{
    if ($file === null) {
        return null;
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
        return null;
    }

    $nomImage = uniqid() . '.' . $extension;

    move_uploaded_file(
        $file['tmp_name'],
        'images/' . $nomImage
    );

    return $nomImage;
}


function redirectToHome(): void
{
    header('Location: ' . PUBLIC_BASE_URL);
    exit;
}


