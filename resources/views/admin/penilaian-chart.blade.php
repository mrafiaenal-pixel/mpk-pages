<!DOCTYPE html>
<html>
<head>
    <title>Visualisasi Penilaian OSIS</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            min-height: 400px;
            margin: 20px 0;
        }
        .filter-panel {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .filter-panel label {
            margin-right: 15px;
            font-weight: 500;
        }
        .filter-panel select,
        .filter-panel input {
            margin-right: 10px;
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Visualisasi Hasil Penilaian OSIS</h2>
            <p class="text-muted">Tampilkan data penilaian untuk 10 sekbid dan banyak anggota OSIS</p>
        </div>
    </div>

    <div class="row">
        <!-- Filter Panel -->
        <div class="col-12 col-md-6">
            <div class="filter-panel">
                <label for="divisiFilter">Filter Divisi:</label>
                <select id="divisiFilter" class="form-select form-select-sm">
                    <option value="">Semua Divisi</option>
                    @foreach ($divisiList as $divisi)
                        <option value="{{ $divisi->id }}" {{ old('divisi') == $divisi->id ? 'selected' : '' }}>{{ $divisi->nama_sekbid }}</option>
                    @endforeach
                </select>

                <label for="anggotaFilter">Filter Anggota:</label>
                <select id="anggotaFilter" class="form-select form-select-sm">
                    <option value="">Semua Anggota</option>
                    @foreach ($anggotaList as $anggota)
                        <option value="{{ $anggota->id }}" {{ old('anggota') == $anggota->id ? 'selected' : '' }}>{{ $anggota->nama_anggota }}</option>
                    @endforeach
                </select>

                <label for="penilaiFilter">Filter MPK Penilai:</label>
                <select id="penilaiFilter" class="form-select form-select-sm">
                    <option value="">Semua Penilai</option>
                    @foreach ($penilaiList as $penilai)
                        <option value="{{ $penilai->id }}" {{ old('penilai') == $penilai->id ? 'selected' : '' }}>{{ $penilai->username }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Chart Type Filters -->
        <div class="col-12 col-md-3 ms-auto">
            <div class="filter-panel pt-3">
                <label>Tampilan:</label><br>
                <input type="radio" id="chartBar" name="chartType" value="bar" checked>
                <label for="chartBar">Bar Chart</label><br>
                <input type="radio" id="chartLine" name="chartType" value="line">
                <label for="chartLine">Line Chart</label><br>
                <input type="radio" id="chartPie" name="chartType" value="pie">
                <label for="chartPie">Pie Chart</label>
            </div>
        </div>
    </div>

    <!-- GDS vs Piket Comparison -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Perbandingannya GDS vs Piket RO per Divisi</h5>
                </div>
                <div class="card-body">
                    <canvas id="gdsPiketChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Poin Per Aspek -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Distribusi Poin per Aspek Penilaian</h5>
                </div>
                <div class="card-body">
                    <canvas aspekChart></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik per Divisi -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Statistik per Divisi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Divisi</th>
                                    <th>Jumlah Penilaian</th>
                                    <th>Rata-rata</th>
                                    <th>Tertinggi</th>
                                    <th>Terendah</th>
                                </tr>
                            </thead>
                            <tbody id="divisiStatsTable">
                                <!-- Akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart JS Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi chart kosong dulu, akan di-load via AJAX
        initCharts();
    });

    function initCharts() {
        // Chart GDS vs Piket RO
        const gdsPiketCtx = document.getElementById('gdsPiketChart').getContext('2d');
        window.gdsPiketChart = new Chart(gdsPiketCtx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'GDS',
                        data: [],
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Piket RO',
                        data: [],
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Rata-rata Poin GDS vs Piket RO per Divisi'
                    }
                }
            }
        });

        // Chart distribusi poin per aspek
        const aspekCtx = document.getElementById('aspekChart').getContext('2d');
        window.aspekChart = new Chart(aspekCtx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Total Poin',
                    data: [],
                    backgroundColor: 'rgba(53, 162, 235, 0.6)'
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                plugins: {
                    title: {
                        display: true,
                        text: 'Rata-rata Poin per Aspek Penilaian'
                    }
                }
            }
        });

        // Load data divisi stats
        loadDivisiStats();

        // Event listeners for filters
        document.getElementById('divisiFilter').addEventListener('change', loadCharts);
        document.getElementById('anggotaFilter').addEventListener('change', loadCharts);
        document.getElementById('penilaiFilter').addEventListener('change', loadCharts);
        
        document.querySelectorAll('input[name="chartType"]').forEach(radio => {
            radio.addEventListener('change', loadCharts);
        });
    }

    function loadCharts() {
        const divisi = document.getElementById('divisiFilter').value;
        const anggota = document.getElementById('anggotaFilter').value;
        const penilai = document.getElementById('penilaiFilter').value;
        const chartType = document.querySelector('input[name="chartType"]:checked').value;

        // Load GDS vs Piket chart
        loadGdsPiketChart(divisi, chartType);
        
        // Load aspek chart
        loadAspekChart(divisi, chartType);
        
        // Load divisi stats table
        loadDivisiStatsTable(divisi);
    }

    function loadGdsPiketChart(divisi, chartType) {
        fetch('/api/penilaian/gds-piket?divisi=' + divisi + '&type=' + chartType)
            .then(response => response.json())
            .then(data => {
                window.gdsPiketChart.data.labels = data.labels;
                window.gdsPiketChart.data.datasets[0].data = data.gdsData;
                window.gdsPiketChart.data.datasets[1].data = data.piketData;
                window.gdsPiketChart.options.plugins.title.text = 'Perbandingannya GDS vs Piket RO per Divisi';
                window.gdsPiketChart.update();
            })
            .catch(error => console.error('Error loading GDS/Piket chart:', error));
    }

    function loadAspekChart(divisi, chartType) {
        fetch('/api/penilaian/aspek?divisi=' + divisi + '&type=' + chartType)
            .then(response => response.json())
            .then(data => {
                window.aspekChart.data.labels = data.aspekLabels;
                window.aspekChart.data.datasets[0].data = data.totalPoinData;
                window.aspekChart.update();
            })
            .catch(error => console.error('Error loading aspek chart:', error));
    }

    function loadDivisiStatsTable(divisi) {
        fetch('/api/penilaian/divisi-stats?divisi=' + divisi)
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('divisiStatsTable');
                tableBody.innerHTML = '';
                
                data.forEach(item => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${item.nama_sekbid}</td>
                        <td>${item.jumlah}</td>
                        <td>${item.rata_nilai}</td>
                        <td>${item.tertinggi}</td>
                        <td>${item.terendah}</td>
                    `;
                    tableBody.appendChild(tr);
                });
            })
            .catch(error => console.error('Error loading divisi stats:', error));
    }
</script>