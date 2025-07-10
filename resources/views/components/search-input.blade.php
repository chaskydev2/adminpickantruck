@props([
    'placeholder' => 'Buscar...',
    'model' => 'search',
    'btnClass' => 'btn-primary',
    'icon' => 'search',
    'btnText' => 'Buscar',
    'size' => 'md',
    'rounded' => false,
    'shadow' => true,
])

@php
    $sizeClasses = [
        'sm' => 'py-1.5 text-sm',
        'md' => 'py-2.5',
        'lg' => 'py-3 text-lg',
    ][$size];
    
    $inputClasses = [
        'form-control',
        $sizeClasses,
        $rounded ? 'rounded-pill' : 'rounded-end',
        'border-end-0',
        'focus:ring-0',
        'focus:border-gray-300',
    ];
    
    $btnClasses = [
        'btn',
        $btnClass,
        $sizeClasses,
        $rounded ? 'rounded-start-0 rounded-end-pill' : 'rounded-start-0',
        'd-flex align-items-center justify-content-center',
        'transition-all',
    ];
    
    $containerClasses = [
        'search-container',
        'd-flex',
        'mb-4',
    ];
    
    if ($shadow) {
        $containerClasses[] = 'shadow-sm';
    }
@endphp

<div class="{{ implode(' ', array_filter($containerClasses)) }}">
    <div class="input-group">
        <input 
            type="text" 
            class="{{ implode(' ', $inputClasses) }}" 
            placeholder="{{ $placeholder }}"
            wire:model.live="{{ $model }}"
            @if(isset($id)) id="{{ $id }}" @endif
            @if(isset($name)) name="{{ $name }}" @endif
        >
        <button class="{{ implode(' ', $btnClasses) }}" type="submit">
            <i class="fas fa-{{ $icon }} me-1"></i>
            <span class="d-none d-sm-inline">{{ $btnText }}</span>
        </button>
    </div>
</div>

@push('styles')
<style>
    .search-container {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .search-container .form-control {
        border-color: #dee2e6;
        box-shadow: none;
        transition: all 0.3s ease;
    }
    
    .search-container .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }
    
    .search-container .btn {
        min-width: 100px;
    }
    
    .search-container .btn i {
        font-size: 0.9em;
    }
    
    /* Efecto hover en el botón */
    .search-container .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Efecto de enfoque en el input */
    .search-container:focus-within {
        transform: translateY(-1px);
    }
    
    /* Responsive */
    @media (max-width: 576px) {
        .search-container .btn span {
            display: none;
        }
        .search-container .btn {
            min-width: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .search-container .btn i {
            margin-right: 0 !important;
        }
    }
</style>
@endpush
