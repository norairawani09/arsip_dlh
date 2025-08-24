{{-- resources/views/laporan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan')
@section('subtitle', 'Analisis dan laporan data surat masuk, keluar, dan disposisi')

@section('content')
<div class="p-6 space-y-6" x-data="laporanData()">
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
            </svg>
            <h3 class="text-lg font-medium">Filter Laporan</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Periode</label>
                <select x-model="selectedPeriod" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                    <option value="hari-ini">Hari Ini</option>
                    <option value="minggu-ini">Minggu Ini</option>
                    <option value="bulan-ini">Bulan Ini</option>
                    <option value="tahun-ini">Tahun Ini</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>

            <template x-if="selectedPeriod === 'custom'">
                <div class="grid grid-cols-2 gap-4 md:col-span-2">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <input type="date" x-model="startDate" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                        <input type="date" x-model="endDate" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                    </div>
                </div>
            </template>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Jenis Surat</label>
                <select x-model="selectedJenis" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                    <option value="semua">Semua</option>
                    <option value="masuk">Surat Masuk</option>
                    <option value="keluar">Surat Keluar</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select x-model="selectedStatus" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                    <option value="semua">Semua</option>
                    <option value="pending">Pending</option>
                    <option value="proses">Proses</option>
                    <option value="selesai">Selesai</option>
                    <option value="terkirim">Terkirim</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Surat Masuk</p>
                    <p class="text-2xl font-bold">{{ $stats['total_surat_masuk'] ?? 156 }}</p>
                    <p class="text-xs text-gray-500 flex items-center mt-1">
                        <svg class="w-3 h-3 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        +12% dari bulan lalu
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 102 0V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 1a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Surat Keluar</p>
                    <p class="text-2xl font-bold">{{ $stats['total_surat_keluar'] ?? 89 }}</p>
                    <p class="text-xs text-gray-500 flex items-center mt-1">
                        <svg class="w-3 h-3 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        +8% dari bulan lalu
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Disposisi</p>
                    <p class="text-2xl font-bold">{{ $stats['total_disposisi'] ?? 203 }}</p>
                    <p class="text-xs text-gray-500 flex items-center mt-1">
                        <svg class="w-3 h-3 mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        -3% dari bulan lalu
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Rata-rata Proses</p>
                    <p class="text-2xl font-bold">3.2</p>
                    <p class="text-xs text-gray-500">hari</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium">Trend Bulanan</h3>
                <p class="text-gray-600 text-sm">Grafik tren surat dan disposisi per bulan</p>
            </div>
            <div class="p-6">
                <canvas id="trendChart" width="400" height="200"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium">Distribusi Status</h3>
                <p class="text-gray-600 text-sm">Persentase status surat dan disposisi</p>
            </div>
            <div class="p-6">
                <canvas id="statusChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Reports Tabs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200" x-data="{ activeTab: 'surat-masuk' }">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button @click="activeTab = 'surat-masuk'" 
                        :class="activeTab === 'surat-masuk' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" 
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                    Surat Masuk
                </button>
                <button @click="activeTab = 'surat-keluar'" 
                        :class="activeTab === 'surat-keluar' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" 
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                    Surat Keluar
                </button>
                <button @click="activeTab = 'disposisi'" 
                        :class="activeTab === 'disposisi' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" 
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                    Disposisi
                </button>
            </nav>
        </div>

        <!-- Surat Masuk Tab -->
        <div x-show="activeTab === 'surat-masuk'" class="p-6">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="text-lg font-medium">Laporan Surat Masuk</h3>
                    <p class="text-gray-600 text-sm">Daftar lengkap surat masuk dengan filter yang diterapkan</p>
                </div>
                <div class="flex gap-2">
                    <button @click="exportData('excel', 'surat-masuk')" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Excel
                    </button>
                    <button @click="exportData('pdf', 'surat-masuk')" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        PDF
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Surat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengirim</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perihal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioritas</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($surat_masuk ?? [] as $surat)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $surat->nomor_surat }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $surat->pengirim }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $surat->perihal }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $surat->status === 'selesai' ? 'bg-green-100 text-green-800' : ($surat->status === 'proses' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($surat->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $surat->prioritas === 'tinggi' ? 'bg-red-100 text-red-800' : ($surat->prioritas === 'sedang' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst($surat->prioritas) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Similar tabs for Surat Keluar and Disposisi -->
        <div x-show="activeTab === 'surat-keluar'" class="p-6">
            <div class="text-center py-8">
                <p class="text-gray-500">Data surat keluar akan ditampilkan di sini</p>
            </div>
        </div>

        <div x-show="activeTab === 'disposisi'" class="p-6">
            <div class="text-center py-8">
                <p class="text-gray-500">Data disposisi akan ditampilkan di sini</p>
            </div>
        </div>
    </div>
</div>

<script>
function laporanData() {
    return {
        selectedPeriod: 'bulan-ini',
        startDate: '',
        endDate: '',
        selectedJenis: 'semua',
        selectedStatus: 'semua',
        
        exportData(format, type) {
            console.log(`Exporting ${type} as ${format}`);
            alert(`Mengunduh laporan ${type} dalam format ${format.toUpperCase()}`);
        }
    }
}

// Initialize charts after page load
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js initialization would go here
    // For demo purposes, we'll add basic canvas elements
});
</script>
@endsection