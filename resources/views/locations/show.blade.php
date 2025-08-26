@extends('layouts.app')

@section('title', $location->name . ' - Laravel Maps App')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-map-marker-alt me-2"></i>{{ $location->name }}</h1>
            <div>
                <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i>Editar
                </a>
                <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Mapa com localização -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-map me-2"></i>Localização no Mapa
                </h5>
            </div>
            <div class="card-body">
                <div id="map" style="height: 400px;"></div>
            </div>
        </div>
    </div>

    <!-- Detalhes do local -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Informações do Local
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nome:</label>
                    <p class="mb-0">{{ $location->name }}</p>
                </div>

                @if($location->category)
                <div class="mb-3">
                    <label class="form-label fw-bold">Categoria:</label>
                    <p class="mb-0">
                        <span class="badge bg-secondary">{{ $location->category }}</span>
                    </p>
                </div>
                @endif

                @if($location->description)
                <div class="mb-3">
                    <label class="form-label fw-bold">Descrição:</label>
                    <p class="mb-0">{{ $location->description }}</p>
                </div>
                @endif

                @if($location->address)
                <div class="mb-3">
                    <label class="form-label fw-bold">Endereço:</label>
                    <p class="mb-0">
                        <i class="fas fa-map-pin me-1"></i>{{ $location->address }}
                    </p>
                </div>
                @endif

                <div class="mb-3">
                    <label class="form-label fw-bold">Coordenadas:</label>
                    <p class="mb-0">
                        <i class="fas fa-location-arrow me-1"></i>
                        {{ $location->latitude }}, {{ $location->longitude }}
                    </p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Data de Criação:</label>
                    <p class="mb-0">{{ $location->created_at->format('d/m/Y H:i') }}</p>
                </div>

                @if($location->updated_at != $location->created_at)
                <div class="mb-3">
                    <label class="form-label fw-bold">Última Atualização:</label>
                    <p class="mb-0">{{ $location->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                @endif

                <hr>

                <div class="d-grid gap-2">
                    <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>Editar Local
                    </a>
                    <form action="{{ route('locations.destroy', $location->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100"
                                onclick="return confirm('Tem certeza que deseja excluir este local?')">
                            <i class="fas fa-trash me-1"></i>Excluir Local
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar o mapa
    var map = L.map('map').setView([{{ $location->latitude }}, {{ $location->longitude }}], 15);

    // Adicionar camada do OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Adicionar marcador do local
    var marker = L.marker([{{ $location->latitude }}, {{ $location->longitude }}])
        .addTo(map)
        .bindPopup(`
            <div class="text-center">
                <h6><strong>{{ $location->name }}</strong></h6>
                @if($location->category)
                    <p class="badge bg-secondary">{{ $location->category }}</p>
                @endif
                @if($location->address)
                    <p><i class="fas fa-map-pin"></i> {{ $location->address }}</p>
                @endif
                @if($location->description)
                    <p>{{ $location->description }}</p>
                @endif
            </div>
        `);

    // Abrir popup automaticamente
    marker.openPopup();
});
</script>
@endsection
