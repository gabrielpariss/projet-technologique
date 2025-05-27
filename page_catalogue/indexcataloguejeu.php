<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Catalogue de Jeux de Société</title>
    <link rel="stylesheet" href="stylecataloguejeu.css"/>
    <script src="cataloguejeu.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
</head>
<body>
<?php
$host = 'localhost';
$db = 'Projet-Technologique';
$user = 'root';
$pass = 'root';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Tester la connexion avec une requête simple
    $testQuery = "SELECT COUNT(*) FROM jeux";
    $testStmt = $conn->prepare($testQuery);
    $testStmt->execute();
    $count = $testStmt->fetchColumn();

    echo "<!-- Connexion réussie. Nombre de jeux : $count -->";

} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupération des jeux avec gestion d'erreur
try {
    $SQLQuery = "SELECT j.*, g.libelle as genre_libelle, t.libelle as type_libelle 
                FROM jeux j 
                LEFT JOIN Genre g ON j.id_genre = g.id_genre 
                LEFT JOIN TypeJeu t ON j.id_type = t.id_type 
                ORDER BY j.nom";
    $SQLStmt = $conn->prepare($SQLQuery);
    $SQLStmt->execute();

    $jeux = $SQLStmt->fetchAll(PDO::FETCH_ASSOC);
    $SQLStmt->closeCursor();

    echo "<!-- Jeux récupérés : " . count($jeux) . " -->";

} catch (PDOException $e) {
    die("Erreur lors de la récupération des jeux : " . $e->getMessage());
}

// Pour débugger, décommentez la ligne suivante :
// var_dump($jeux);
?>

<!-- Header avec structure améliorée -->
<header>
    <div class="container header-content">
        <a href="#" class="logo">
            <img src="public/images/logo-titre.webp" alt="La Taverne du Jeu" class="logo-image"/>
        </a>
        <nav>
            <ul>
                <li><a href="../index.php" class="active">Accueil</a></li>
                <li><a href="indexcataloguejeu.php">catalogue</a></li>
                <li><a href="../evenements.php">Événements</a></li>
            </ul>
        </nav>
    </div>
</header>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h2>Découvrez les meilleurs jeux de société</h2>
        <p>Explorez notre catalogue complet pour trouver le jeu parfait pour vos soirées entre amis ou en famille.</p>
        <a href="#catalogue" class="cta-button">Explorer le catalogue</a>
    </div>
</section>

<!-- Contenu principal -->
<div class="main-content">
    <div class="container">
        <!-- Section recherche et filtres améliorée -->
        <section class="search-filter-section">
            <div class="search-row">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input
                            type="text"
                            id="search-input"
                            placeholder="Rechercher un jeu..."
                            aria-label="Rechercher un jeu"
                    />
                </div>
                <div class="filter-container">
                    <div class="filter-group">
                        <label for="category-filter">Catégorie</label>
                        <select id="category-filter">
                            <option value="">Toutes les catégories</option>
                            <!-- Options dynamiques ajoutées en JS -->
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="players-filter">Nombre de joueurs</label>
                        <select id="players-filter">
                            <option value="">Tous</option>
                            <!-- Options dynamiques ajoutées en JS -->
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="duration-filter">Durée</label>
                        <select id="duration-filter">
                            <option value="">Toutes durées</option>
                            <!-- Options dynamiques ajoutées en JS -->
                        </select>
                    </div>
                    <button class="reset-button" aria-label="Réinitialiser les filtres">
                        <i class="fas fa-redo"></i>
                        Réinitialiser
                    </button>
                </div>
            </div>
        </section>

        <!-- Section Bientôt disponibles -->
        <?php
        try {
            $queryBientot = "SELECT * FROM JeuxBientotDisponibles ORDER BY date_prevue ASC";
            $stmtBientot = $conn->prepare($queryBientot);
            $stmtBientot->execute();
            $bientotJeux = $stmtBientot->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<!-- Erreur jeux bientôt disponibles : " . $e->getMessage() . " -->";
            $bientotJeux = [];
        }
        ?>

        <?php if (count($bientotJeux) > 0): ?>
            <section class="coming-soon-section">
                <div class="section-header">
                    <h2>Bientôt disponibles</h2>
                </div>
                <div class="coming-soon-container">
                    <?php foreach ($bientotJeux as $jeuBientot): ?>
                        <div class="coming-soon-card">
                            <div class="coming-soon-image" style="background-image: url('<?= htmlspecialchars($jeuBientot['visuel']) ?>');"></div>
                            <h3><?= htmlspecialchars($jeuBientot['nom']) ?></h3>
                            <p class="release-date">
                                <i class="fas fa-calendar-alt"></i>
                                <strong>Date prévue :</strong> <?= htmlspecialchars($jeuBientot['date_prevue']) ?>
                            </p>
                            <p class="description"><?= nl2br(htmlspecialchars($jeuBientot['description'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <section class="games-section" id="catalogue">
            <div class="section-header">
                <h2>Notre catalogue de jeux (<?= count($jeux) ?> jeux)</h2>
            </div>

            <div id="games-container">
                <?php if (empty($jeux)): ?>
                    <div style="text-align: center; padding: 40px; background: white; border-radius: 10px; margin: 20px 0;">
                        <h3>Aucun jeu trouvé</h3>
                        <p>Il semble qu'il y ait un problème avec la base de données.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($jeux as $jeu): ?>
                        <div class="game-card" onclick="openPopup(<?= $jeu['id_jeu'] ?>)" data-game-id="<?= $jeu['id_jeu'] ?>">
                            <div class="game-image" style="background-image: url('<?= htmlspecialchars($jeu['visuel_principal']) ?>');"></div>
                            <div class="game-info">
                                <h3 class="game-title"><?= htmlspecialchars($jeu['nom']) ?></h3>
                                <p class="game-description"><?= htmlspecialchars($jeu['description_courte']) ?></p>
                            </div>
                        </div>

                        <div class="modal-overlay" id="modal-<?= $jeu['id_jeu'] ?>">
                            <div class="modal-content">
                                <button class="modal-close" onclick="closePopup(<?= $jeu['id_jeu'] ?>)" aria-label="Fermer la fenêtre">
                                    <i class="fas fa-times"></i>
                                </button>
                                <h2><?= htmlspecialchars($jeu['nom']) ?></h2>
                                <div class="modal-body">
                                    <img src="<?= htmlspecialchars($jeu['visuel_principal']) ?>" alt="<?= htmlspecialchars($jeu['nom']) ?>" class="popup-image">

                                    <div class="game-details">
                                        <p><strong><i class="fas fa-calendar"></i> Année de sortie :</strong> <?= $jeu['annee_sortie'] ?></p>

                                        <?php if (!empty($jeu['id_nb_joueurs_min']) && !empty($jeu['id_nb_joueurs_max'])): ?>
                                            <p><strong><i class="fas fa-users"></i> Nombre de joueurs :</strong>
                                                <?= $jeu['id_nb_joueurs_min'] ?> - <?= $jeu['id_nb_joueurs_max'] ?> joueurs</p>
                                        <?php endif; ?>

                                        <?php if (!empty($jeu['id_duree_partie'])): ?>
                                            <p><strong><i class="fas fa-clock"></i> Durée moyenne :</strong> <?= $jeu['id_duree_partie'] ?> minutes</p>
                                        <?php endif; ?>

                                        <?php if (!empty($jeu['id_age_minimum'])): ?>
                                            <p><strong><i class="fas fa-child"></i> Âge minimum :</strong> <?= $jeu['id_age_minimum'] ?> ans</p>
                                        <?php endif; ?>

                                        <?php if (!empty($jeu['categorie'])): ?>
                                            <p><strong><i class="fas fa-tag"></i> Catégorie :</strong> <?= htmlspecialchars($jeu['categorie']) ?></p>
                                        <?php endif; ?>

                                        <?php if (!empty($jeu['genre_libelle'])): ?>
                                            <p><strong><i class="fas fa-bookmark"></i> Genre :</strong> <?= htmlspecialchars($jeu['genre_libelle']) ?></p>
                                        <?php endif; ?>

                                        <?php if (!empty($jeu['type_libelle'])): ?>
                                            <p><strong><i class="fas fa-gamepad"></i> Type :</strong> <?= htmlspecialchars($jeu['type_libelle']) ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="game-resources">
                                        <h4><i class="fas fa-book-open"></i> Ressources</h4>
                                        <div class="resources-buttons">
                                            <?php if (!empty($jeu['lien_video_tuto'])): ?>
                                                <a href="<?= htmlspecialchars($jeu['lien_video_tuto']) ?>"
                                                   target="_blank"
                                                   class="resource-button video-button"
                                                   aria-label="Voir le tutoriel vidéo">
                                                    <i class="fab fa-youtube"></i>
                                                    Tutoriel vidéo
                                                </a>
                                            <?php endif; ?>

                                            <?php if (!empty($jeu['lien_regles_pdf'])): ?>
                                                <a href="<?= htmlspecialchars($jeu['lien_regles_pdf']) ?>"
                                                   target="_blank"
                                                   class="resource-button pdf-button"
                                                   aria-label="Télécharger les règles PDF">
                                                    <i class="fas fa-file-pdf"></i>
                                                    Règles PDF
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="game-description-long">
                                        <h4>Description</h4>
                                        <p><?= nl2br(htmlspecialchars($jeu['description_longue'])) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Message aucun résultat -->
            <div id="no-results" style="display: none;" role="alert" aria-live="polite">
                <i class="fas fa-search-minus"></i>
                <h3>Aucun jeu trouvé</h3>
                <p>
                    Désolé, le jeu que vous recherchez est indisponible pour le moment.
                    <br/>
                    Essayez de modifier vos critères de recherche.
                </p>
            </div>

            <!-- Pagination -->
            <div id="pagination" style="display: none;">
                <button id="prevPage" aria-label="Page précédente">
                    <i class="fas fa-chevron-left"></i> Précédent
                </button>
                <span id="currentPage" aria-live="polite" aria-atomic="true">Page 1</span>
                <button id="nextPage" aria-label="Page suivante">
                    Suivant <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </section>
    </div>
</div>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>La Taverne du Jeu</h3>
                <p>Votre destination pour découvrir les meilleurs jeux de société.</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 La Taverne du Jeu - Tous droits réservés</p>
        </div>
    </div>
</footer>
</body>
</html>
