<?php
// Include database connection from api.php to get all comments
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chef_marie_db";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get all comments
    $stmt = $pdo->query("SELECT * FROM comments ORDER BY created_at DESC");
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $comments = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments - Chef Elodie</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy-dark: #1a2332;
            --navy-primary: #2c3e50;
            --white: #ffffff;
            --black: #1c1c1c;
            --gray-light: #f8f9fa;
            --gray-medium: #6c757d;
            --gray-dark: #495057;
            --accent-gold: #d4af37;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.7;
            color: var(--black);
            background: var(--white);
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Header */
        header {
            background: var(--navy-dark);
            color: var(--white);
            padding: 1.5rem 0;
            box-shadow: 0 2px 20px rgba(26, 35, 50, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--white);
            letter-spacing: -0.5px;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 3rem;
        }

        .nav-links a {
            color: var(--white);
            text-decoration: none;
            font-weight: 500;
            padding: 0.75rem 0;
            position: relative;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-gold);
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 100%;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--accent-gold);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .language-toggle {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 25px;
            padding: 0.4rem;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .language-toggle:hover {
            background: rgba(255,255,255,0.25);
            border-color: rgba(255,255,255,0.3);
            transform: translateY(-1px);
        }

        .language-option {
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: rgba(255,255,255,0.8);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-width: 40px;
            text-align: center;
        }

        .language-option.active {
            background: var(--accent-gold);
            color: var(--white);
            box-shadow: 0 2px 8px rgba(212, 175, 55, 0.4);
        }

        .language-option:not(.active):hover {
            color: var(--white);
            background: rgba(255,255,255,0.1);
        }

        /* Page Header */
        .page-header {
            text-align: center;
            padding: 6rem 0 4rem;
            background: var(--gray-light);
        }

        .page-header h1 {
            font-family: 'Playfair Display', serif;
            color: var(--navy-dark);
            font-size: 3rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .page-header p {
            color: var(--gray-medium);
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Comments Section */
        .comments-section {
            padding: 4rem 0;
        }

        .comments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .comment-card {
            background: var(--white);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(26, 35, 50, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 4px solid var(--accent-gold);
        }

        .comment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(26, 35, 50, 0.12);
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .comment-author {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            color: var(--navy-dark);
            font-weight: 600;
        }

        .comment-date {
            color: var(--gray-medium);
            font-size: 0.9rem;
        }

        .comment-text {
            color: var(--gray-dark);
            line-height: 1.6;
            font-style: italic;
            position: relative;
        }

        .comment-text::before {
            content: '"';
            font-size: 3rem;
            color: var(--accent-gold);
            position: absolute;
            top: -1rem;
            left: -0.5rem;
            font-family: 'Playfair Display', serif;
            opacity: 0.3;
        }

        .no-comments {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--gray-medium);
            font-style: italic;
        }

        /* Footer */
        footer {
            background: var(--navy-dark);
            color: var(--white);
            text-align: center;
            padding: 3rem 0;
            margin-top: 4rem;
        }

        footer p {
            opacity: 0.8;
            font-size: 0.95rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }

            .nav-links {
                gap: 1.5rem;
            }

            .comments-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .page-header h1 {
                font-size: 2.2rem;
            }

            .nav-right {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-links {
                gap: 1rem;
            }

            .language-toggle {
                order: -1;
            }
        }

        @media (max-width: 480px) {
            .nav-right {
                flex-direction: column;
                gap: 0.8rem;
                width: 100%;
            }

            .nav-links {
                order: 2;
                justify-content: center;
                width: 100%;
            }

            .language-toggle {
                order: 1;
                align-self: flex-end;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo" data-en="Chef Elodie" data-fr="Chef Élodie">Chef Elodie</div>
            <div class="nav-right">
                <ul class="nav-links">
                    <li><a href="index.php" data-en="Home" data-fr="Accueil">Home</a></li>
                    <li><a href="gallery.php" data-en="Gallery" data-fr="Galerie">Gallery</a></li>
                    <li><a href="about.php" data-en="About" data-fr="À Propos">About</a></li>
                    <li><a href="contact.php" data-en="Contact" data-fr="Contact">Contact</a></li>
                    <li><a href="comments.php" class="active" data-en="Comments" data-fr="Commentaires">Comments</a></li>
                </ul>
                <div class="language-toggle" onclick="toggleLanguage()">
                    <div class="language-option active" id="lang-en">EN</div>
                    <div class="language-option" id="lang-fr">FR</div>
                </div>
            </div>
        </nav>
    </header>

    <div class="page-header">
        <div class="container">
            <h1 data-en="What Our Clients Say" data-fr="Ce Que Disent Nos Clients">What Our Clients Say</h1>
            <p data-en="Read testimonials from those who have experienced Chef Elodie's culinary artistry" data-fr="Lisez les témoignages de ceux qui ont vécu l'art culinaire de Chef Élodie">Read testimonials from those who have experienced Chef Elodie's culinary artistry</p>
        </div>
    </div>

    <div class="container">
        <div class="comments-section">
            <?php if (empty($comments)): ?>
                <div class="no-comments">
                    <p data-en="No comments available at the moment. Be the first to share your experience!" data-fr="Aucun commentaire disponible pour le moment. Soyez le premier à partager votre expérience !">No comments available at the moment. Be the first to share your experience!</p>
                </div>
            <?php else: ?>
                <div class="comments-grid">
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment-card">
                            <div class="comment-header">
                                <div class="comment-author" 
                                     data-en="<?php echo htmlspecialchars($comment['name_en'] ?? $comment['name_fr'] ?? 'Anonymous'); ?>" 
                                     data-fr="<?php echo htmlspecialchars($comment['name_fr'] ?? $comment['name_en'] ?? 'Anonyme'); ?>">
                                    <?php echo htmlspecialchars($comment['name_en'] ?? $comment['name_fr'] ?? 'Anonymous'); ?>
                                </div>
                                <div class="comment-date">
                                    <?php echo date('F j, Y', strtotime($comment['created_at'])); ?>
                                </div>
                            </div>
                            <div class="comment-text" 
                                 data-en="<?php echo htmlspecialchars($comment['comment_en'] ?? $comment['comment_fr'] ?? 'No comment'); ?>" 
                                 data-fr="<?php echo htmlspecialchars($comment['comment_fr'] ?? $comment['comment_en'] ?? 'Aucun commentaire'); ?>">
                                <?php echo htmlspecialchars($comment['comment_en'] ?? $comment['comment_fr'] ?? 'No comment'); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <div class="container">
            <p data-en="&copy; 2024 Chef Elodie. All rights reserved." data-fr="&copy; 2024 Chef Élodie. Tous droits réservés.">&copy; 2024 Chef Elodie. All rights reserved.</p>
        </div>
    </footer>

    <script>
        let currentLanguage = 'en';

        // Load saved language preference
        document.addEventListener('DOMContentLoaded', function() {
            const savedLanguage = localStorage.getItem('preferred-language') || 'en';
            if (savedLanguage !== 'en') {
                currentLanguage = savedLanguage;
                updateLanguageToggle();
                translatePage();
            }
        });

        function toggleLanguage() {
            currentLanguage = currentLanguage === 'en' ? 'fr' : 'en';
            localStorage.setItem('preferred-language', currentLanguage);
            updateLanguageToggle();
            translatePage();
        }

        function updateLanguageToggle() {
            const enBtn = document.getElementById('lang-en');
            const frBtn = document.getElementById('lang-fr');
            
            if (currentLanguage === 'en') {
                enBtn.classList.add('active');
                frBtn.classList.remove('active');
            } else {
                frBtn.classList.add('active');
                enBtn.classList.remove('active');
            }
        }

        function translatePage() {
            const elements = document.querySelectorAll(`[data-${currentLanguage}]`);
            elements.forEach(element => {
                const translation = element.getAttribute(`data-${currentLanguage}`);
                if (translation) {
                    element.textContent = translation;
                }
            });

            // Update page language attribute
            document.documentElement.lang = currentLanguage;
        }
    </script>
</body>
</html>