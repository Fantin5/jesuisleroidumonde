-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 24 juin 2025 à 15:32
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `chef_marie_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `name_fr` varchar(255) NOT NULL,
  `comment_en` text NOT NULL,
  `comment_fr` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `name_en`, `name_fr`, `comment_en`, `comment_fr`, `created_at`) VALUES
(1, 'Sarah Johnson', 'Sarah Johnson', 'Chef Elodie created the most amazing dinner party for us! Every dish was perfectly crafted and the presentation was stunning. Highly recommend her services.', 'Chef Élodie a créé le plus incroyable dîner pour nous ! Chaque plat était parfaitement préparé et la présentation était époustouflante. Je recommande vivement ses services.', '2025-06-24 12:34:48'),
(2, 'Michael Brown', 'Michael Brown', 'Exceptional culinary skills! The flavors were incredible and the attention to detail was remarkable. Thank you for making our anniversary so special.', 'Compétences culinaires exceptionnelles ! Les saveurs étaient incroyables et l\'attention aux détails était remarquable. Merci d\'avoir rendu notre anniversaire si spécial.', '2025-06-24 12:34:48'),
(3, 'Emma Wilson', 'Emma Wilson', 'Outstanding service and delicious food. Chef Elodie understood exactly what we wanted and delivered beyond our expectations. Will definitely book again!', 'Service exceptionnel et nourriture délicieuse. Chef Élodie a parfaitement compris ce que nous voulions et a dépassé nos attentes. Nous réserverons certainement à nouveau !', '2025-06-24 12:34:48'),
(4, 'Test User', 'test', 'This is a test comment', 'grrrrr', '2025-06-24 12:53:37'),
(5, 'Sample User', 'sss', 'Sample comment text', 'ssss', '2025-06-24 13:10:09'),
(6, 'Quick User', 'qss', 'Quick test comment', 'sqqss', '2025-06-24 13:17:48');

-- --------------------------------------------------------

--
-- Structure de la table `meals`
--

CREATE TABLE `meals` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `title_fr` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `description_en` text DEFAULT NULL,
  `description_fr` text DEFAULT NULL,
  `category` varchar(20) NOT NULL DEFAULT 'cat1',
  `images` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `meals`
--

INSERT INTO `meals` (`id`, `title`, `title_en`, `title_fr`, `description`, `description_en`, `description_fr`, `category`, `images`, `created_at`) VALUES
(2, 'Chocolate Soufflé', 'Chocolate Soufflé', 'Chocolate Soufflé', 'An elegant and airy chocolate dessert that rises beautifully in the oven. Made with rich dark chocolate and perfectly whipped eggs, this soufflé is a true testament to the art of French pastry making.', 'An elegant and airy chocolate dessert that rises beautifully in the oven. Made with rich dark chocolate and perfectly whipped eggs, this soufflé is a true testament to the art of French pastry making.', 'An elegant and airy chocolate dessert that rises beautifully in the oven. Made with rich dark chocolate and perfectly whipped eggs, this soufflé is a true testament to the art of French pastry making.', 'cat1', '[\"uploads\\/6859678344c4c_1750689667.jpg\"]', '2025-06-23 12:30:11'),
(3, 'Coq au Vin', 'Coq au Vin', NULL, 'Tender chicken braised in white wine with pearl onions, mushrooms, and fresh herbs. This rustic yet refined dish embodies the soul of French country cooking with its rich flavors and comforting aroma.', 'Tender chicken braised in white wine with pearl onions, mushrooms, and fresh herbs. This rustic yet refined dish embodies the soul of French country cooking with its rich flavors and comforting aroma.', NULL, 'cat1', '[]', '2025-06-23 12:30:11'),
(11, 'ccc', 'ccc', NULL, 'ccc', 'ccc', NULL, 'cat1', '[\"uploads\\/68595abf6d981_1750686399.png\"]', '2025-06-23 13:46:39'),
(12, 'gggg', 'gggg', NULL, 'test', 'test', NULL, 'cat1', '[\"uploads\\/68595cc33984f_1750686915.png\"]', '2025-06-23 13:55:15'),
(14, 'fart', 'fart', 'prout', 'realy goog', 'realy goog', 'vrmtbon', 'cat1', '[\"uploads\\/685965127d1ca_1750689042.png\"]', '2025-06-23 14:30:15'),
(15, 'desserto', 'desserto', 'dessert', 'testo', 'testo', 'test', 'cat1', '[\"uploads\\/6859719f2d8b5_1750692255.png\"]', '2025-06-23 15:24:15'),
(16, 'desserto', 'desserto', 'dessert', 'testo', 'testo', 'test', 'cat3', '[\"uploads\\/68597ee704244_1750695655.png\",\"uploads\\/68597ee705bf1_1750695655.png\"]', '2025-06-23 16:20:55'),
(17, 'desserto', 'desserto', 'dessert', 'testo', 'testo', 'test', 'cat1', '[\"uploads\\/685982b40d3ae_1750696628.png\"]', '2025-06-23 16:37:08'),
(18, 'aaaa', 'aaaa', 'bbb', 'a', 'a', 'b', 'cat2', '[\"uploads\\/685988cd907e1_1750698189.png\"]', '2025-06-23 17:03:09');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Index pour la table `meals`
--
ALTER TABLE `meals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_meals_category` (`category`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `meals`
--
ALTER TABLE `meals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;