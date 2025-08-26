@extends('layouts.app')

@section('title', 'Locais - Laravel Maps App')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-map-marker-alt me-2"></i>Locais Cadastrados</h1>
            <a href="{{ route('locations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Adicionar Local
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-map me-2"></i>Mapa Interativo
                </h5>
            </div>
            <div class="card-body">
                <div id="map"></div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Lista de Locais
                </h5>
            </div>
            <div class="card-body">
                @if($locations->count() > 0)
                    <div class="list-group">
                        @foreach($locations as $location)
                            <div class="list-group-item location-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $location->name }}</h6>
                                        @if($location->category)
                                            <span class="badge bg-secondary mb-2">{{ $location->category }}</span>
                                        @endif
                                        @if($location->address)
                                            <p class="mb-1 text-muted small">
                                                <i class="fas fa-map-pin me-1"></i>{{ $location->address }}
                                            </p>
                                        @endif
                                        <p class="mb-1 text-muted small">
                                            <i class="fas fa-location-arrow me-1"></i>
                                            {{ $location->latitude }}, {{ $location->longitude }}
                                        </p>
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('locations.show', $location->id) }}"
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('locations.edit', $location->id) }}"
                                           class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('locations.destroy', $location->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Tem certeza que deseja excluir este local?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Nenhum local cadastrado</h5>
                        <p class="text-muted">Clique em "Adicionar Local" para começar!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar o mapa
    var map = L.map('map').setView([-23.5505, -46.6333], 10); // São Paulo como centro inicial

    // Adicionar camada do OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Array para armazenar os marcadores
    var markers = [];

    // Dados dos locais do PHP
    var locations = {!! json_encode($locations) !!};

    // Adicionar marcadores para cada local
    locations.forEach(function(location) {
        var marker = L.marker([location.latitude, location.longitude])
            .addTo(map)
            .bindPopup(`
                <div class="text-center">
                    <h6><strong>${location.name}</strong></h6>
                    ${location.category ? `<p class="badge bg-secondary">${location.category}</p>` : ''}
                    ${location.address ? `<p><i class="fas fa-map-pin"></i> ${location.address}</p>` : ''}
                    ${location.description ? `<p>${location.description}</p>` : ''}
                    <div class="mt-2">
                        <a href="/locations/${location.id}" class="btn btn-sm btn-primary">Ver Detalhes</a>
                    </div>
                </div>
            `);

        markers.push(marker);
    });

    // Ajustar o zoom para mostrar todos os marcadores
    if (markers.length > 0) {
        var group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
    }
});
</script>
@endsection
