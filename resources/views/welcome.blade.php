<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYWA Boutique - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #4A90E2 0%, #7FB3D3 100%);
            min-height: 100vh;
        }
        
        .hero-section {
            background: linear-gradient(135deg, rgba(74, 144, 226, 0.9) 0%, rgba(127, 179, 211, 0.9) 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 40px;
            opacity: 0.9;
        }
        
        .btn-custom {
            background: linear-gradient(45deg, #4A90E2, #7FB3D3);
            border: none;
            color: white;
            padding: 15px 30px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(74, 144, 226, 0.3);
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(74, 144, 226, 0.4);
            color: white;
        }
        
        .features-section {
            background: white;
            padding: 80px 0;
        }
        
        .feature-card {
            background: linear-gradient(135deg, #FFF5F0 0%, #FFF0E6 100%);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
            border: 2px solid #FFE4D6;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.2);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: #4A90E2;
            margin-bottom: 20px;
        }
        
        .feature-title {
            color: #4A90E2;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .cta-section {
            background: linear-gradient(135deg, #4A90E2 0%, #7FB3D3 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
        }
        
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
        }
        
        .nav-link {
            color: #4A90E2 !important;
            font-weight: 500;
            margin: 0 10px;
        }
        
        .nav-link:hover {
            color: #7FB3D3 !important;
        }
        
        .footer {
            background: #2C1810;
            color: white;
            padding: 40px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/" style="color: #4A90E2; font-size: 1.5rem;">
                🌟 MYWA Boutique
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/catalogue">Catalogue</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contact</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="/mon-compte">Mon Compte</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="/connexion">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/inscription">Inscription</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="hero-title">🌟 Découvrez MYWA Boutique</h1>
                    <p class="hero-subtitle">Des produits authentiques et de qualité, directement du Sénégal vers chez vous</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="/catalogue" class="btn btn-custom btn-lg">Voir nos Produits</a>
                        <a href="/contact" class="btn btn-outline-light btn-lg">Nous Contacter</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-4 fw-bold" style="color: #4A90E2;">Pourquoi nous choisir ?</h2>
                    <p class="lead text-muted">Découvrez ce qui fait notre différence</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">🛍️</div>
                        <h4 class="feature-title">Produits Authentiques</h4>
                        <p class="text-muted">Tous nos produits sont authentiques et proviennent directement du Sénégal, garantissant leur qualité et leur originalité.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">🚚</div>
                        <h4 class="feature-title">Livraison Rapide</h4>
                        <p class="text-muted">Livraison express partout au Sénégal avec un suivi en temps réel de votre commande.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">💳</div>
                        <h4 class="feature-title">Paiement Sécurisé</h4>
                        <p class="text-muted">Paiement en ligne sécurisé ou paiement à la livraison selon vos préférences.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">🎁</div>
                        <h4 class="feature-title">Cadeaux Originaux</h4>
                        <p class="text-muted">Idéal pour offrir des cadeaux uniques et authentiques à vos proches.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">🌍</div>
                        <h4 class="feature-title">Artisanat Local</h4>
                        <p class="text-muted">Soutenez les artisans locaux sénégalais et leur savoir-faire traditionnel.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">⭐</div>
                        <h4 class="feature-title">Service Client</h4>
                        <p class="text-muted">Une équipe dédiée pour vous accompagner dans vos achats et répondre à vos questions.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="display-5 fw-bold mb-4">Prêt à découvrir MYWA Boutique ?</h2>
                    <p class="lead mb-4">Rejoignez des milliers de clients satisfaits qui ont choisi notre boutique</p>
                    <a href="/catalogue" class="btn btn-light btn-lg px-5">Commencer mes Achats</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h5 class="mb-3">🌟 MYWA Boutique</h5>
                    <p class="mb-3">Votre boutique en ligne de produits authentiques du Sénégal</p>
                    <div class="d-flex justify-content-center gap-4 mb-3">
                        <a href="/catalogue" class="text-white text-decoration-none">Catalogue</a>
                        <a href="/contact" class="text-white text-decoration-none">Contact</a>
                        <a href="/mon-compte" class="text-white text-decoration-none">Mon Compte</a>
                    </div>
                    <p class="mb-0">&copy; 2024 MYWA Boutique. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
