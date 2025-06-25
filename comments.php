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
            --navy-dark: #0a0f1a;
            --navy-primary: #1a2332;
            --navy-accent: #2c3e50;
            --white: #ffffff;
            --black: #000000;
            --gray-light: #f8f9fa;
            --gray-medium: #6c757d;
            --gray-dark: #495057;
            --electric-blue: #0066ff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.7;
            color: var(--white);
            background: var(--navy-dark);
            overflow-x: hidden;
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Header */
        header {
            background: var(--black);
            color: var(--white);
            padding: 1.5rem 0;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(20px);
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
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .nav-links a::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--electric-blue);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-links a:hover::before,
        .nav-links a.active::before {
            width: 100%;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--electric-blue);
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
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 25px;
            padding: 0.4rem;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .language-toggle:hover {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.3);
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
            background: var(--electric-blue);
            color: var(--white);
        }

        .language-option:not(.active):hover {
            color: var(--white);
            background: rgba(255,255,255,0.1);
        }

        /* Hero Section - Simplified */
        .hero-section {
            background: var(--navy-dark);
            color: var(--white);
            text-align: center;
            padding: 6rem 0 4rem;
            position: relative;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--white);
        }

        .hero-section p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 2rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        /* Stats Container - Updated */
        .stats-container {
            display: flex;
            justify-content: center;
            gap: 3rem;
            margin-top: 3rem;
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem;
            background: rgba(255,255,255,0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            min-width: 120px;
        }

        .stat-item:hover {
            background: rgba(255,255,255,0.15);
        }

        .stat-number {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--electric-blue);
            display: block;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
            color: var(--white);
        }

        /* Comments Section - Clean White Background */
        .comments-main {
            padding: 6rem 0;
            background: var(--white);
            color: var(--black);
        }

        .section-intro {
            text-align: center;
            margin-bottom: 4rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .section-intro h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--navy-dark);
            margin-bottom: 1.5rem;
            position: relative;
        }

        .section-intro h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--electric-blue);
            border-radius: 2px;
        }

        .section-intro p {
            font-size: 1.1rem;
            color: var(--gray-dark);
            line-height: 1.7;
        }

        /* Simple Comments Grid */
        .comments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .comment-card {
            background: var(--white);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(26, 35, 50, 0.1);
            border: 1px solid rgba(26, 35, 50, 0.05);
            transition: all 0.3s ease;
        }

        .comment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(26, 35, 50, 0.15);
            border-color: var(--electric-blue);
        }

        .comment-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .comment-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--electric-blue), var(--navy-primary));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--white);
            font-weight: 700;
            flex-shrink: 0;
        }

        .comment-info {
            flex: 1;
        }

        .comment-author {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            color: var(--navy-dark);
            font-weight: 600;
            margin-bottom: 0.3rem;
        }

        .comment-date {
            color: var(--gray-medium);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .comment-date::before {
            content: '📅';
            font-size: 0.8rem;
        }

        .comment-text {
            color: var(--gray-dark);
            line-height: 1.7;
            font-size: 1rem;
            padding: 1.5rem;
            background: var(--gray-light);
            border-radius: 10px;
            border-left: 4px solid var(--electric-blue);
            margin-top: 1rem;
            position: relative;
        }

        .comment-text::before {
            content: '"';
            font-size: 3rem;
            color: var(--electric-blue);
            position: absolute;
            top: -5px;
            left: 10px;
            font-family: 'Playfair Display', serif;
            opacity: 0.3;
            line-height: 1;
        }

        .comment-text::after {
            content: '"';
            font-size: 3rem;
            color: var(--electric-blue);
            position: absolute;
            bottom: -25px;
            right: 15px;
            font-family: 'Playfair Display', serif;
            opacity: 0.3;
            line-height: 1;
        }

        /* Remove Rating Stars - Replace with appreciation emoji */
        .comment-appreciation {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            justify-content: center;
            padding: 0.8rem;
            background: var(--gray-light);
            border-radius: 10px;
            font-size: 1.5rem;
        }

        /* CTA Section */
        .cta-section {
            background: var(--navy-primary);
            color: var(--white);
            padding: 4rem 0;
            text-align: center;
        }

        .cta-content {
            max-width: 600px;
            margin: 0 auto;
        }

        .cta-section h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            margin-bottom: 1.5rem;
        }

        .cta-section p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            line-height: 1.7;
        }

        .cta-button {
            display: inline-block;
            background: var(--electric-blue);
            color: var(--white);
            padding: 1rem 2.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .cta-button:hover {
            background: #0052cc;
            transform: translateY(-2px);
        }

        /* Footer */
        footer {
            background: var(--navy-dark);
            color: var(--white);
            text-align: center;
            padding: 3rem 0;
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

            .hero-section h1 {
                font-size: 2.5rem;
            }

            .stats-container {
                flex-direction: column;
                gap: 1.5rem;
                align-items: center;
            }

            .stat-item {
                min-width: 200px;
            }

            .comments-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .comment-card {
                padding: 1.5rem;
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

            .section-intro h2 {
                font-size: 2rem;
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

            .comment-card {
                padding: 1.2rem;
            }

            .comment-text {
                padding: 1rem;
            }

            .hero-section h1 {
                font-size: 2rem;
            }

            .cta-section h2 {
                font-size: 1.8rem;
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

    <div class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 data-en="What Our Clients Say" data-fr="Ce Que Disent Nos Clients">What Our Clients Say</h1>
                <p data-en="Discover the experiences and stories from those who have tasted the magic of Chef Elodie's culinary artistry" data-fr="Découvrez les expériences et histoires de ceux qui ont goûté à la magie de l'art culinaire de Chef Élodie">Discover the experiences and stories from those who have tasted the magic of Chef Elodie's culinary artistry</p>
                
                <div class="stats-container">
                    <div class="stat-item">
                        <span class="stat-number" id="totalComments"><?php echo count($comments); ?></span>
                        <span class="stat-label" data-en="Testimonials" data-fr="Témoignages">Testimonials</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">100%</span>
                        <span class="stat-label" data-en="Happy Clients" data-fr="Clients Satisfaits">Happy Clients</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">5+</span>
                        <span class="stat-label" data-en="Years Experience" data-fr="Années d'Expérience">Years Experience</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="comments-main">
        <div class="container">
            <div class="section-intro">
                <h2 data-en="Testimonials That Warm the Heart" data-fr="Témoignages Qui Réchauffent le Cœur">Testimonials That Warm the Heart</h2>
                <p data-en="Each review tells a story of memorable moments, exceptional flavors, and the joy that comes from sharing great food with loved ones." data-fr="Chaque avis raconte une histoire de moments mémorables, de saveurs exceptionnelles et de la joie qui vient du partage de bons plats avec ses proches.">Each review tells a story of memorable moments, exceptional flavors, and the joy that comes from sharing great food with loved ones.</p>
            </div>

            <?php if (empty($comments)): ?>
                <div class="no-comments">
                    <div class="no-comments-content">
                        <span class="no-comments-icon">💭</span>
                        <h3 data-en="No Reviews Yet" data-fr="Aucun Avis Pour Le Moment">No Reviews Yet</h3>
                        <p data-en="Be the first to share your experience with Chef Elodie's exceptional culinary creations. Your feedback helps us continue to deliver extraordinary dining experiences." data-fr="Soyez le premier à partager votre expérience avec les créations culinaires exceptionnelles de Chef Élodie. Vos commentaires nous aident à continuer d'offrir des expériences gastronomiques extraordinaires.">Be the first to share your experience with Chef Elodie's exceptional culinary creations. Your feedback helps us continue to deliver extraordinary dining experiences.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="comments-grid">
                    <?php foreach ($comments as $index => $comment): ?>
                        <div class="comment-card" style="animation-delay: <?php echo $index * 0.1; ?>s">
                            <div class="comment-header">
                                <div class="comment-avatar">
                                    <?php echo strtoupper(substr($comment['name_en'] ?? $comment['name_fr'] ?? 'A', 0, 1)); ?>
                                </div>
                                <div class="comment-info">
                                    <div class="comment-author" 
                                         data-en="<?php echo htmlspecialchars($comment['name_en'] ?? $comment['name_fr'] ?? 'Anonymous'); ?>" 
                                         data-fr="<?php echo htmlspecialchars($comment['name_fr'] ?? $comment['name_en'] ?? 'Anonyme'); ?>">
                                        <?php echo htmlspecialchars($comment['name_en'] ?? $comment['name_fr'] ?? 'Anonymous'); ?>
                                    </div>
                                    <div class="comment-date">
                                        <?php echo date('F  Y', strtotime($comment['created_at'])); ?>
                                    </div>
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

    <div class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2 data-en="Ready to Create Your Own Story?" data-fr="Prêt à Créer Votre Propre Histoire ?">Ready to Create Your Own Story?</h2>
                <p data-en="Experience the culinary excellence that has our clients raving. Let's create unforgettable moments together." data-fr="Découvrez l'excellence culinaire qui fait l'éloge de nos clients. Créons ensemble des moments inoubliables.">Experience the culinary excellence that has our clients raving. Let's create unforgettable moments together.</p>
                <a href="contact.php" class="cta-button" data-en="Book Your Experience" data-fr="Réservez Votre Expérience">Book Your Experience</a>
            </div>
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