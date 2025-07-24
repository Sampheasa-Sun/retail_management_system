<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Grid for the stat boxes --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                <!-- Total Products Box -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium text-gray-500 dark:text-gray-400">Total Products</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                            {{ $totalProducts }}
                        </p>
                    </div>
                </div>

                <!-- Total Employees Box -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium text-gray-500 dark:text-gray-400">Total Employees</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                            {{ $totalEmployees }}
                        </p>
                    </div>
                </div>

                <!-- Today's Revenue Box -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium text-gray-500 dark:text-gray-400">Today's Revenue</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                            ${{ number_format($todaysRevenue, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- New section for the chart --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-500 dark:text-gray-400 mb-4">Employee Performance (Last 30 Days)</h3>
                    <canvas id="employeeSalesChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    {{-- Script section for Chart.js --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('employeeSalesChart').getContext('2d');
            const employeeSalesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Total Sales ($)',
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                // This callback function now formats the numbers
                                callback: function(value, index, values) {
                                    // This will add comma separators for thousands (e.g., 1,000)
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
