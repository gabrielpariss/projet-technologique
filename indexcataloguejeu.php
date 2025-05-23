<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Catalogue de Jeux de Société</title>
    <link rel="stylesheet" href="./stylecataloguejeu.css"/>
    <script src="./cataloguejeu.js" defer></script>

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
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage();
}

$SQLQuery = "SELECT * FROM jeux";
$SQLStmt = $conn->prepare($SQLQuery);
$SQLStmt->execute();

$jeux = $SQLStmt->fetchAll(PDO::FETCH_ASSOC);
$jeu = [
    'titre' => 'Aventurier du Rail',
    'image1' => 'images/aventurierdurail.webp'
];
$SQLStmt->closeCursor();

//var_dump($jeux);
?>

<!-- Header -->
<header>
    <div class="container header-content">
        <a href="#" class="logo">
            <img src="public/images/logo-titre.webp" alt="La Taverne du Jeu" class="logo-image"/>
        </a>
        <nav>
            <ul>
                <li><a href="index.php" class="active">Accueil</a></li>
                <li><a href="jeux.php">Jeux</a></li>
                <li><a href="evenements.php">Événements</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </div>
</header>

<section class="hero">
    <div class="container">
        <h2>Découvrez les meilleurs jeux de société</h2>
        <p>Explorez notre catalogue complet pour trouver le jeu parfait pour vos soirées entre amis ou en famille.</p>
        <a href="#" class="cta-button">Explorer le catalogue</a>
    </div>
</section>

<div class="main-content">
    <div class="container">
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
                </div>
                <button class="reset-button" aria-label="Réinitialiser les filtres">
                    <i class="fas fa-redo"></i> Réinitialiser les filtres
                </button>
            </div>
        </section>

        <!-- Bientôt disponibles - inchangé -->
        <section class="coming-soon-section">
            <div class="section-header">
                <h2>Bientôt disponibles</h2>
            </div>
            <div class="coming-soon-container">
                <?php
                $queryBientot = "SELECT * FROM JeuxBientotDisponibles ORDER BY date_prevue ASC";
                $stmtBientot = $conn->prepare($queryBientot);
                $stmtBientot->execute();
                $bientotJeux = $stmtBientot->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <section class="coming-soon-section">
                    <div class="section-header">
                    </div>
                    <div class="coming-soon-container">
                        <?php if (count($bientotJeux) > 0): ?>
                            <?php foreach ($bientotJeux as $jeu): ?>
                                <div class="coming-soon-card">
                                    <div class="coming-soon-image" style="background-image: url('<?= htmlspecialchars($jeu['visuel']) ?>');"></div>
                                    <h3><?= htmlspecialchars($jeu['nom']) ?></h3>
                                    <p class="release-date"><strong>Date prévue :</strong> <?= htmlspecialchars($jeu['date_prevue']) ?></p>
                                    <p class="description"><?= nl2br(htmlspecialchars($jeu['description'])) ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucun jeu bientôt disponible pour le moment.</p>
                        <?php endif; ?>
                    </div>
                </section>

            </div>
        </section>

        <!-- Catalogue de jeux -->
        <section class="games-section">
            <div class="section-header">
                <h2>Notre catalogue de jeux</h2>
            </div>

            <?php foreach ($jeux as $jeu): ?>
                <div class="game-card" onclick="openPopup(<?= $jeu['id_jeu'] ?>)">
                    <div class="game-image" style="background-image: url('<?= htmlspecialchars($jeu['visuel_principal']) ?>');"></div>

                    <div class="game-info">
                        <h3 class="game-title"><?= htmlspecialchars($jeu['nom']) ?></h3>
                        <p class="game-description"><?= htmlspecialchars($jeu['description_courte']) ?></p>
                    </div>
                </div>

                <div class="modal-overlay" id="modal-<?= $jeu['id_jeu'] ?>">
                    <div class="modal-content">
                        <button class="modal-close" onclick="closePopup(<?= $jeu['id_jeu'] ?>)">&times;</button>
                        <h2><?= htmlspecialchars($jeu['nom']) ?></h2>
                        <div>
                            <img src="<?= htmlspecialchars($jeu['visuel_principal']) ?>" alt="Image du jeu" class="popup-image">
                            <p><strong>Année de sortie :</strong> <?= $jeu['annee_sortie'] ?></p>
                            <p><?= nl2br(htmlspecialchars($jeu['description_longue'])) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>


            <div id="no-results" role="alert" aria-live="polite">
                <i class="fas fa-search-minus"></i>
                <h3>Aucun jeu trouvé</h3>
                <p>
                    Désolé, le jeu que vous recherchez est indisponible pour le moment.
                    <br/>
                    Essayez de modifier vos critères de recherche.
                </p>
            </div>

            <!-- Pagination -->
            <div id="pagination">
                <button id="prevPage" aria-label="Page précédente">Précédent</button>
                <span id="currentPage" aria-live="polite" aria-atomic="true">1</span>
                <button id="nextPage" aria-label="Page suivante">Suivant</button>
            </div>
        </section>
    </div>
</div>

<!-- Modal Overlay -->
<div
        id="modal-overlay"
        class="modal-overlay"
        aria-hidden="true"
        role="dialog"
        aria-labelledby="modal-title"
        aria-modal="true"
>
    <div class="modal-content">
        <button id="modal-close" class="modal-close" aria-label="Fermer la fenêtre">&times;</button>
        <h2 id="modal-title">Titre du jeu</h2>
        <div id="modal-body">
            <!-- Contenu injecté par JS -->
        </div>
    </div>
</div>

<!-- Footer - inchangé -->
<footer>
    <div class="container">
        <div class="footer-content">
            <!-- ton contenu footer ici -->
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 La taverne du jeu - Tous droits réservés</p>
        </div>
    </div>
</footer>
</body>
</html>
