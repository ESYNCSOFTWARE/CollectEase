<script setup>
import { Head } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

defineProps({
    auth: {
        type: Object,
        default: null,
    },
});

// Chart Refs
const lineChartRef = ref(null);
const pieChartRef = ref(null);
const barChartRef = ref(null);

onMounted(() => {
    // Line Chart - Collection Performance
    new Chart(lineChartRef.value, {
        type: "line",
        data: {
            labels: [
                "Jan", "Feb", "Mar", "Apr", "May", "Jun", 
                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
            ],
            datasets: [
                {
                    label: "Amount Collected ($)",
                    data: [18500, 22200, 19400, 27800, 23600, 32500, 29800, 36700, 39500, 42350, 38200, 41100],
                    borderColor: "rgb(59, 130, 246)",
                    backgroundColor: "rgba(59, 130, 246, 0.1)",
                    tension: 0.3,
                    fill: true,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: "top" } },
            scales: {
                y: { beginAtZero: true, grid: { drawBorder: false } },
                x: { grid: { display: false } },
            },
        },
    });

    // Pie Chart - Debt Distribution
    new Chart(pieChartRef.value, {
        type: "pie",
        data: {
            labels: ["Credit Cards", "Personal Loans", "Auto Loans", "Mortgages", "Medical Bills"],
            datasets: [
                {
                    data: [35, 25, 20, 15, 5],
                    backgroundColor: [
                        "rgb(59, 130, 246)",
                        "rgb(139, 92, 246)",
                        "rgb(16, 185, 129)",
                        "rgb(245, 158, 11)",
                        "rgb(239, 68, 68)",
                    ],
                    hoverOffset: 4,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: "right" } },
        },
    });

    // Bar Chart - Debt Recovery by Age
    new Chart(barChartRef.value, {
        type: "bar",
        data: {
            labels: ["0-30 days", "31-60 days", "61-90 days", "91-120 days", "120+ days"],
            datasets: [
                {
                    label: "Recovery Rate (%)",
                    data: [92, 75, 58, 32, 18],
                    backgroundColor: "rgba(59, 130, 246, 0.7)",
                    borderColor: "rgb(59, 130, 246)",
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { callback: (value) => value + "%" },
                },
                x: { grid: { display: false } },
            },
        },
    });
});
</script>

<template>
    <Head title="Dashboard" />

    <main class="w-full px-4 sm:px-6 lg:px-8 py-2">
        <!-- ====== STATS GRID ====== -->
       <!-- Stats Grid -->
       <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Stat Card 1 -->
            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Outstanding Debt</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">$245,680</p>
                        <p class="text-xs text-red-600 font-medium mt-2">
                            <span>+8.3% from last month</span>
                        </p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Collected This Month</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">$42,350</p>
                        <p class="text-xs text-green-600 font-medium mt-2">
                            <span>+15.2% from last month</span>
                        </p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 极速赛车开奖直播 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Active Accounts</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">1,489</p>
                        <p class="text-xs text-red-600 font-medium mt-2">
                            <span>-2.1% from last month</span>
                        </p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 极速赛车开奖直播H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-极速赛车开奖直播 0 3 3 0 016 0zm6 3a2 2 0 11-4 极速赛车开奖直播 2 2 0 014 0zM7 10a2 2 极速赛车开奖直播 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Stat Card 4 -->
            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Recovery Rate</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">68.9%</p>
                        <p class="text-xs text-green-600 font-medium mt-2">
                            <span>+5.7% from last month</span>
                        </p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2极速赛车开奖直播h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- ====== CHARTS ====== -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Line Chart -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Collection Performance</h2>
                <div class="relative h-72">
                    <canvas ref="lineChartRef"></canvas>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Debt Distribution</h2>
                <div class="relative h-72">
                    <canvas ref="pieChartRef"></canvas>
                </div>
            </div>
        </div>

        <!-- Bar Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Debt Recovery by Age</h2>
            <div class="relative h-72">
                <canvas ref="barChartRef"></canvas>
            </div>
        </div>

        <!-- ====== RECENT COLLECTIONS TABLE ====== -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Recent Collections</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Debtor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount Due</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days Overdue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">#ACC-7829</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">John D. Smith</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$2,499</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">42</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Delinquent
                                </span>
                            </td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</template>
