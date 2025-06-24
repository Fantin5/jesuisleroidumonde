<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Chef Elodie - Culinary Excellence</title>
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

        /* About Page - Enhanced Design */
        .page-header {
            text-align: center;
            padding: 8rem 0 6rem;
            background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-primary) 100%);
            color: var(--white);
            position: relative;
        }

        .page-header::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 80px;
            background: var(--white);
            clip-path: polygon(0 100%, 100% 100%, 100% 0, 0 80%);
        }

        .page-header h1 {
            font-family: 'Playfair Display', serif;
            color: var(--white);
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .page-header p {
            color: rgba(255,255,255,0.9);
            font-size: 1.3rem;
            max-width: 700px;
            margin: 0 auto;
            text-shadow: 0 1px 5px rgba(0,0,0,0.2);
        }

        .about-hero-section {
            padding: 0 0 6rem;
            background: var(--white);
            position: relative;
            z-index: 2;
        }

        .about-content {
            display: grid;
            grid-template-columns: 450px 1fr;
            gap: 6rem;
            align-items: start;
            padding: 4rem 0 0;
        }

        .about-photo {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(26, 35, 50, 0.2);
            transform: translateY(-50px);
            background: var(--gray-light);
        }

        .about-photo img {
            width: 100%;
            height: 600px;
            object-fit: cover;
            display: block;
            border-radius: 20px;
        }

        .about-photo::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(26, 35, 50, 0.1) 0%, transparent 50%);
            border-radius: 20px;
        }

        .about-text {
            padding-top: 2rem;
        }

        .about-text h2 {
            font-family: 'Playfair Display', serif;
            color: var(--navy-dark);
            margin-bottom: 3rem;
            font-size: 2.8rem;
            font-weight: 600;
            position: relative;
        }

        .about-text h2::after {
            content: '';
            position: absolute;
            bottom: -1rem;
            left: 0;
            width: 80px;
            height: 4px;
            background: var(--accent-gold);
            border-radius: 2px;
        }

        .about-text .paragraph-block {
            background: var(--white);
            padding: 2.5rem;
            margin-bottom: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(26, 35, 50, 0.08);
            border-left: 5px solid var(--navy-primary);
            position: relative;
            transition: all 0.3s ease;
        }

        .about-text .paragraph-block:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(26, 35, 50, 0.12);
            border-left-color: var(--accent-gold);
        }

        .about-text .paragraph-block::before {
            content: '';
            position: absolute;
            top: -1px;
            left: -1px;
            right: -1px;
            bottom: -1px;
            background: linear-gradient(45deg, var(--navy-primary), transparent, var(--accent-gold));
            border-radius: 15px;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .about-text .paragraph-block:hover::before {
            opacity: 0.1;
        }

        .about-text .paragraph-block p {
            margin: 0;
            color: var(--gray-dark);
            font-size: 1.1rem;
            line-height: 1.8;
            text-align: justify;
        }

        /* About Sections - Enhanced Design */
        .about-sections {
            padding: 8rem 0;
            background: var(--gray-light);
            position: relative;
        }

        .about-sections::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 80px;
            background: var(--white);
            clip-path: polygon(0 0, 100% 80%, 100% 100%, 0 100%);
        }

        .about-section {
            margin-bottom: 6rem;
            background: var(--white);
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(26, 35, 50, 0.1);
            position: relative;
            transition: all 0.4s ease;
        }

        .about-section:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 80px rgba(26, 35, 50, 0.15);
        }

        .about-section:last-child {
            margin-bottom: 0;
        }

        .about-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--navy-primary), var(--accent-gold), var(--navy-primary));
        }

        .about-section-content {
            padding: 4rem;
        }

        .about-section h3 {
            font-family: 'Playfair Display', serif;
            color: var(--navy-dark);
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
        }

        .about-section h3::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--navy-primary);
            border-radius: 2px;
        }

        .about-section .section-paragraphs {
            display: grid;
            gap: 2rem;
            margin-top: 3rem;
        }

        .about-section .paragraph-item {
            background: linear-gradient(135deg, rgba(44, 62, 80, 0.02) 0%, rgba(212, 175, 55, 0.05) 100%);
            padding: 2rem;
            border-radius: 15px;
            border-left: 4px solid var(--navy-primary);
            position: relative;
            transition: all 0.3s ease;
        }

        .about-section .paragraph-item:hover {
            background: linear-gradient(135deg, rgba(44, 62, 80, 0.05) 0%, rgba(212, 175, 55, 0.08) 100%);
            transform: translateX(10px);
            border-left-color: var(--accent-gold);
        }

        .about-section .paragraph-item::before {
            content: '';
            position: absolute;
            left: -4px;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, var(--navy-primary), var(--accent-gold));
            transition: all 0.3s ease;
        }

        .about-section .paragraph-item:hover::before {
            width: 6px;
            left: -6px;
        }

        .about-section .paragraph-item p {
            color: var(--gray-dark);
            font-size: 1.1rem;
            line-height: 1.8;
            margin: 0;
            text-align: justify;
        }

        /* Section Icons */
        .section-icon {
            display: inline-block;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--navy-primary), var(--accent-gold));
            border-radius: 50%;
            margin: 0 auto 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--white);
            box-shadow: 0 8px 25px rgba(44, 62, 80, 0.3);
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

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .about-content {
                grid-template-columns: 1fr;
                gap: 4rem;
                text-align: center;
            }

            .about-photo {
                transform: translateY(0);
                max-width: 500px;
                margin: 0 auto;
            }

            .about-photo img {
                height: 500px;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }

            .nav-links {
                gap: 1.5rem;
            }

            .page-header h1 {
                font-size: 2.5rem;
            }

            .about-content {
                gap: 3rem;
            }

            .about-text .paragraph-block {
                padding: 1.5rem;
            }

            .about-section-content {
                padding: 2rem;
            }

            .about-photo {
                transform: translateY(0);
            }

            .about-photo img {
                height: 400px;
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
                    <li><a href="about.php" class="active" data-en="About" data-fr="À Propos">About</a></li>
                    <li><a href="contact.php" data-en="Contact" data-fr="Contact">Contact</a></li>
                    <li><a href="comments.php" data-en="Comments" data-fr="Commentaires">Comments</a></li>
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
            <h1 data-en="About Chef Elodie" data-fr="À Propos du Chef Élodie">About Chef Elodie</h1>
            <p data-en="Discover the passion and expertise behind every dish" data-fr="Découvrez la passion et l'expertise derrière chaque plat">Discover the passion and expertise behind every dish</p>
        </div>
    </div>

    <div class="about-hero-section">
        <div class="container">
            <div class="about-content">
                <div class="about-photo">
                    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDUwIiBoZWlnaHQ9IjYwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48bGluZWFyR3JhZGllbnQgaWQ9ImciIHgxPSIwJSIgeTE9IjAlIiB4Mj0iMTAwJSIgeTI9IjEwMCUiPjxzdG9wIG9mZnNldD0iMCUiIHN0b3AtY29sb3I9IiNmOGY5ZmEiLz48c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNlOWVjZWYiLz48L2xpbmVhckdyYWRpZW50PjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2cpIi8+PGNpcmNsZSBjeD0iMjI1IiBjeT0iMjAwIiByPSI4MCIgZmlsbD0iIzFhMjMzMiIgb3BhY2l0eT0iMC4xIi8+PHRleHQgeD0iNTAlIiB5PSIzNSUiIGZvcnQtZmFtaWx5PSJQbGF5ZmFpciBEaXNwbGF5LCBzZXJpZiIgZm9udC1zaXplPSIyNCIgZmlsbD0iIzFhMjMzMiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iIGZvcnQtd2VpZ2h0PSI2MDAiPkNoZWYgw4lsb2RpZTwvdGV4dD48dGV4dCB4PSI1MCUiIHk9IjY1JSIgZm9udC1mYW1pbHk9IkludGVyLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE2IiBmaWxsPSIjNmM3NTdkIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+UGhvdG8gQ29taW5nIFNvb248L3RleHQ+PC9zdmc+" alt="Chef Élodie - About">
                </div>
                <div class="about-text">
                    <h2 data-en="A Culinary Journey" data-fr="Un Voyage Culinaire">A Culinary Journey</h2>
                    
                    <div class="paragraph-block">
                        <p data-en="Welcome to my kitchen, where every meal is prepared with love and attention to detail. My culinary journey began in my grandmother's kitchen, learning the secrets of traditional recipes passed down through generations." data-fr="Bienvenue dans ma cuisine, où chaque repas est préparé avec amour et attention aux détails. Mon voyage culinaire a commencé dans la cuisine de ma grand-mère, apprenant les secrets des recettes traditionnelles transmises de génération en génération.">Welcome to my kitchen, where every meal is prepared with love and attention to detail. My culinary journey began in my grandmother's kitchen, learning the secrets of traditional recipes passed down through generations.</p>
                    </div>
                    
                    <div class="paragraph-block">
                        <p data-en="Over the years, I have refined my skills and developed my own unique style that honors classic techniques while embracing fresh, seasonal ingredients. Cooking is not just about feeding the body – it's about nourishing the soul and creating moments of joy and connection." data-fr="Au fil des années, j'ai affiné mes compétences et développé mon propre style unique qui honore les techniques classiques tout en adoptant des ingrédients frais et de saison. Cuisiner ne consiste pas seulement à nourrir le corps – il s'agit de nourrir l'âme et de créer des moments de joie et de connexion.">Over the years, I have refined my skills and developed my own unique style that honors classic techniques while embracing fresh, seasonal ingredients. Cooking is not just about feeding the body – it's about nourishing the soul and creating moments of joy and connection.</p>
                    </div>
                    
                    <div class="paragraph-block">
                        <p data-en="Whether preparing an intimate family dinner or catering for special occasions, I believe that food has the power to bring people together and create lasting memories. Every dish I prepare is a reflection of my passion for culinary excellence and my commitment to using only the finest ingredients." data-fr="Que ce soit pour préparer un dîner familial intime ou pour des occasions spéciales, je crois que la nourriture a le pouvoir de rassembler les gens et de créer des souvenirs durables. Chaque plat que je prépare est le reflet de ma passion pour l'excellence culinaire et de mon engagement à utiliser uniquement les meilleurs ingrédients.">Whether preparing an intimate family dinner or catering for special occasions, I believe that food has the power to bring people together and create lasting memories. Every dish I prepare is a reflection of my passion for culinary excellence and my commitment to using only the finest ingredients.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="about-sections">
        <div class="container">
            <div class="about-section">
                <div class="about-section-content">
                    <div class="section-icon">🎓</div>
                    <h3 data-en="Professional Formation" data-fr="Formation Professionnelle">Professional Formation</h3>
                    <div class="section-paragraphs">
                        <div class="paragraph-item">
                            <p data-en="My culinary education began at the prestigious Culinary Arts Institute where I mastered the fundamentals of classical French cuisine. During my intensive training, I learned the importance of precision, timing, and respect for ingredients that would become the foundation of my cooking philosophy." data-fr="Ma formation culinaire a commencé au prestigieux Institut des Arts Culinaires où j'ai maîtrisé les fondamentaux de la cuisine française classique. Pendant ma formation intensive, j'ai appris l'importance de la précision, du timing et du respect des ingrédients qui deviendraient la base de ma philosophie culinaire.">My culinary education began at the prestigious Culinary Arts Institute where I mastered the fundamentals of classical French cuisine. During my intensive training, I learned the importance of precision, timing, and respect for ingredients that would become the foundation of my cooking philosophy.</p>
                        </div>
                        <div class="paragraph-item">
                            <p data-en="I continued my education through apprenticeships with renowned chefs in Michelin-starred restaurants across Europe. These experiences taught me the art of presentation, the science behind flavor combinations, and the discipline required to consistently deliver exceptional dishes that create lasting memories for every guest." data-fr="J'ai poursuivi ma formation par des apprentissages auprès de chefs renommés dans des restaurants étoilés Michelin à travers l'Europe. Ces expériences m'ont appris l'art de la présentation, la science derrière les combinaisons de saveurs, et la discipline nécessaire pour livrer constamment des plats exceptionnels qui créent des souvenirs durables pour chaque invité.">I continued my education through apprenticeships with renowned chefs in Michelin-starred restaurants across Europe. These experiences taught me the art of presentation, the science behind flavor combinations, and the discipline required to consistently deliver exceptional dishes that create lasting memories for every guest.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="about-section">
                <div class="about-section-content">
                    <div class="section-icon">💭</div>
                    <h3 data-en="Culinary Philosophy" data-fr="Philosophie Culinaire">Culinary Philosophy</h3>
                    <div class="section-paragraphs">
                        <div class="paragraph-item">
                            <p data-en="My approach to cooking is rooted in the belief that simplicity and quality are the cornerstones of exceptional cuisine. I believe in letting the natural flavors of premium ingredients shine through careful preparation and thoughtful combinations that enhance rather than mask their inherent beauty." data-fr="Mon approche de la cuisine est enracinée dans la conviction que la simplicité et la qualité sont les pierres angulaires d'une cuisine exceptionnelle. Je crois qu'il faut laisser les saveurs naturelles des ingrédients de qualité s'exprimer à travers une préparation soignée et des combinaisons réfléchies qui rehaussent plutôt que masquent leur beauté inhérente.">My approach to cooking is rooted in the belief that simplicity and quality are the cornerstones of exceptional cuisine. I believe in letting the natural flavors of premium ingredients shine through careful preparation and thoughtful combinations that enhance rather than mask their inherent beauty.</p>
                        </div>
                        <div class="paragraph-item">
                            <p data-en="Sustainability and seasonality guide every menu I create. I work closely with local farmers and artisans to source the finest seasonal produce, ensuring that each dish not only tastes exceptional but also supports our community and respects our environment for future generations." data-fr="La durabilité et la saisonnalité guident chaque menu que je crée. Je travaille étroitement avec les agriculteurs et artisans locaux pour me procurer les meilleurs produits de saison, en m'assurant que chaque plat non seulement a un goût exceptionnel mais soutient également notre communauté et respecte notre environnement pour les générations futures.">Sustainability and seasonality guide every menu I create. I work closely with local farmers and artisans to source the finest seasonal produce, ensuring that each dish not only tastes exceptional but also supports our community and respects our environment for future generations.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="about-section">
                <div class="about-section-content">
                    <div class="section-icon">❤️</div>
                    <h3 data-en="Passion & Purpose" data-fr="Passion et But">Passion & Purpose</h3>
                    <div class="section-paragraphs">
                        <div class="paragraph-item">
                            <p data-en="What drives me every day is the joy I see on people's faces when they experience a truly memorable meal. Food has this incredible power to transport us, to create connections, and to turn ordinary moments into treasured memories. This is what motivates me to continuously push the boundaries of my craft." data-fr="Ce qui me motive chaque jour, c'est la joie que je vois sur le visage des gens quand ils vivent un repas vraiment mémorable. La nourriture a ce pouvoir incroyable de nous transporter, de créer des connexions et de transformer des moments ordinaires en souvenirs précieux. C'est ce qui me motive à repousser continuellement les limites de mon art.">What drives me every day is the joy I see on people's faces when they experience a truly memorable meal. Food has this incredible power to transport us, to create connections, and to turn ordinary moments into treasured memories. This is what motivates me to continuously push the boundaries of my craft.</p>
                        </div>
                        <div class="paragraph-item">
                            <p data-en="My ultimate goal is to create culinary experiences that honor tradition while embracing innovation. Whether it's a intimate family gathering or a grand celebration, I pour my heart into every dish, ensuring that each bite tells a story and creates a moment of pure culinary bliss that will be remembered long after the last course is served." data-fr="Mon objectif ultime est de créer des expériences culinaires qui honorent la tradition tout en embrassant l'innovation. Qu'il s'agisse d'un rassemblement familial intime ou d'une grande célébration, je mets tout mon cœur dans chaque plat, en m'assurant que chaque bouchée raconte une histoire et crée un moment de pur bonheur culinaire dont on se souviendra longtemps après que le dernier service soit terminé.">My ultimate goal is to create culinary experiences that honor tradition while embracing innovation. Whether it's a intimate family gathering or a grand celebration, I pour my heart into every dish, ensuring that each bite tells a story and creates a moment of pure culinary bliss that will be remembered long after the last course is served.</p>
                        </div>
                    </div>
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

            // Update page language attribute
            document.documentElement.lang = currentLanguage;
        }
    </script>
</body>
</html>
