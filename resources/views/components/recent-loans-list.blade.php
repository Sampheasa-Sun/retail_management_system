<div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
  <div class="p-6 border-b border-gray-200 dark:border-gray-700">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
      <span class="text-xl">📋</span> Recent Loan Applications
    </h3>
  </div>
  <div class="p-6">
    @forelse ($recentLoans as $loan)
    <x-recent-loan-row :loan="$loan" />
    @empty
    <div class="text-center py-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
        <i class="fas fa-file-invoice-dollar text-2xl text-gray-400 dark:text-gray-500"></i>
      </div>
      <p class="text-gray-500 dark:text-gray-400">No recent loans found</p>
      <a href="{{ route('loans.create') }}"
        class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
        <i class="fas fa-plus mr-2"></i> Create New Loan
      </a>
    </div>
    @endforelse
    @if($recentLoans->count() > 0)
    <div class="mt-6 text-center">
      <a href="{{ route('loans.index') }}"
        class="inline-flex items-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
        <span>View All Loans</span>
        <i class="fas fa-arrow-right ml-2"></i>
      </a>
    </div>
    @endif
  </div>
</div>