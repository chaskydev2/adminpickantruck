<style>
    .hover-scale {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .hover-scale:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        cursor: pointer;
    }
</style>

<div class="card shadow-sm mt-3">
    <div class="card-header bg-white">
        <h5 class="mb-0">Estadísticas de Pujas</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Total de Pujas -->
            <div class="col-6 col-md-4 col-lg mb-3">
                <a href="{{ route('bids.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100 hover-scale">
                        <div class="card-body text-center py-4">
                            <h3 class="mb-1 fw-bold">{{ number_format($bidsStats['total']) }}</h3>
                            <p class="mb-0 text-muted small">Total de Pujas</p>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Pujas Aceptadas -->
            <div class="col-6 col-md-4 col-lg mb-3">
                <a href="{{ route('bids.index', ['estado' => 'aceptado']) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100 hover-scale">
                        <div class="card-body text-center py-4">
                            <h3 class="mb-1 fw-bold">{{ number_format($bidsStats['aceptadas']) }}</h3>
                            <p class="mb-0 text-muted small">Pujas Aceptadas</p>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Pujas Rechazadas -->
            <div class="col-6 col-md-4 col-lg mb-3">
                <a href="{{ route('bids.index', ['estado' => 'rechazado']) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100 hover-scale">
                        <div class="card-body text-center py-4">
                            <h3 class="mb-1 fw-bold">{{ number_format($bidsStats['rechazadas']) }}</h3>
                            <p class="mb-0 text-muted small">Pujas Rechazadas</p>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Pujas Pendientes -->
            <div class="col-6 col-md-4 col-lg mb-3">
                <a href="{{ route('bids.index', ['estado' => 'pendiente']) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100 hover-scale">
                        <div class="card-body text-center py-4">
                            <h3 class="mb-1 fw-bold">{{ number_format($bidsStats['pendientes']) }}</h3>
                            <p class="mb-0 text-muted small">Pujas Pendientes</p>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Pujas Terminadas -->
            <div class="col-6 col-md-4 col-lg mb-3">
                <a href="{{ route('bids.index', ['estado' => 'completado']) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100 hover-scale">
                        <div class="card-body text-center py-4">
                            <h3 class="mb-1 fw-bold">{{ number_format($bidsStats['terminadas']) }}</h3>
                            <p class="mb-0 text-muted small">Pujas Terminadas</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
