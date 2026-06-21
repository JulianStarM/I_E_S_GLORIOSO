{{-- Reusable modal component --}}
{{-- Usage: @include('components.modal', ['id' => 'miModal', 'title' => 'Titulo', 'size' => 'lg']) --}}
@props(['id' => 'modal', 'title' => '', 'size' => 'lg'])

@php
    $maxW = match($size) {
        'sm' => 'max-w-md',
        'md' => 'max-w-xl',
        'lg' => 'max-w-3xl',
        'xl' => 'max-w-5xl',
        'full' => 'max-w-6xl',
        default => 'max-w-3xl',
    };
@endphp

<div id="{{ $id }}" class="fixed inset-0 z-[999] hidden" role="dialog" aria-modal="true">
    {{-- Overlay --}}
    <div class="modal-overlay fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity duration-300 opacity-0" onclick="closeModal('{{ $id }}')"></div>
    
    {{-- Panel --}}
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="modal-panel pointer-events-auto w-full {{ $maxW }} bg-white rounded-2xl shadow-2xl transform transition-all duration-300 scale-95 opacity-0 max-h-[90vh] flex flex-col">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 shrink-0">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2" id="{{ $id }}-title">
                    {{ $title }}
                </h3>
                <button type="button" onclick="closeModal('{{ $id }}')" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>
            
            {{-- Body --}}
            <div class="flex-1 overflow-y-auto px-6 py-4" id="{{ $id }}-body">
                {{ $slot ?? '' }}
            </div>
            
            {{-- Footer (optional, for buttons) --}}
            <div class="px-6 py-4 border-t border-slate-100 shrink-0 hidden" id="{{ $id }}-footer">
            </div>
        </div>
    </div>
</div>
