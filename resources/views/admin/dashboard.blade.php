<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Administration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tableau de bord</h1>
        <div>
            <a href="{{ route('admin.produits') }}" class="btn btn-primary">
                <i class="fas fa-box"></i> Gérer les produits
            </a>
            <a href="{{ route('admin.orders') }}" class="btn btn-success">
                <i class="fas fa-shopping-cart"></i> Gérer les commandes
            </a>
            <a href="{{ route('admin.users') }}" class="btn btn-info">
                <i class="fas fa-users"></i> Gérer les utilisateurs
            </a>
            <a href="{{ route('admin.categories') }}" class="btn btn-warning">
                <i class="fas fa-tags"></i> Gérer les catégories
            </a>
        </div>
    </div>

    <!-- Statistiques générales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Chiffre d'affaires</h5>
                    <h3 class="card-text">{{ number_format($totalRevenue, 0, ',', ' ') }} F CFA</h3>
                    <small>Total des ventes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Commandes</h5>
                    <h3 class="card-text">{{ $totalOrders }}</h3>
                    <small>Total des commandes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Clients</h5>
                    <h3 class="card-text">{{ $totalCustomers }}</h3>
                    <small>Clients inscrits</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Produits</h5>
                    <h3 class="card-text">{{ $totalProducts }}</h3>
                    <small>Produits en catalogue</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Évolution des ventes (7 derniers jours)</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Produits les plus vendus</h5>
                </div>
                <div class="card-body">
                    <canvas id="productsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Dernières commandes -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Dernières commandes</h5>
                </div>
                <div class="card-body">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>N° Commande</th>
                                        <th>Client</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td><strong>{{ $order->order_number }}</strong></td>
                                            <td>{{ $order->user->name }}</td>
                                            <td>{{ number_format($order->total_amount, 0, ',', ' ') }} F CFA</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'shipped' ? 'primary' : ($order->status === 'delivered' ? 'success' : 'danger'))) }}">
                                                    {{ $order->status_label }}
                                                </span>
                                            </td>
                                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Aucune commande récente.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Statuts de paiement</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Payées</span>
                            <span class="badge bg-success">{{ $paidOrders }}</span>
                        </div>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-success" style="width: {{ $totalOrders > 0 ? ($paidOrders / $totalOrders) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>En attente</span>
                            <span class="badge bg-warning">{{ $pendingOrders }}</span>
                        </div>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-warning" style="width: {{ $totalOrders > 0 ? ($pendingOrders / $totalOrders) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Échouées</span>
                            <span class="badge bg-danger">{{ $failedOrders }}</span>
                        </div>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-danger" style="width: {{ $totalOrders > 0 ? ($failedOrders / $totalOrders) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Graphique des ventes
const salesCtx = document.getElementById('salesChart').getContext('2d');
new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($salesChartLabels) !!},
        datasets: [{
            label: 'Ventes (F CFA)',
            data: {!! json_encode($salesChartData) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Graphique des produits les plus vendus
const productsCtx = document.getElementById('productsChart').getContext('2d');
new Chart(productsCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($topProductsLabels) !!},
        datasets: [{
            data: {!! json_encode($topProductsData) !!},
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
</body>
</html> 