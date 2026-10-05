<?php
/*
./app/vues/templates/default.php
*/

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../app/views/templates/partials/_head.php'; ?>
</head>

<body>

    <?php include '../app/views/templates/partials/_nav.php'; ?>

    <?php /* Par défaut (?? true), le hero s'affiche — c'est le cas de indexAction() qui ne définit pas $showHero (donc null → fallback true). */ ?>
    <?php if ($showHero ?? true): ?>
        <?php include '../app/views/templates/partials/_hero.php'; ?>
    <?php endif; ?>

    <?php include '../app/views/templates/partials/_main.php'; ?>

    <?php include '../app/views/templates/partials/_footer.php'; ?>

    <?php include '../app/views/templates/partials/_scripts.php'; ?>

</body>

</html>