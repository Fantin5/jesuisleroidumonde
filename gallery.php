<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery - Chef Elodie</title>
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

        /* Gallery Page */
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

        .gallery-section {
            padding: 8rem 0;
            background: var(--white);
        }

        /* Category Filter Buttons */
        .category-filters {
            display: flex;
            gap: 1rem;
            margin: 3rem 0;
            justify-content: center;
            flex-wrap: wrap;
        }

        .category-filter-btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid var(--gray-light);
            background: var(--white);
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
            color: var(--gray-dark);
        }

        .category-filter-btn.active,
        .category-filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .category-filter-btn.all.active,
        .category-filter-btn.all:hover {
            background: var(--navy-primary);
            border-color: var(--navy-primary);
            color: var(--white);
        }

        .category-filter-btn.cat1.active,
        .category-filter-btn.cat1:hover {
            background: var(--cat1-color);
            border-color: var(--cat1-color);
            color: var(--white);
        }

        .category-filter-btn.cat2.active,
        .category-filter-btn.cat2:hover {
            background: var(--cat2-color);
            border-color: var(--cat2-color);
            color: var(--white);
        }

        .category-filter-btn.cat3.active,
        .category-filter-btn.cat3:hover {
            background: var(--cat3-color);
            border-color: var(--cat3-color);
            color: var(--white);
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
            max-width: 100%;
        }

        .gallery-item {
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(26, 35, 50, 0.1);
            transition: all 0.4s ease;
            border: 1px solid rgba(26, 35, 50, 0.08);
            position: relative;
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
        }

        .image-carousel {
            position: relative;
            height: 280px;
            overflow: hidden;
            border-bottom: 1px solid rgba(26, 35, 50, 0.08);
            width: 100%;
        }

        .carousel-images {
            display: flex;
            height: 100%;
            transition: transform 0.5s ease;
            width: 100%;
        }

        .carousel-images img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            flex-shrink: 0;
            display: block;
        }

        .carousel-nav {
            position: absolute;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 0.5rem;
            z-index: 2;
        }

        .carousel-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .carousel-dot.active {
            background: var(--accent-gold);
            border-color: var(--accent-gold);
            box-shadow: 0 2px 8px rgba(212, 175, 55, 0.4);
        }

        .carousel-dot:hover:not(.active) {
            background: rgba(255,255,255,0.8);
            transform: scale(1.2);
        }

        .gallery-item-content {
            padding: 1.5rem;
            position: relative;
            z-index: 2;
            background: var(--white);
            min-height: 200px;
            display: flex;
            flex-direction: column;
        }

        .category-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
            color: white;
            width: fit-content;
        }

        .gallery-item h3 {
            font-family: 'Playfair Display', serif;
            color: var(--navy-dark);
            margin-bottom: 0.8rem;
            font-size: 1.3rem;
            font-weight: 600;
            line-height: 1.3;
            flex-shrink: 0;
        }

        .gallery-item p {
            color: var(--gray-dark);
            line-height: 1.6;
            font-size: 0.95rem;
            flex-grow: 1;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
        }

        .no-results {
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

            .gallery-grid {
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

            .gallery-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
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
                    <li><a href="index.php" data-en="Home" data-fr="Accueil">Home</a></li>
                    <li><a href="gallery.php" class="active" data-en="Gallery" data-fr="Galerie">Gallery</a></li>
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
    

    <div class="page-header">
        <div class="container">
            <h1 data-en="Culinary Creations" data-fr="Créations Culinaires">Culinary Creations</h1>
            <p data-en="A collection of lovingly crafted dishes that tell the story of flavor and tradition" data-fr="Une collection de plats préparés avec amour qui racontent l'histoire de la saveur et de la tradition">A collection of lovingly crafted dishes that tell the story of flavor and tradition</p>
        </div>
    </div>

    <div class="gallery-section">
        <div class="container">
            <!-- Category Filters -->
            <div class="category-filters">
                <button class="category-filter-btn all active" onclick="filterGalleryByCategory('all')" data-en="All Dishes" data-fr="Tous les Plats">All Dishes</button>
                <button class="category-filter-btn cat1" onclick="filterGalleryByCategory('cat1')" data-en="🍽️ Appetizers" data-fr="🍽️ Entrées">🍽️ Appetizers</button>
                <button class="category-filter-btn cat2" onclick="filterGalleryByCategory('cat2')" data-en="🥘 Main Courses" data-fr="🥘 Plats Principaux">🥘 Main Courses</button>
                <button class="category-filter-btn cat3" onclick="filterGalleryByCategory('cat3')" data-en="🍰 Desserts" data-fr="🍰 Desserts">🍰 Desserts</button>
            </div>

            <div id="galleryGrid" class="gallery-grid">
                <!-- Meals will be loaded dynamically -->
            </div>

            <div id="noResults" class="no-results" style="display: none;">
                <p data-en="No dishes found in this category yet." data-fr="Aucun plat trouvé dans cette catégorie pour le moment.">No dishes found in this category yet.</p>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p data-en="&copy; 2024 Chef Elodie. All rights reserved." data-fr="&copy; 2024 Chef Élodie. Tous droits réservés.">&copy; 2024 Chef Elodie. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Global variables
        let currentCarousels = {};
        let currentLanguage = 'en';
        let allMeals = [];
        let currentGalleryFilter = 'all';

        // Load saved language preference and initialize
        document.addEventListener('DOMContentLoaded', function() {
            const savedLanguage = localStorage.getItem('preferred-language') || 'en';
            if (savedLanguage !== 'en') {
                currentLanguage = savedLanguage;
                updateLanguageToggle();
                translatePage();
            }
            loadMeals();
        });

        function toggleLanguage() {
            currentLanguage = currentLanguage === 'en' ? 'fr' : 'en';
            localStorage.setItem('preferred-language', currentLanguage);
            updateLanguageToggle();
            translatePage();
            loadMeals(); // Reload meals to show in new language
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

            document.documentElement.lang = currentLanguage;
        }

        function filterGalleryByCategory(category) {
            currentGalleryFilter = category;
            
            // Update active filter button
            document.querySelectorAll('.category-filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`.category-filter-btn.${category}`).classList.add('active');
            
            // Display filtered meals
            displayMeals(allMeals);
        }

        function displayMeals(meals) {
            const gallery = document.getElementById('galleryGrid');
            const noResults = document.getElementById('noResults');
            gallery.innerHTML = '';

            if (!meals || meals.length === 0) {
                const noMealsMessage = currentLanguage === 'en' 
                    ? 'No meals available yet. Please check back later!'
                    : 'Aucun plat disponible pour le moment. Revenez plus tard !';
                gallery.innerHTML = `<p style="text-align: center; color: #666; padding: 2rem; grid-column: 1 / -1;">${noMealsMessage}</p>`;
                noResults.style.display = 'none';
                return;
            }

            // Filter meals based on current category filter
            const filteredMeals = currentGalleryFilter === 'all' 
                ? meals 
                : meals.filter(meal => (meal.category || 'cat1') === currentGalleryFilter);

            if (filteredMeals.length === 0) {
                gallery.innerHTML = '';
                noResults.style.display = 'block';
                return;
            }

            noResults.style.display = 'none';

            filteredMeals.forEach((meal, index) => {
                const mealElement = document.createElement('div');
                mealElement.className = `gallery-item ${meal.category || 'cat1'}`;
                
                // Get the appropriate language content with fallback
                const title = currentLanguage === 'en' 
                    ? (meal.title_en || meal.title || 'Untitled Dish')
                    : (meal.title_fr || meal.title || 'Plat sans titre');
                
                const description = currentLanguage === 'en' 
                    ? (meal.description_en || meal.description || 'No description available')
                    : (meal.description_fr || meal.description || 'Aucune description disponible');
                
                // Category display
                const categoryNames = {
                    'cat1': currentLanguage === 'en' ? 'Appetizers' : 'Entrées',
                    'cat2': currentLanguage === 'en' ? 'Main Courses' : 'Plats Principaux', 
                    'cat3': currentLanguage === 'en' ? 'Desserts' : 'Desserts'
                };
                
                const categoryName = categoryNames[meal.category || 'cat1'];
                
                // Create carousel for images - different structure for single vs multiple images
                let carouselHTML = '';
                const images = meal.images && meal.images.length > 0 ? meal.images : [];
                
                if (images.length > 1) {
                    // Multiple images - use carousel with navigation
                    carouselHTML = `
                        <div class="image-carousel">
                            <div class="carousel-images" id="carousel-${index}" style="transform: translateX(0%);">
                                ${images.map(img => `<img src="${img}" alt="${title}" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjI4MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjBmMGYwIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNiIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg=='">`).join('')}
                            </div>
                            <div class="carousel-nav">
                                ${images.map((_, i) => `<div class="carousel-dot ${i === 0 ? 'active' : ''}" onclick="showCarouselImage(${index}, ${i})"></div>`).join('')}
                            </div>
                        </div>
                    `;
                } else if (images.length === 1) {
                    // Single image - static display without carousel functionality
                    carouselHTML = `
                        <div class="image-carousel">
                            <img src="${images[0]}" alt="${title}" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjI4MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjBmMGYwIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNiIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg=='" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        </div>
                    `;
                } else {
                    // No images - placeholder
                    carouselHTML = `
                        <div class="image-carousel">
                            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjI4MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjBmMGYwIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNiIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg==" alt="${title}" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        </div>
                    `;
                }
                
                mealElement.innerHTML = `
                    ${carouselHTML}
                    <div class="gallery-item-content">
                        <div class="category-badge ${meal.category || 'cat1'}">${categoryName}</div>
                        <h3>${title}</h3>
                        <p>${description}</p>
                    </div>
                `;
                
                gallery.appendChild(mealElement);
                
                // Only initialize carousel for meals with multiple images
                if (images.length > 1) {
                    currentCarousels[index] = 0;
                    
                    // Auto-rotate carousel every 5 seconds
                    const carouselInterval = setInterval(() => {
                        if (document.getElementById(`carousel-${index}`)) {
                            const nextIndex = (currentCarousels[index] + 1) % images.length;
                            showCarouselImage(index, nextIndex);
                        } else {
                            clearInterval(carouselInterval);
                        }
                    }, 5000);
                }
            });
        }

        // Load meals from database
        async function loadMeals() {
            try {
                const response = await fetch('api.php?action=meals');
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    throw new Error("Response is not JSON");
                }
                
                const meals = await response.json();
                
                if (!Array.isArray(meals)) {
                    throw new Error("Invalid response format");
                }
                
                allMeals = meals; // Store all meals for filtering
                displayMeals(meals);
            } catch (error) {
                console.error('Failed to load meals:', error);
                const noMealsMessage = currentLanguage === 'en' 
                    ? 'Unable to load meals. Please try again later.'
                    : 'Impossible de charger les plats. Veuillez réessayer plus tard.';
                document.getElementById('galleryGrid').innerHTML = `<p style="text-align: center; color: #666; padding: 2rem;">${noMealsMessage}</p>`;
            }
        }

        function showCarouselImage(carouselIndex, imageIndex) {
            const carousel = document.getElementById(`carousel-${carouselIndex}`);
            if (!carousel) return;
            
            // Only proceed if this is actually a carousel (has carousel-images class)
            if (!carousel.classList.contains('carousel-images')) return;
            
            const parentElement = carousel.closest('.image-carousel');
            const dots = parentElement ? parentElement.querySelectorAll('.carousel-dot') : [];
            
            carousel.style.transform = `translateX(-${imageIndex * 100}%)`;
            currentCarousels[carouselIndex] = imageIndex;
            
            // Update dots
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === imageIndex);
            });
        }
    </script>
</body>
</html>
