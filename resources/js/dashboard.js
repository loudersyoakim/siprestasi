// 1. Deklarasi variabel di paling atas agar bisa diakses oleh listener Livewire
let chart3d, areaChart, barChart;

// 2. Palet warna otomatis
const colorPalette = [
    "#006633",
    "#fbbf24",
    "#2563eb",
    "#ef4444",
    "#8b5cf6",
    "#ec4899",
    "#06b6d4",
];

// 3. Inisialisasi Grafik saat halaman siap
document.addEventListener("DOMContentLoaded", function () {
    // Highcharts 3D Pie
    chart3d = Highcharts.chart("chart3d", {
        chart: {
            type: "pie",
            options3d: { enabled: true, alpha: 45, beta: 0 },
            backgroundColor: "transparent",
            marginBottom: 100,
        },
        title: { text: null },
        plotOptions: {
            pie: {
                depth: 45,
                dataLabels: { enabled: false },
                showInLegend: true,
            },
        },
        legend: {
            enabled: true,
            verticalAlign: "bottom",
            itemStyle: { fontSize: "10px", fontWeight: "bold" },
        },
        series: [{ name: "Total", data: [] }],
        credits: { enabled: false },
    });

    // Highcharts Area
    areaChart = Highcharts.chart("area-chart-rekap", {
        chart: { type: "area", backgroundColor: "transparent" },
        title: { text: null },
        xAxis: { categories: [] },
        yAxis: { title: { text: null } },
        plotOptions: {
            area: {
                fillOpacity: 0.3,
                marker: { enabled: false },
            },
        },
        series: [{ name: "Input Prestasi", data: [], color: "#006633" }],
        credits: { enabled: false },
    });

    // Highcharts Bar
    barChart = Highcharts.chart("bar-chart-kategori", {
        chart: { type: "column", backgroundColor: "transparent" },
        title: { text: null },
        xAxis: { categories: [] },
        yAxis: { title: { text: null } },
        series: [{ name: "Jumlah Data", data: [], color: "#fbbf24" }],
        credits: { enabled: false },
    });
});

// 4. Listener untuk Update Data dari Livewire
window.addEventListener("updateAllCharts", (event) => {
    let incoming = event.detail.data;
    if (!incoming && event.detail[0]) {
        incoming = event.detail[0];
    }

    if (incoming) {
        // Update Pie
        if (chart3d && incoming.pie) {
            const formattedPie = incoming.pie.labels.map((label, index) => ({
                name: label,
                y: incoming.pie.data[index],
                color: colorPalette[index % colorPalette.length],
            }));
            chart3d.series[0].setData(formattedPie);
        }

        // Update Area
        if (areaChart && incoming.area) {
            areaChart.xAxis[0].setCategories(incoming.area.categories);
            areaChart.series[0].setData(incoming.area.data);
        }

        // Update Bar
        if (barChart && incoming.bar) {
            barChart.xAxis[0].setCategories(incoming.bar.categories);
            barChart.series[0].setData(incoming.bar.data);
        }
    }
});
