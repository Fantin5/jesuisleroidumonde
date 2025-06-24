<?php
// Include PHPMailer classes
require_once 'mail/PHPMailer/Exception.php';
require_once 'mail/PHPMailer/PHPMailer.php';
require_once 'mail/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$message = '';
$messageType = '';

// Check if form was submitted
if ($_POST) {
    // Get form data
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = strip_tags(trim($_POST["phone"]));
    $eventType = strip_tags(trim($_POST["event-type"]));
    $guests = strip_tags(trim($_POST["guests"]));
    $date = strip_tags(trim($_POST["date"]));
    $userMessage = strip_tags(trim($_POST["message"]));

    // Validate form data
    if (empty($name) || empty($userMessage) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "validation_error";
        $messageType = 'error';
    } else {
        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'deschaumeselodie@gmail.com';
            $mail->Password   = 'qghypbtuftgqqzuw';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('deschaumeselodie@gmail.com', 'Chef Elodie Contact Form');
            $mail->addAddress('deschaumeselodie@gmail.com', 'Chef Elodie');
            $mail->addReplyTo($email, $name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'New Contact Form Submission - Chef Elodie';
            
            // Create HTML email body
            $emailBody = "
            <html>
            <head>
                <title>New Contact Form Submission</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: #1a2332; color: white; padding: 20px; text-align: center; }
                    .content { padding: 20px; background: #f8f9fa; }
                    .field { margin-bottom: 15px; }
                    .label { font-weight: bold; color: #1a2332; }
                    .value { margin-top: 5px; }
                    .message-box { background: white; padding: 15px; border-left: 4px solid #d4af37; margin-top: 15px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>New Contact Form Submission</h2>
                        <p>Chef Elodie Website</p>
                    </div>
                    <div class='content'>
                        <div class='field'>
                            <div class='label'>Name:</div>
                            <div class='value'>{$name}</div>
                        </div>
                        <div class='field'>
                            <div class='label'>Email:</div>
                            <div class='value'>{$email}</div>
                        </div>";
            
            if (!empty($phone)) {
                $emailBody .= "
                        <div class='field'>
                            <div class='label'>Phone:</div>
                            <div class='value'>{$phone}</div>
                        </div>";
            }
            
            if (!empty($eventType)) {
                $emailBody .= "
                        <div class='field'>
                            <div class='label'>Occasion:</div>
                            <div class='value'>{$eventType}</div>
                        </div>";
            }
            
            if (!empty($guests)) {
                $emailBody .= "
                        <div class='field'>
                            <div class='label'>Number of Guests:</div>
                            <div class='value'>{$guests}</div>
                        </div>";
            }
            
            if (!empty($date)) {
                $emailBody .= "
                        <div class='field'>
                            <div class='label'>Preferred Date:</div>
                            <div class='value'>{$date}</div>
                        </div>";
            }
            
            $emailBody .= "
                        <div class='message-box'>
                            <div class='label'>Message:</div>
                            <div class='value'>" . nl2br($userMessage) . "</div>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            ";
            
            $mail->Body = $emailBody;
            $mail->AltBody = "New Contact Form Submission\n\nName: {$name}\nEmail: {$email}\nPhone: {$phone}\nOccasion: {$eventType}\nGuests: {$guests}\nDate: {$date}\nMessage: {$userMessage}";

            // Send the email
            $mail->send();
            $message = "email_success";
            $messageType = 'success';
        } catch (Exception $e) {
            error_log("Mailer Error: {$mail->ErrorInfo}");
            $message = "email_error";
            $messageType = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Chef Elodie</title>
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

        /* Contact Content */
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

        /* Message Styles */
        .message {
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-radius: 8px;
            font-weight: 500;
            text-align: center;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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

            .contact-content {
                grid-template-columns: 1fr;
                gap: 3rem;
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

        /* Contact Info Styles */
        .services-list ul {
            list-style-type: disc;
            margin-left: 1.5rem;
            line-height: 1.8;
        }

        .services-list li {
            margin-bottom: 0.5rem;
            color: var(--gray-dark);
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
                    <li><a href="gallery.php" onclick="navigateToSection('gallery')" data-en="Gallery" data-fr="Galerie">Gallery</a></li>
                    <li><a href="about.php" onclick="navigateToSection('about')" data-en="About" data-fr="À Propos">About</a></li>
                    <li><a href="contact.php" class="active" data-en="Contact" data-fr="Contact">Contact</a></li>
                    <li><a href="comments.php" data-en="Comments" data-fr="Commentaires">Comments</a></li>
                </ul>
                <div class="language-toggle" onclick="toggleLanguage()">
                    <div class="language-option active" id="lang-en">EN</div>
                    <div class="language-option" id="lang-fr">FR</div>
                </div>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="page-header">
            <h1 data-en="Get In Touch" data-fr="Contactez-Moi">Get In Touch</h1>
            <p data-en="Ready to create something extraordinary together? Let's discuss your culinary needs" data-fr="Prêt à créer quelque chose d'extraordinaire ensemble ? Discutons de vos besoins culinaires">Ready to create something extraordinary together? Let's discuss your culinary needs</p>
        </div>

        <div class="contact-content">
            <div class="contact-form">
                <h3 data-en="Send a Message" data-fr="Envoyer un Message">Send a Message</h3>
                
                <?php if ($message): ?>
                    <div class="message <?php echo $messageType; ?>">
                        <span id="messageText" 
                              data-en="<?php echo getEnglishMessage($message); ?>" 
                              data-fr="<?php echo getFrenchMessage($message); ?>">
                            <?php echo getEnglishMessage($message); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name" data-en="Full Name" data-fr="Nom Complet">Full Name</label>
                        <input type="text" id="name" name="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="email" data-en="Email Address" data-fr="Adresse Email">Email Address</label>
                        <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone" data-en="Phone Number" data-fr="Numéro de Téléphone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="event-type" data-en="Occasion" data-fr="Occasion">Occasion</label>
                        <input type="text" id="event-type" name="event-type" placeholder="" data-placeholder-en="e.g., Dinner party, Wedding, Corporate event" data-placeholder-fr="ex. Dîner, Mariage, Événement d'entreprise" value="<?php echo isset($_POST['event-type']) ? htmlspecialchars($_POST['event-type']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="guests" data-en="Number of Guests" data-fr="Nombre d'Invités">Number of Guests</label>
                        <input type="number" id="guests" name="guests" placeholder="" data-placeholder-en="How many people?" data-placeholder-fr="Combien de personnes ?" value="<?php echo isset($_POST['guests']) ? htmlspecialchars($_POST['guests']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="date" data-en="Preferred Date" data-fr="Date Préférée">Preferred Date</label>
                        <input type="date" id="date" name="date" value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="message" data-en="Message" data-fr="Message">Message</label>
                        <textarea id="message" name="message" rows="5" placeholder="" data-placeholder-en="Tell me about your event, dietary preferences, favorite dishes, and any special requests..." data-placeholder-fr="Parlez-moi de votre événement, de vos préférences alimentaires, de vos plats préférés et de toute demande spéciale..." required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    </div>
                    <button type="submit" class="btn" data-en="Send Message" data-fr="Envoyer le Message">Send Message</button>
                </form>
            </div>

            <div class="contact-info">
                <h3 data-en="Contact Information" data-fr="Informations de Contact">Contact Information</h3>
                <p><strong data-en="Email:" data-fr="Email :">Email:</strong><br>chef.elodie@example.com</p>
                <p><strong data-en="Phone:" data-fr="Téléphone :">Phone:</strong><br>+33 (0) 1 23 45 67 89</p>
                <p><strong data-en="Response Time:" data-fr="Temps de Réponse :">Response Time:</strong><br><span data-en="I typically respond within 24 hours to all inquiries" data-fr="Je réponds généralement dans les 24 heures à toutes les demandes">I typically respond within 24 hours to all inquiries</span></p>
                
                <h3 data-en="Service Areas" data-fr="Zones de Service">Service Areas</h3>
                <p data-en="Île-de-France region and surrounding areas. Travel arrangements can be made for special events outside the region." data-fr="Région Île-de-France et zones environnantes. Des arrangements de voyage peuvent être pris pour des événements spéciaux en dehors de la région.">Île-de-France region and surrounding areas. Travel arrangements can be made for special events outside the region.</p>
                
                <h3 data-en="Services Offered" data-fr="Services Offerts">Services Offered</h3>
                <div class="services-list">
                    <ul data-en-list="true" style="display: block;">
                        <li>Private dinner parties</li>
                        <li>Corporate events</li>
                        <li>Wedding catering</li>
                        <li>Cooking classes</li>
                        <li>Menu consultation</li>
                        <li>Special dietary accommodations</li>
                    </ul>
                    <ul data-fr-list="true" style="display: none;">
                        <li>Dîners privés</li>
                        <li>Événements d'entreprise</li>
                        <li>Traiteur pour mariages</li>
                        <li>Cours de cuisine</li>
                        <li>Consultation de menu</li>
                        <li>Accommodations alimentaires spéciales</li>
                    </ul>
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

            // Handle services lists
            const enList = document.querySelector('[data-en-list]');
            const frList = document.querySelector('[data-fr-list]');
            
            if (currentLanguage === 'en') {
                if (enList) enList.style.display = 'block';
                if (frList) frList.style.display = 'none';
            } else {
                if (enList) enList.style.display = 'none';
                if (frList) frList.style.display = 'block';
            }

            // Update page language attribute
            document.documentElement.lang = currentLanguage;
        }

        function navigateToSection(section) {
            window.location.href = `index.php#${section}`;
        }
    </script>
</body>
</html>

<?php
// Helper functions for message translation
function getEnglishMessage($messageKey) {
    $messages = [
        'email_success' => 'Thank you for your message! I will get back to you within 24 hours.',
        'email_error' => 'Sorry, there was an error sending your message. Please try again.',
        'validation_error' => 'Please fill in all required fields with valid information.'
    ];
    return isset($messages[$messageKey]) ? $messages[$messageKey] : $messageKey;
}

function getFrenchMessage($messageKey) {
    $messages = [
        'email_success' => 'Merci pour votre message ! Je vous recontacterai dans les 24 heures.',
        'email_error' => 'Désolé, il y a eu une erreur lors de l\'envoi de votre message. Veuillez réessayer.',
        'validation_error' => 'Veuillez remplir tous les champs obligatoires avec des informations valides.'
    ];
    return isset($messages[$messageKey]) ? $messages[$messageKey] : $messageKey;
}
?>
