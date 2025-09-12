<template>
    <div class="min-h-screen text-slate-900 p-6 lg:p-10">
 


   <!-- Stats Grid -->
       <!-- Debt Collector Dashboard - Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <!-- Debt Assigned to Me -->
    <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition duration-300 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Debt Assigned to Me</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">RM58,200</p>
                <p class="text-xs text-blue-600 font-semibold mt-2">
                    12 active cases
                </p>
            </div>
            <div class="bg-blue-50 p-3 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 
                           14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Collected This Month -->
    <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition duration-300 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Collected This Month</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">RM12,750</p>
                <p class="text-xs text-green-600 font-semibold mt-2">
                    ▲ 9.4% vs last month
                </p>
            </div>
            <div class="bg-green-50 p-3 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 11V7a4 4 0 018 0v4m-4 
                           4h-1a2 2 0 00-2 2v1a2 2 
                           0 002 2h1a2 2 0 002-2v-1a2 2 
                           0 00-2-2z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Pending Follow-ups -->
    <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition duration-300 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Pending Follow-ups</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">23</p>
                <p class="text-xs text-red-600 font-semibold mt-2">
                    5 overdue this week
                </p>
            </div>
            <div class="bg-red-50 p-3 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-red-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 
                           11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Recovery Rate -->
    <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition duration-300 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Recovery Rate</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">74.5%</p>
                <p class="text-xs text-yellow-600 font-semibold mt-2">
                    ▲ 4.2% vs last month
                </p>
            </div>
            <div class="bg-yellow-50 p-3 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-yellow-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
        </div>
    </div>

</div>


 

    <!-- Debtors Table -->
    <div class="bg-white rounded-2xl shadow overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead class="bg-slate-100 text-slate-600 text-sm">
          <tr>
            <th class="px-6 py-3">Name</th>
            <th class="px-6 py-3">Phone</th>
            <th class="px-6 py-3">Outstanding</th>
            <th class="px-6 py-3">Due Date</th>
            <th class="px-6 py-3">Status</th>
            <th class="px-6 py-3">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          <tr>
            <td class="px-6 py-4">Asha Verma</td>
            <td class="px-6 py-4">+91 981234567</td>
            <td class="px-6 py-4 font-semibold">₹25,000</td>
            <td class="px-6 py-4">2025-09-15</td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">Open</span>
            </td>
            <td class="px-6 py-4">
              <button class="text-indigo-600 hover:underline">View</button>
            </td>
          </tr>
          <tr>
            <td class="px-6 py-4">Rohit Sharma</td>
            <td class="px-6 py-4">+91 982345678</td>
            <td class="px-6 py-4 font-semibold">₹75,000</td>
            <td class="px-6 py-4">2025-09-20</td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">Closed</span>
            </td>
            <td class="px-6 py-4">
              <button class="text-indigo-600 hover:underline">View</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>


</template>