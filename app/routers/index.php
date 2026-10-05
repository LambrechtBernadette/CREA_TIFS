<?php

// ROUTES PROJETS
// URL: ?projets=show&id=... | add-form | add-insert | edit-form | edit-update | delete
if (isset($_GET['projets'])):
    include_once '../app/routers/projets.php';

// ROUTE PAR DÉFAUT: Les 10 derniers projets
// PATTERN: /
// URL: ?
// CTRL: projetsController
// ACTION: index
else:
    include_once '../app/controllers/projetsController.php';
    \App\Controllers\ProjetsController\indexAction($connexion);
endif;
