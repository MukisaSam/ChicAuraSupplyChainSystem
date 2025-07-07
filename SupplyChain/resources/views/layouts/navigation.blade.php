<nav class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/CA-WORD2.png') }}" alt="ChicAura Logo" class="h-9 w-auto block dark:hidden" />
                    <img src="{{ asset('images/CA-WORD2.png') }}" alt="ChicAura Logo" class="h-9 w-auto hidden dark:block" />
                </a>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Back to Dashboard (only for supply request section) -->
                @if (request()->is('supplier/supply-request/*'))
                    <a href="{{ route('supplier.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        &larr; Back to dashboard
                    </a>
                @endif
                <!-- Dark Mode Toggle -->
                <button data-theme-toggle class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white rounded-full transition-colors" title="Switch Theme">
                    <i class="fas fa-moon text-lg"></i>
                </button>
            </div>
        </div>
    </div>
</nav>
