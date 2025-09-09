<div class="mb-6 last:mb-0">
  <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <!-- Loan Basic Info -->
      <div class="flex items-start space-x-4">
        <div class="flex-shrink-0">
          @if($loan->customer && $loan->customer->verification_photo)
          <img src="{{ asset('storage/' . $loan->customer->verification_photo) }}"
            alt="{{ $loan->customer->name }}"
            class="h-12 w-12 rounded-full object-cover">
          @else
          <div class="h-12 w-12 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
            <i class="fas fa-user text-gray-400 dark:text-gray-500"></i>
          </div>
          @endif
        </div>
        <div>
          <div class="flex items-center gap-2">
            <h4 class="text-base font-medium text-gray-900 dark:text-white">
              {{ $loan->customer->name ?? 'N/A' }}
            </h4>
            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full 
                                {{ $loan->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($loan->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-red-100 text-red-800') }}">
              {{ ucfirst($loan->status) }}
            </span>
          </div>
          <p class="text-sm text-gray-500 dark:text-gray-400">
            Loan #{{ $loan->loan_id }} • Applied {{ $loan->created_at->diffForHumans() }}
          </p>
        </div>
      </div>
      <!-- Loan Details -->
      <div class="flex flex-wrap items-center gap-4">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
            <i class="fas fa-dollar-sign text-green-600 dark:text-green-400"></i>
          </div>
          <div>
            <p class="text-xs text-gray-500 dark:text-gray-400">Amount</p>
            <p class="text-sm font-medium text-gray-900 dark:text-white">
              ${{ number_format($loan->loan_amount, 2) }}
            </p>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
            <i class="fas fa-percentage text-blue-600 dark:text-blue-400"></i>
          </div>
          <div>
            <p class="text-xs text-gray-500 dark:text-gray-400">Interest</p>
            <p class="text-sm font-medium text-gray-900 dark:text-white">
              {{ $loan->interest_rate }}%
            </p>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
            <i class="fas fa-calendar text-purple-600 dark:text-purple-400"></i>
          </div>
          <div>
            <p class="text-xs text-gray-500 dark:text-gray-400">Start Date</p>
            <p class="text-sm font-medium text-gray-900 dark:text-white">
              {{ \Carbon\Carbon::parse($loan->starting_date)->format('M d, Y') }}
            </p>
          </div>
        </div>
        <!-- Actions -->
        <div class="flex gap-2 ml-auto">
          <a href="{{ route('loans.show', $loan->id) }}"
            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
            <i class="fas fa-eye mr-1.5"></i> View
          </a>
          <a href="{{ route('loans.edit', $loan->id) }}"
            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-yellow-500 rounded-md hover:bg-yellow-600 transition-colors">
            <i class="fas fa-edit mr-1.5"></i> Edit
          </a>
        </div>
      </div>
    </div>
  </div>
</div>