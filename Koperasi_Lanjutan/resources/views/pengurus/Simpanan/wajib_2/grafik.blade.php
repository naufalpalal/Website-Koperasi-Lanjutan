@extends('pengurus.index')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-lg">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">
            📊 Grafik Simpanan Wajib Tahun {{ $tahun }}
        </h2>

        <form method="GET">
            <input type="number" name="tahun" value="{{ $tahun }}"
                class="border rounded px-3 py-1 w-28">
            <button class="bg-blue-600 text-white px-3 py-1 rounded">
                Filter
            </button>
        </form>
    </div>

    <canvas id="simpananChart" height="120"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('simpananChart').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($labels) !!},
        datasets: [{
            label: 'Total Simpanan (Rp)',
            data: {!! json_encode($values) !!},
            backgroundColor: '#3b82f6',
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Rp ' + context.raw.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: value => 'Rp ' + value.toLocaleString('id-ID')
                }
            }
        }
    }
});
</script>
@endsection
