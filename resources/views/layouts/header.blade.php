<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container">
        <a class="navbar-brand" href="/">E-Commerce</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/accueil">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/catalogue">Catalogue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/panier">Panier</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact">Contact</a>
                </li>
                @if(session('user_id'))
                    <li class="nav-item">
                        <a class="nav-link" href="/order/history">Mes commandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/mon-compte">Mon compte</a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link">Bonjour, {{ \App\Models\User::find(session('user_id'))->name ?? 'Utilisateur' }}</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/deconnexion">Déconnexion</a>
                    </li>
                    @if(session('user_id') && (\App\Models\User::find(session('user_id'))->is_admin ?? false))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                Administration
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.produits') }}">Gérer les produits</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.orders') }}">Gérer les commandes</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.users') }}">Gérer les utilisateurs</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.categories') }}">Gérer les catégories</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.messages') }}">Messages de contact</a></li>
                            </ul>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="/connexion">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/inscription">Inscription</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> 