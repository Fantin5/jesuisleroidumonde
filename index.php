<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef Elodie - Culinary Excellence</title>
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
            --cat1-color: #e74c3c;
            --cat2-color: #f39c12;
            --cat3-color: #27ae60;
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

        /* Updated Language Toggle Styles */
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

        /* Page Styles */
        .page {
            display: none;
            min-height: calc(100vh - 80px);
        }

        .page.active {
            display: block;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-primary) 100%);
            color: var(--white);
            text-align: center;
            padding: 8rem 0;
            position: relative;
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            letter-spacing: -1px;
            line-height: 1.1;
        }

        .hero p {
            font-size: 1.3rem;
            max-width: 600px;
            margin: 0 auto 2.5rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .cta-button {
            display: inline-block;
            background: var(--white);
            color: var(--navy-dark);
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 0;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--accent-gold);
            transition: left 0.3s ease;
            z-index: -1;
        }

        .cta-button:hover::before {
            left: 0;
        }

        .cta-button:hover {
            color: var(--navy-dark);
            transform: translateY(-2px);
        }

        /* Welcome Section */
        .welcome-section {
            padding: 8rem 0;
            background: var(--white);
        }

        .welcome-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6rem;
            align-items: center;
        }

        .welcome-text h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            color: var(--navy-dark);
            margin-bottom: 2rem;
            font-weight: 600;
            line-height: 1.2;
        }

        .welcome-text p {
            font-size: 1.1rem;
            color: var(--gray-dark);
            margin-bottom: 1.5rem;
            line-height: 1.8;
        }

        .chef-photo img {
            width: 100%;
            height: auto;
            border-radius: 0;
            box-shadow: 0 20px 40px rgba(26, 35, 50, 0.15);
        }

        /* About Page */
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

        .about-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 4rem;
            padding: 4rem 0;
        }

        .about-text h2 {
            font-family: 'Playfair Display', serif;
            color: var(--navy-dark);
            margin-bottom: 2rem;
            font-size: 2.2rem;
            font-weight: 600;
        }

        .about-text p {
            margin-bottom: 1.5rem;
            color: var(--gray-dark);
            font-size: 1.05rem;
            line-height: 1.8;
        }

        /* Contact Page */
        .contact-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            padding: 4rem 0;
        }

        .contact-form,
        .contact-info {
            background: var(--white);
            padding: 3rem;
            border-radius: 0;
            box-shadow: 0 10px 30px rgba(26, 35, 50, 0.08);
        }

        .contact-form h3,
        .contact-info h3 {
            font-family: 'Playfair Display', serif;
            color: var(--navy-dark);
            margin-bottom: 2rem;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--navy-dark);
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--gray-light);
            border-radius: 0;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--white);
            color: var(--black);
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--navy-primary);
            box-shadow: 0 0 0 3px rgba(44, 62, 80, 0.1);
        }

        .btn {
            background: var(--navy-dark);
            color: var(--white);
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 0;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn:hover {
            background: var(--navy-primary);
            transform: translateY(-2px);
        }

        .contact-info p {
            margin-bottom: 1.5rem;
            color: var(--gray-dark);
            line-height: 1.7;
        }

        /* Footer */
        footer {
            background: var(--navy-dark);
            color: var(--white);
            text-align: center;
            padding: 3rem 0;
            margin-top: 0;
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

            .hero h1 {
                font-size: 2.5rem;
            }

            .welcome-grid,
            .about-content,
            .contact-content {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .page-header h1 {
                font-size: 2.2rem;
            }

            .section-title h2 {
                font-size: 2rem;
            }

            .welcome-text h2 {
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

            .category-filters {
                flex-direction: column;
                align-items: center;
                gap: 0.5rem;
            }

            .category-filter-btn {
                padding: 0.5rem 1rem;
                font-size: 0.8rem;
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

            .category-filters {
                gap: 0.25rem;
            }

            .image-carousel {
                height: 220px;
            }

            .gallery-item-content {
                padding: 1rem;
                min-height: 160px;
            }

            .gallery-item h3 {
                font-size: 1.2rem;
            }

            .gallery-item p {
                font-size: 0.9rem;
                -webkit-line-clamp: 3;
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
                    <li><a href="index.php" class="active" data-en="Home" data-fr="Accueil">Home</a></li>
                    <li><a href="gallery.php" data-en="Gallery" data-fr="Galerie">Gallery</a></li>
                    <li><a href="about.php" data-en="About" data-fr="À Propos">About</a></li>
                    <li><a href="contact.php" data-en="Contact" data-fr="Contact">Contact</a></li>
                </ul>
                <div class="language-toggle" onclick="toggleLanguage()">
                    <div class="language-option active" id="lang-en">EN</div>
                    <div class="language-option" id="lang-fr">FR</div>
                </div>
            </div>
        </nav>
    </header>

    <!-- HOME PAGE -->
    <div class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 data-en="Culinary Excellence" data-fr="Excellence Culinaire">Culinary Excellence</h1>
                <p data-en="Where passion meets precision in every carefully crafted dish" data-fr="Où la passion rencontre la précision dans chaque plat soigneusement préparé">Where passion meets precision in every carefully crafted dish</p>
                <a href="contact.php" class="cta-button" data-en="Get In Touch" data-fr="Contactez-Moi">Get In Touch</a>
            </div>
        </div>
    </div>

    <div class="welcome-section">
        <div class="container">
            <div class="welcome-grid">
                <div class="welcome-text">
                    <h2 data-en="Passion in Every Bite" data-fr="Passion dans Chaque Bouchée">Passion in Every Bite</h2>
                    <p data-en="With years of culinary passion and expertise, I create memorable dining experiences that bring family and friends together around the table." data-fr="Avec des années de passion et d'expertise culinaires, je crée des expériences gastronomiques mémorables qui rassemblent famille et amis autour de la table.">With years of culinary passion and expertise, I create memorable dining experiences that bring family and friends together around the table.</p>
                    <p data-en="Each recipe is a labor of love, combining time-honored techniques with fresh, quality ingredients to create dishes that warm the heart and soul." data-fr="Chaque recette est un travail d'amour, combinant des techniques éprouvées avec des ingrédients frais et de qualité pour créer des plats qui réchauffent le cœur et l'âme.">Each recipe is a labor of love, combining time-honored techniques with fresh, quality ingredients to create dishes that warm the heart and soul.</p>
                    <p data-en="From family dinners to special celebrations, discover the joy of homemade cuisine crafted with care." data-fr="Des dîners familiaux aux célébrations spéciales, découvrez la joie de la cuisine maison préparée avec soin.">From family dinners to special celebrations, discover the joy of homemade cuisine crafted with care.</p>
                </div>
                <div class="chef-photo">
                    <img src="chef-photo.jpg" alt="Chef Marie" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjQwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxOCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkNoZWYgTWFyaWU8L3RleHQ+PC9zdmc+'">
                </div>
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

            // Handle placeholders
            const placeholderElements = document.querySelectorAll(`[data-placeholder-${currentLanguage}]`);
            placeholderElements.forEach(element => {
                const placeholder = element.getAttribute(`data-placeholder-${currentLanguage}`);
                if (placeholder) {
                    element.placeholder = placeholder;
                }
            });

            // Update page language attribute
            document.documentElement.lang = currentLanguage;
        }
    </script>
</body>
</html>