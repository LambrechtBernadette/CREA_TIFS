<?php

/*
 * ./app/views/projets/show.php
 * Variables dispo :
 * -$projet : ARRAY(id, titre, dateCreation, texte, image, creatif)
 */

/** @var array $projet */

$slug = Core\Helpers\slugify($projet['titre']);
?>


<div class="container ct-content-wrap">
  <div class="row">
    <div class="col-lg-8">
          <h1><?= htmlspecialchars($projet['titre'], ENT_QUOTES, 'UTF-8') ?></h1>
          <p class="ct-byline">par <a href="#"><?= htmlspecialchars($projet['creatif_pseudo'], ENT_QUOTES, 'UTF-8') ?></a> · <?= \Core\Helpers\dateFormator($projet['dateCreation']) ?></p>

          <div class="mb-4">
            <!-- routes: /projets/id/slug/edit/form.html — /projets/delete/id/slug.html -->
            <a href="<?= PUBLIC_BASE_URL ?>projects/<?= (int) $projet['id'] ?>/<?= $slug ?>/edit/form.html" class="ct-btn ct-btn--primary">Éditer le projet</a>
            <a href="<?= PUBLIC_BASE_URL ?>projets/delete/<?= (int) $projet['id'] ?>/<?= $slug ?>.html" class="ct-btn ct-btn--danger" onclick="return confirm('Supprimer définitivement ce projet ?');">Supprimer le projet</a>
          </div>

          <article class="ct-card">
            <div class="row">
              <div class="col-md-6">
                <img class="img-fluid mb-3 mb-md-0" src="<?= PUBLIC_BASE_URL ?>images/<?= htmlspecialchars($projet['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($projet['titre'], ENT_QUOTES, 'UTF-8') ?>" />
              </div>
              <div class="col-md-6">
                <p class="lead" style="font-weight: 600">
                  <?= nl2br(htmlspecialchars($projet['texte'], ENT_QUOTES, 'UTF-8')) ?>
                </p>
                <hr />
                <ul class="ct-tags">
                  <?php foreach (array_filter(explode('|', $projet['projet_tags'] ?? '')) as $tag): ?>
                    <li><a class="ct-tag" href="#"><?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') ?></a></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
          </article>
    </div>

    <?php include '../app/views/templates/partials/_aside.php'; ?>
  </div>
</div>