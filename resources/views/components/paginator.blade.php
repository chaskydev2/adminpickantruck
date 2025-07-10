@props(['paginator'])

@php
    // Asegurarse de que siempre tengamos elementos de paginación
    $elements = $paginator->hasPages() ? $paginator->links()->elements : [];
    
    // Si no hay suficientes elementos para paginar, forzamos la visualización del paginador
    $showPaginator = true;
    
    // Si hay una sola página y no hay elementos, ocultamos el paginador
    if ($paginator->lastPage() <= 1 && $paginator->total() === 0) {
        $showPaginator = false;
    }
@endphp

@if ($showPaginator)
    <div class="card border-light shadow-sm mb-3">
        <div class="card-body py-2">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                {{-- Mostrar por página --}}
                <div class="d-flex align-items-center">
                    <span class="me-2 text-muted small">Mostrar:</span>
                    <select class="form-select form-select-sm border-light" style="width: auto;" onchange="window.location.href=this.value">
                        @foreach([5, 10, 20] as $perPage)
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => $perPage]) }}" 
                                    {{ $paginator->perPage() == $perPage ? 'selected' : '' }}>
                                {{ $perPage }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="d-flex align-items-center">
                    {{-- Información de resultados --}}
                    <div class="me-3 text-muted small d-none d-md-block">
                        @if($paginator->total() > 0)
                            Mostrando {{ $paginator->firstItem() }} a {{ $paginator->lastItem() }} de {{ $paginator->total() }} resultados
                        @else
                            No hay resultados para mostrar
                        @endif
                    </div>
                    
                    {{-- Navegación - Solo mostrar si hay más de una página --}}
                    @if($paginator->lastPage() > 1)
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Botón Anterior --}}
                            @if ($paginator->onFirstPage())
                                <li class="page-item">
                                    <span class="page-link text-muted">&laquo;</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a href="{{ $paginator->previousPageUrl() }}" class="page-link text-primary" rel="prev">&laquo;</a>
                                </li>
                            @endif

                            {{-- Números de página --}}
                            @if(is_array($elements) && count($elements) > 0)
                                @foreach ($elements as $element)
                                    {{-- Separador "..." --}}
                                    @if (is_string($element))
                                        <li class="page-item">
                                            <span class="page-link text-muted">{{ $element }}</span>
                                        </li>
                                    @endif

                                    {{-- Array de enlaces --}}
                                    @if (is_array($element))
                                        @foreach ($element as $page => $url)
                                            @if ($page == $paginator->currentPage())
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $page }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a href="{{ $url }}" class="page-link text-primary">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif

                            {{-- Botón Siguiente --}}
                            @if ($paginator->hasMorePages())
                                <li class="page-item">
                                    <a href="{{ $paginator->nextPageUrl() }}" class="page-link text-primary" rel="next">&raquo;</a>
                                </li>
                            @else
                                <li class="page-item">
                                    <span class="page-link text-muted">&raquo;</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
