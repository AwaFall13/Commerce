<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messages de contact - Administration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Messages de contact</h1>
        <a href="{{ route('admin.produits') }}" class="btn btn-secondary">Retour aux produits</a>
    </div>

    @if($messages->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $message)
                        <tr>
                            <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $message->name }}</td>
                            <td>{{ $message->email }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#messageModal{{ $message->id }}">
                                    Voir le message
                                </button>
                            </td>
                            <td>
                                <a href="mailto:{{ $message->email }}" class="btn btn-sm btn-primary">Répondre</a>
                            </td>
                        </tr>
                        
                        <!-- Modal pour afficher le message complet -->
                        <div class="modal fade" id="messageModal{{ $message->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Message de {{ $message->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Email :</strong> {{ $message->email }}</p>
                                        <p><strong>Date :</strong> {{ $message->created_at->format('d/m/Y à H:i') }}</p>
                                        <hr>
                                        <p><strong>Message :</strong></p>
                                        <p>{{ $message->message }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="mailto:{{ $message->email }}" class="btn btn-primary">Répondre par email</a>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $messages->links() }}
        </div>
    @else
        <div class="alert alert-info">
            Aucun message de contact pour le moment.
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 