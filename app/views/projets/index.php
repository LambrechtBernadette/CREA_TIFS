<?php
/** @var array $projets */
/** @var int $page */
/** @var int $totalPages */
?>

<div class="container ct-content-wrap">
      <div class="row">
        <!-- Liste des projets -->
        <div class="col-lg-8">
            <?php foreach ($projets as $projet):
              $slug = Core\Helpers\slugify($projet['titre']);
              $urlProjet = PUBLIC_BASE_URL . 'projets/' . (int) $projet['id'] . '/' . $slug . '.html';
              ?>

            <article class="ct-card">
            <div class="row">
              <div class="col-md-4">
                <a href="<?php echo htmlspecialchars($urlProjet, ENT_QUOTES, 'UTF-8'); ?>">
                  <img class="img-fluid mb-3 mb-md-0" src="<?php echo PUBLIC_BASE_URL; ?>images/<?php echo htmlspecialchars($projet['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($projet['titre'], ENT_QUOTES, 'UTF-8'); ?>" />
                </a>
              </div>
              <div class="col-md-8">
                <h3><a href="<?php echo htmlspecialchars($urlProjet, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($projet['titre'], ENT_QUOTES, 'UTF-8'); ?></a></h3>
                <p><?php echo htmlspecialchars(Core\Helpers\truncate($projet['texte'], 100), ENT_QUOTES, 'UTF-8'); ?></p>
                <a class="ct-btn ct-btn--primary ct-btn--sm" href="<?php echo htmlspecialchars($urlProjet, ENT_QUOTES, 'UTF-8'); ?>">Voir le projet</a>
              </div>
            </div>
          </article>

            <?php endforeach; ?>
          

          
          <!-- Pagination : 10 projets par page : chaque lien pointe vers l'URL avec le paramètre ?page=X correspondant, que le routeur et indexAction() liront ensuite via $_GET['page'].-->
          <nav aria-label="Navigation entre les pages de projets">
            <ul class="pagination ct-pagination" style="justify-content: center">
              <li class="page-item"><a class="page-link" href="?page=<?php echo max(1, $page - 1); ?>">Précédent</a></li>
              <?php for ($pageNumber = 1; $pageNumber <= $totalPages; $pageNumber++): ?>
                <li class="page-item <?php echo $pageNumber === $page ? 'active' : ''; ?>">
                  <a class="page-link" href="?page=<?php echo $pageNumber; ?>"><?php echo $pageNumber; ?></a>
                </li>
              <?php endfor; ?>
              <!--syntaxhe end for utilisée pour mélanger php et html au lieu d-->
              <li class="page-item"><a class="page-link" href="?page=<?php echo min($totalPages, $page + 1); ?>">Suivant</a></li>
            </ul>
          </nav>
        </div>

        <?php include '../app/views/templates/partials/_aside.php'; ?>
      </div>
      <!-- /.row -->
    </div>
