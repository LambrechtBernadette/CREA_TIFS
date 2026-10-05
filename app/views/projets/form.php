<?php
/** @var array|null $projet */
/** @var bool $edit */
/** @var array $creatifs */

$action = $edit
    ? PUBLIC_BASE_URL . 'projects/' . (int) $projet['id'] . '/' . Core\Helpers\slugify($projet['titre']) . '/edit/update.html'
    : PUBLIC_BASE_URL . 'projects/add/insert.html';
?>

<div class="container" style="margin-top: 2.5rem">
  <div class="row">
    <div class="col-lg-8 py-3">
      <h1 class="mb-4"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>

      <form action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>" method="post" enctype="multipart/form-data" class="ct-form-card">
        <label for="title">Titre du projet</label>
        <input type="text" name="title" id="title" class="form-control" required value="<?= htmlspecialchars($projet['titre'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />

        <label for="text">Description</label>
        <textarea id="text" name="text" class="form-control" rows="5" required><?= htmlspecialchars($projet['texte'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

        <label for="image">Photo du résultat</label>
        <input type="file" class="form-control-file" id="image" name="image" accept="image/jpeg,image/png,image/gif" />

        <label for="category">Créa'tif</label>
        <select id="category" name="category_id" class="form-control" required>
          <option value="">Sélectionnez le créa'tif</option>
          <?php foreach ($creatifs as $creatif): ?>
            <option value="<?= (int) $creatif['id'] ?>" <?= (int) ($projet['creatif'] ?? 0) === (int) $creatif['id'] ? 'selected' : '' ?>><?= htmlspecialchars($creatif['pseudo'], ENT_QUOTES, 'UTF-8') ?></option>
          <?php endforeach; ?>
        </select>

        <?php $selectedTags = array_filter(explode('|', $projet['projet_tag_ids'] ?? '')); ?>
        <!--transforme en tableau des ID de tags sélectionnés pour pré-cocher les cases -->
        <label>Tags <span style="font-weight:400;font-size:.8rem">(facultatif)</span></label>
        <div class="ct-tag-choice">
          <?php foreach ([1 => 'Vintage', 2 => 'Alimentation', 3 => 'Géométrie', 4 => 'Couleur', 5 => 'Figuratif', 6 => 'Baptême', 7 => 'Abstract', 8 => 'Inclassable'] as $tagId => $tagName): ?>
            <label>
              <input type="checkbox" name="tags[]" value="<?= $tagId ?>" <?= in_array((string) $tagId, $selectedTags, true) ? 'checked' : '' ?> />
              <!--convertit l'ID (entier, ex: 3) en chaîne "3", car $selectedTags contient des chaînes (issues de explode() sur projet_tag_ids). Sans ce cast, la comparaison stricte 3 === "3" échouerait toujours. -->
              <?= htmlspecialchars($tagName, ENT_QUOTES, 'UTF-8') ?>
            </label>
          <?php endforeach; ?>
        </div>

        <div class="mt-3">
          <input class="ct-btn ct-btn--primary" type="submit" value="Enregistrer" />
          <a class="ct-btn ct-btn--ghost" href="<?= PUBLIC_BASE_URL ?>">Annuler</a>
        </div>
      </form>
    </div>
  </div>
</div>
