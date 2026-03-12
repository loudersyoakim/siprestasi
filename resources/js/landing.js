document.addEventListener("DOMContentLoaded", function () {
    const nav = document.getElementById("mainNav");
    const menuBtn = document.getElementById("menuBtn");
    const mobileMenu = document.getElementById("mobileMenu");
    const spans = menuBtn?.querySelectorAll("span");
    const mobileLinks = document.querySelectorAll(".mobile-link");

    // ==========================================
    // 1. LOGIKA HAMBURGER & NAVBAR (Tetap Sama)
    // ==========================================
    function toggleMenu(forceClose = false) {
        if (!mobileMenu || !spans) return;
        const isOpen = !mobileMenu.classList.contains("translate-x-full");
        if (isOpen || forceClose) {
            mobileMenu.classList.add("translate-x-full");
            spans.forEach((s) => (s.style.backgroundColor = "white"));
            spans[0].style.transform = "none";
            spans[1].style.opacity = "1";
            spans[2].style.transform = "none";
            document.body.classList.remove("overflow-hidden");
        } else {
            mobileMenu.classList.remove("translate-x-full");
            spans.forEach((s) => (s.style.backgroundColor = "#006633"));
            spans[0].style.transform = "translateY(8px) rotate(45deg)";
            spans[1].style.opacity = "0";
            spans[2].style.transform = "translateY(-8px) rotate(-45deg)";
            document.body.classList.add("overflow-hidden");
        }
    }

    menuBtn?.addEventListener("click", (e) => {
        e.stopPropagation();
        toggleMenu();
    });
    document.addEventListener("click", (e) => {
        if (!mobileMenu) return;
        if (
            !mobileMenu.classList.contains("translate-x-full") &&
            !mobileMenu.contains(e.target) &&
            !menuBtn.contains(e.target)
        ) {
            toggleMenu(true);
        }
    });
    mobileLinks.forEach((link) => {
        link.addEventListener("click", () => toggleMenu(true));
    });

    window.addEventListener("scroll", function () {
        if (window.innerWidth <= 770 || !nav) return;
        const logo = document.getElementById("navLogo");
        const title = document.getElementById("navTitle");
        const tagline = document.getElementById("navTagline");
        if (window.scrollY > 50) {
            nav.classList.replace("py-4", "py-2");
            nav.classList.add(
                "bg-[#006633]/95",
                "backdrop-blur-xl",
                "shadow-xl",
            );
            if (logo) logo.classList.replace("h-10", "h-8");
            if (title) title.classList.replace("text-base", "text-sm");
            if (tagline) tagline.classList.add("opacity-0", "h-0");
        } else {
            nav.classList.replace("py-2", "py-4");
            nav.classList.remove(
                "bg-[#006633]/95",
                "backdrop-blur-xl",
                "shadow-xl",
            );
            if (logo) logo.classList.replace("h-8", "h-10");
            if (title) title.classList.replace("text-sm", "text-base");
            if (tagline) tagline.classList.remove("opacity-0", "h-0");
        }
    });

    // ==========================================
    // 2. LOGIKA GRAFIK DINAMIS (API SINKRON)
    // ==========================================
    // Basis warna hijau monochrome
    const baseGreens = [
        "#006633", // Hijau Unimed (Start)
        "#1b7a2f", // Hijau Forest
        "#378f2a", // Hijau Grass
        "#52a326", // Hijau Lime Dark
        "#8ebf1f", // Hijau Lime Bright
        "#c9db19", // Lemon
        "#e6e214", // Yellow
        "#fbbf24", // Kuning Emas Unimed (End)
    ];

    // Mengubah warna flat menjadi Radial Gradient (Gradasi ke tengah)
    const themeColors = baseGreens.map((color) => {
        return {
            radialGradient: { cx: 0.5, cy: 0.5, r: 0.7 },
            stops: [
                [0, Highcharts.color(color).brighten(0.3).get("rgb")], // Warna tengah (lebih terang/glow)
                [1, color], // Warna pinggir (warna asli)
            ],
        };
    });
    // const themeColors = [
    //     // "#006633", // 1. Hijau Utama (Identity)
    //     // "#fbbf24", // 2. Kuning Emas (Prestasi)
    //     // "#059669", // 3. Hijau Emerald (Modern)
    //     // "#f59e0b", // 4. Kuning Amber (Deep Achievement)
    //     // "#065f46", // 5. Hijau Deep Forest (Wibawa)
    //     // "#facc15", // 6. Kuning Cerah (Vibrant)
    //     // "#064e3b", // 7. Hijau Dark Emerald (Solid)
    //     // "#fb923c", // 8. Kuning Sunset (Kontras Akhir)
    //     "#004d26", // Hijau Gelap (Base)
    //     "#006633", // Hijau Unimed (Identity)
    //     "#008040", // Hijau Medium
    //     "#00994d", // Hijau Emerald
    //     "#00b359", // Hijau Leaf
    //     "#00cc66", // Hijau Vibrant
    //     "#1aff8c", // Hijau Mint (Highlight)
    //     "#b3ffda", // Hijau Sangat Muda (Paling Luar)
    // ];

    const chart = Highcharts.chart("unimed3DPie", {
        accessibility: { enabled: false },
        chart: {
            type: "pie",
            backgroundColor: "transparent",
            spacingTop: 40,
            spacingBottom: 40,
            spacingLeft: 20,
            spacingRight: 20,
            options3d: {
                enabled: true,
                alpha: 50,
                beta: 0,
                depth: 50,
            },
        },
        title: { text: null },
        tooltip: {
            headerFormat:
                '<span style="font-size: 10px; font-weight: 900; color: #666; text-transform: uppercase;">{point.key}</span><br/>',
            pointFormat:
                '<span style="color:{point.color}">●</span> <b>{point.percentage:.1f}%</b> dari total',
            backgroundColor: "rgba(255, 255, 255, 0.95)",
            borderRadius: 15,
            borderWidth: 0,
            shadow: true,
            style: { color: "#333", fontSize: "11px" },
        },

        // 1. SETTING UTAMA LEGEND (DI LUAR RESPONSIVE)
        legend: {
            enabled: false, // Default di Desktop: Legend Mati (pakai DataLabels melayang)
            itemStyle: {
                fontSize: "10px",
                color: "#333",
                fontWeight: "bold",
            },
            align: "center",
            verticalAlign: "bottom",
            layout: "horizontal",
            itemMarginBottom: 5,
        },

        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: "pointer",
                depth: 40,
                size: "100%",
                center: ["50%", "50%"],
                colors: themeColors,
                borderWidth: 1.5,
                borderColor: "rgba(255, 255, 255, 0.3)",

                // 2. AKTIFKAN DUKUNGAN LEGEND DI SINI
                showInLegend: true,

                dataLabels: {
                    enabled: true,
                    useHTML: true,
                    distance: 40,
                    crop: false,
                    overflow: "allow",
                    backgroundColor: "transparent",
                    borderWidth: 0,
                    padding: 0,
                    shadow: false,
                    formatter: function () {
                        const point = this.point;
                        const isLeft = point.half === 1;
                        const gradientDir = isLeft
                            ? "to bottom left"
                            : "to bottom right";
                        const alignment = isLeft ? "flex-end" : "flex-start";

                        return `
                        <div style="display: flex; flex-direction: column; align-items: ${alignment};">
                            <div style="
                                display: flex;
                                flex-direction: column;
                                align-items: ${alignment};
                                padding: 8px 16px;
                                background: linear-gradient(${gradientDir}, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.005) 60%, transparent 100%);
                                backdrop-filter: blur(12px);
                                -webkit-backdrop-filter: blur(12px);
                                border-radius: 20px;
                                border: 1px solid rgba(255, 255, 255, 0);
                                border-top: 1px solid rgba(255, 255, 255, 0);
                                width: fit-content;
                            ">
                                <span style="color: white; font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.15em; line-height: 1.2; margin-bottom: 2px; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                    ${point.name}
                                </span>
                                <span style="color: white; font-size: 15px; font-weight: 900; line-height: 1; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                    ${point.percentage.toFixed(1)}%
                                </span>
                            </div>
                        </div>
                        `;
                    },
                    connectorColor: null,
                    connectorWidth: 0.5,
                    connectorPadding: 0,
                    softConnector: true,
                },
            },
        },

        // 3. RESPONSIVE RULES (KUNCI UNTUK MOBILE)
        responsive: {
            rules: [
                {
                    condition: {
                        callback: function () {
                            return window.innerWidth <= 768;
                        },
                    },
                    chartOptions: {
                        chart: {
                            // Kosongkan spacing kiri-kanan agar grafik bisa selebar layar HP
                            spacingLeft: 0,
                            spacingRight: 0,
                            spacingBottom: 15, // Ruang untuk legend di bawah
                            spacingTop: 10,
                        },
                        legend: {
                            enabled: true,
                            layout: "horizontal",
                            align: "center",
                            verticalAlign: "bottom",
                            itemMarginTop: 5, // Jarak atas antar item legend
                            itemMarginBottom: 5, // Jarak bawah antar item legend
                            itemStyle: {
                                fontSize: "10px",
                                fontWeight: "bold",
                                color: "#333",
                            },
                        },
                        plotOptions: {
                            pie: {
                                dataLabels: {
                                    enabled: false, // Matikan label melayang
                                },
                                size: "90%",
                                center: ["50%", "40%"],
                            },
                        },
                    },
                },
            ],
        },
        series: [
            {
                name: "Kontribusi",
                colorByPoint: true,
                data: [],
            },
        ],
        credits: { enabled: false },
    });

    async function fetchStatistik(type) {
        try {
            const response = await fetch(`/api/statistik-landing?type=${type}`);
            const data = await response.json();

            // 1. FILTER: Hanya ambil data yang nilainya lebih dari 0
            // Kita petakan dulu labels dan values yang sinkron
            const filteredDataRaw = data.labels
                .map((label, index) => {
                    return {
                        label: label,
                        value: data.values[index],
                    };
                })
                .filter((item) => item.value > 0); // <-- Kuncinya di sini, Bang!

            // 2. Jika setelah difilter ternyata kosong semua (misal data 0 semua),
            // Abang bisa kasih fallback atau biarkan chart kosong
            if (filteredDataRaw.length === 0) {
                chart.series[0].setData([], true);
                return;
            }

            // 3. Cari nilai tertinggi dari data yang sudah difilter untuk efek 'sliced'
            const maxValue = Math.max(
                ...filteredDataRaw.map((item) => item.value),
            );

            // 4. Format data untuk Highcharts
            const formattedData = filteredDataRaw.map((item) => {
                const isWinner = item.value === maxValue;
                return {
                    name: item.label,
                    y: item.value,
                    sliced: isWinner,
                    selected: isWinner,
                };
            });

            // 5. Update Chart
            chart.series[0].setData(formattedData, true);
        } catch (error) {
            console.error("Gagal sinkronisasi data:", error);
        }
    }

    // Event Listener untuk Dropdown Filter
    const filterDropdown = document.getElementById("filterChart");
    filterDropdown.addEventListener("change", function (e) {
        fetchStatistik(e.target.value);
    });

    // Load Data Pertama Kali (Default: Tingkat)
    fetchStatistik("tingkat");

    // ==========================================
    // 3. LOGIKA AUTO-RELOAD (SETIAP 5 DETIK)
    // ==========================================
    setInterval(() => {
        // Ambil nilai filter yang sedang aktif (Tingkat/Fakultas/dll)
        const currentType = filterDropdown.value;

        // Panggil fungsi fetch tanpa mengganggu tampilan (silent reload)
        fetchStatistik(currentType);

        // Debugging singkat di console biar Abang tahu dia lagi reload
        console.log(
            `Auto-refresh data: ${currentType} (${new Date().toLocaleTimeString()})`,
        );
    }, 5000); // 5000ms = 5 detik
});
