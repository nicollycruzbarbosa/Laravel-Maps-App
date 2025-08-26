@extends('layouts.app')

@section('title', 'Editar ' . $location->name . ' - Laravel Maps App')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-edit me-2"></i>Editar Local</h1>
            <a href="{{ route('locations.show', $location->id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Voltar
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Mapa para seleção de coordenadas -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-map me-2"></i>Atualizar Localização
                </h5>
            </div>
            <div class="card-body">
                <div id="map" style="height: 400px;"></div>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Clique no mapa para atualizar as coordenadas do local
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>Editar Informações
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('locations.update', $location->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome do Local *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $location->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3">{{ old('description', $location->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="latitude" class="form-label">Latitude *</label>
                                <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror"
                                       id="latitude" name="latitude" value="{{ old('latitude', $location->latitude) }}" required>
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="longitude" class="form-label">Longitude *</label>
                                <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror"
                                       id="longitude" name="longitude" value="{{ old('longitude', $location->longitude) }}" required>
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Endereço</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                               id="address" name="address" value="{{ old('address', $location->address) }}">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
</div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Categoria</label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category">
                            <option value="">Selecione uma categoria</option>
                            <option value="Restaurante" {{ old('category', $location->category) == 'Restaurante' ? 'selected' : '' }}>Restaurante</option>
                            <option value="Hotel" {{ old('category', $location->category) == 'Hotel' ? 'selected' : '' }}>Hotel</option>
                            <option value="Shopping" {{ old('category', $location->category) == 'Shopping' ? 'selected' : '' }}>Shopping</option>
                            <option value="Parque" {{ old('category', $location->category) == 'Parque' ? 'selected' : '' }}>Parque</option>
                            <option value="Museu" {{ old('category', $location->category) == 'Museu' ? 'selected' : '' }}>Museu</option>
                            <option value="Teatro" {{ old('category', $location->category) == 'Teatro' ? 'selected' : '' }}>Teatro</option>
                            <option value="Hospital" {{ old('category', $location->category) == 'Hospital' ? 'selected' : '' }}>Hospital</option>
                            <option value="Escola" {{ old('category', $location->category) == 'Escola' ? 'selected' : '' }}>Escola</option>
                            <option value="Outro" {{ old('category', $location->category) == 'Outro' ? 'selected' : '' }}>Outro</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i>Atualizar Local
                        </button>
                    </div>
                </form>
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

    // Adicionar marcador inicial
    var marker = L.marker([{{ $location->latitude }}, {{ $location->longitude }}]).addTo(map);

    // Evento de clique no mapa
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        // Atualizar campos de latitude e longitude
        document.getElementById('latitude').value = lat.toFixed(8);
        document.getElementById('longitude').value = lng.toFixed(8);

        // Mover marcador para nova posição
        marker.setLatLng([lat, lng]);
        marker.bindPopup(`
            <div class="text-center">
                <strong>Nova localização:</strong><br>
                Lat: ${lat.toFixed(8)}<br>
                Lng: ${lng.toFixed(8)}
            </div>
        `).openPopup();
    });
});
</script>
@endsection
