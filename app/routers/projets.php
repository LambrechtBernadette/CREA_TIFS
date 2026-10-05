<?php

use \App\Controllers\ProjetsController;

include_once '../app/controllers/projetsController.php';

switch ($_GET['projets']):
    case 'show':
        ProjetsController\showAction($connexion, (int) $_GET['id']);
        break;
    case 'add-form':
        ProjetsController\addFormAction($connexion);
        break;
    case 'add-insert':
        ProjetsController\insertAction($connexion, $_POST, $_FILES);
        break;
    case 'edit-form':
        ProjetsController\editFormAction($connexion, (int) $_GET['id']);
        break;
    case 'edit-update':
        ProjetsController\updateAction($connexion, (int) $_GET['id'], $_POST, $_FILES);
        break;
    case 'delete':
        ProjetsController\deleteAction($connexion, (int) $_GET['id']);
        break;
    default:
        ProjetsController\indexAction($connexion);
        break;
endswitch;
