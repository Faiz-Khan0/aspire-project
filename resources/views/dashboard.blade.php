<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center space-x-2">
                            <label for="week" class="text-gray-700 dark:text-gray-300">
                                Select week:
                            </label>

                            <input type="date" name="week" id="week" value="{{ $selectedWeek }}" class="block w-full sm:w-auto rounded-md shadow-sm border-gray-300 dark:border-gray-700
               dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500
               dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">

                            <x-primary-button class="ml-2">
                                Filter
                            </x-primary-button>
                        </form>
                    </div>

                    <canvas id="checkinChart" height="100"></canvas>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                        const rawData = @json($checkinData);

                        const labels = Object.keys(rawData); // service names
                        const subtypes = new Set();

                        // Collect all unique subtypes
                        Object.values(rawData).forEach(service => {
                            Object.keys(service).forEach(subtype => subtypes.add(subtype));
                        });

                        const subtypeList = Array.from(subtypes);

                        const datasets = subtypeList.map(subtype => {
                            return {
                                label: subtype,
                                data: labels.map(service => rawData[service]?.[subtype] || 0),
                                backgroundColor: getRandomColor()
                            };
                        });

                        new Chart(document.getElementById('checkinChart'), {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: datasets
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { position: 'top' },
                                    title: {
                                        display: true,
                                        text: 'Check-ins by Service and Subtype'
                                    }
                                },
                                scales: {
                                    x: { stacked: false },
                                    y: { beginAtZero: true }
                                }
                            }
                        });

                        function getRandomColor() {
                            return 'hsl(' + Math.floor(Math.random() * 360) + ', 70%, 60%)';
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>