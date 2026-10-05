<?php

namespace App\Models\CreatifsModel;

use \PDO;

function findAll(PDO $connexion): array
{
    $sql = 'SELECT
                id,
                pseudo,
                bio,
                image
            FROM creatifs;';

    $rs = $connexion->query($sql);

    return $rs->fetchAll(PDO::FETCH_ASSOC);
}

function findOneById(PDO $connexion, int $id): ?array
{
    $sql = 'SELECT * FROM creatifs WHERE id = :id;';
    $rs = $connexion->prepare($sql);
    $rs->bindValue(':id', $id, PDO::PARAM_INT);
    $rs->execute();
    $creatif = $rs->fetch(PDO::FETCH_ASSOC);
    return $creatif !== false ? $creatif : null;
}
