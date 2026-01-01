<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center">
        <div class="flex-shrink-0 rounded-md p-3" style="background-color: {{ $bgColor ?? '#FEE2E2' }}">
            <svg class="w-6 h-6" style="color: {{ $iconColor ?? '#DC2626' }}" fill="{{ $fill ?? 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                {!! $icon !!}
            </svg>
        </div>
        <div class="ml-5 flex-1">
            <p class="text-sm font-medium text-gray-500">{{ $title }}</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $value }}</p>
            @isset($subtitle)
                <p class="text-xs text-gray-500 mt-1">{{ $subtitle }}</p>
            @endisset
            @isset($trend)
                <div class="mt-2 flex items-center text-sm">
                    @if($trend > 0)
                        <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-green-600">{{ abs($trend) }}%</span>
                    @elseif($trend < 0)
                        <svg class="w-4 h-4 text-red-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-red-600">{{ abs($trend) }}%</span>
                    @else
                        <span class="text-gray-600">No change</span>
                    @endif
                </div>
            @endisset
        </div>
    </div>
</div>
