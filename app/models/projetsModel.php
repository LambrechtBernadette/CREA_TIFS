<?php

namespace App\Models\ProjetsModel;

use \PDO;

function findAll(PDO $connexion, int $page = 1, int $pagination = 10): array
{
    $page = max(1, $page);
    $offset = ($page - 1) * $pagination;
    $sql = "SELECT projets.*, creatifs.pseudo AS creatif_pseudo
            FROM projets
            INNER JOIN creatifs ON projets.creatif = creatifs.id
            ORDER BY projets.dateCreation DESC
            LIMIT :limit OFFSET :offset;";
    $rs = $connexion->prepare($sql);
    $rs->bindValue(':limit', $pagination, PDO::PARAM_INT);
    $rs->bindValue(':offset', $offset, PDO::PARAM_INT);
    $rs->execute();
    return $rs->fetchAll(PDO::FETCH_ASSOC);
}

function countAll(PDO $connexion): int
{
    return (int) $connexion->query('SELECT COUNT(*) FROM projets')->fetchColumn();
}


function findOneById(PDO $connexion, int $id): ?array
{
    //Pour chaque projet p, elle va chercher tous les tags liés (table projets_has_tags), et les compacte en une seule chaîne "3|5|7" stockée dans la colonne virtuelle projet_tag_ids du résultat.
    $sql = "SELECT
                p.*,
                c.pseudo AS creatif_pseudo,
                c.image AS creatif_image,
                (SELECT GROUP_CONCAT(tags.nom ORDER BY tags.nom SEPARATOR '|')
                 FROM projets_has_tags
                 INNER JOIN tags ON tags.id = projets_has_tags.tag
                 WHERE projets_has_tags.projet = p.id) AS projet_tags,
                (SELECT GROUP_CONCAT(projets_has_tags.tag ORDER BY projets_has_tags.tag SEPARATOR '|')
                 FROM projets_has_tags
                 WHERE projets_has_tags.projet = p.id) AS projet_tag_ids
            FROM projets p
            INNER JOIN creatifs c ON p.creatif = c.id
            WHERE p.id = :id;";
    $rs = $connexion->prepare($sql);
    $rs->bindValue(':id', $id, PDO::PARAM_INT);
    $rs->execute();
    $projet = $rs->fetch(PDO::FETCH_ASSOC);
    return $projet !== false ? $projet : null;
}

function create(PDO $connexion, array $data): int
{
    $sql = "INSERT INTO projets (titre, texte, dateCreation, image, creatif)
            VALUES (:titre, :texte, NOW(), :image, :creatif);";
    $rs = $connexion->prepare($sql);
    $rs->execute([':titre' => $data['titre'],
        ':texte' => $data['texte'],
        ':image' => $data['image'],
        ':creatif' => $data['creatif'],
    ]);

    return (int) $connexion->lastInsertId();
}

//Cette fonction est appelée depuis projetsController.php, updateAction(), qui construit $data à partir du formulaire ($_POST) avec des valeurs de repli (??) reprenant l'ancien projet si un champ n'est pas envoyé.
function update(PDO $connexion, int $id, array $data): void
{
    $sql = "UPDATE projets
            SET titre = :titre, texte = :texte, image = :image, creatif = :creatif
            WHERE id = :id;";
    $rs = $connexion->prepare($sql);
    $rs->execute([
        ':id' => $id,
        ':titre' => $data['titre'],
        ':texte' => $data['texte'],
        ':image' => $data['image'],
        ':creatif' => $data['creatif'],
    ]);
}

function delete(PDO $connexion, int $id): void
{
    // Supprime les associations de tags
    $sql = 'DELETE FROM projets_has_tags WHERE projet = :id';
    $rs = $connexion->prepare($sql);
    $rs->bindValue(':id', $id, PDO::PARAM_INT);
    $rs->execute();
    // Supprime ensuite le projet
    $sql = 'DELETE FROM projets WHERE id = :id';
    $rs = $connexion->prepare($sql);
    $rs->bindValue(':id', $id, PDO::PARAM_INT);
    $rs->execute();
}

function replaceTags(PDO $connexion, int $projetId, array $tagIds): void
{
    $connexion->prepare('DELETE FROM projets_has_tags WHERE projet = :projet')
        ->execute([':projet' => $projetId]);

    $rs = $connexion->prepare(
        'INSERT INTO projets_has_tags (projet, tag) VALUES (:projet, :tag)'
    );
    foreach ($tagIds as $tagId) {
        $rs->execute([':projet' => $projetId, ':tag' => (int) $tagId]);
    }
}
