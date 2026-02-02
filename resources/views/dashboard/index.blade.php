<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | E-Invoice</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('invoice3.png') }}">

    <!-- CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* ================= GLOBAL MOTION ================= */
        :root {
            --ease-smooth: cubic-bezier(.4,0,.2,1);
            --ease-soft: cubic-bezier(.25,.8,.25,1);
        }

        /* ================= PAGE FADE ================= */
        .fade-in {
            animation: pageEnter .7s var(--ease-smooth) both;
        }
        @keyframes pageEnter {
            from {
                opacity: 0;
                transform: translateY(20px) scale(.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ================= CARD ELEVATION ================= */
        .elevation {
            position: relative;
            transition:
                transform .35s var(--ease-soft),
                box-shadow .35s var(--ease-soft),
                filter .35s var(--ease-soft);
        }

        .elevation:hover {
            transform: translateY(-6px) scale(1.01);
            box-shadow:
                0 20px 40px rgba(0,0,0,.12),
                0 8px 16px rgba(0,0,0,.08);
            filter: saturate(1.05);
        }

        /* ================= SPARKLINE GLOW ================= */
        .elevation canvas {
            transition:
                transform .35s var(--ease-soft),
                filter .35s var(--ease-soft);
        }

        .elevation:hover canvas {
            transform: scale(1.04);
            filter: drop-shadow(0 0 16px rgba(255,255,255,.9));
        }

        /* ================= SPARKLINE PULSE ================= */
        .sparkline {
            animation: sparkPulse 3.5s ease-in-out infinite;
            transform-origin: center;
        }

        @keyframes sparkPulse {
            0% {
                opacity: .65;
                filter: drop-shadow(0 0 0 rgba(255,255,255,0));
            }
            50% {
                opacity: 1;
                filter: drop-shadow(0 0 12px rgba(255,255,255,.65));
            }
            100% {
                opacity: .65;
                filter: drop-shadow(0 0 0 rgba(255,255,255,0));
            }
        }

        /* ================= PAUSE SAAT HOVER (CAKEP 😎) ================= */
        .elevation:hover .sparkline {
            animation-play-state: paused;
            opacity: 1;
            filter: drop-shadow(0 0 16px rgba(255,255,255,.9));
        }

        /* ================= TEXT SMOOTH ================= */
        h1, h2, h3 {
            letter-spacing: -.01em;
        }

        /* ================= CANVAS ENTER ================= */
        canvas {
            animation: canvasFade .9s var(--ease-smooth) both;
        }
        @keyframes canvasFade {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

</head>

<body class="bg-gray-100">
<div class="flex">
    @include('layouts.sidebar_admin')

    <div class="flex-1 min-h-screen flex flex-col">
        @include('layouts.header_admin')

        <main class="p-6 space-y-10 fade-in max-w-7xl mx-auto w-full">

            @php
                function rupiah($n){ return 'Rp '.number_format($n,0,',','.'); }
                function trend($p){
                    return $p >= 0
                        ? '<span class="text-emerald-300">▲ '.abs($p).'%</span>'
                        : '<span class="text-rose-300">▼ '.abs($p).'%</span>';
                }
            @endphp

            <!-- ================= HERO STATS ================= -->
            <section class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- TOTAL -->
                <div class="rounded-xl p-6 text-white bg-gradient-to-br from-indigo-500 to-indigo-700 elevation">
                    <p class="text-sm opacity-90">Total Invoice</p>
                    <h2 class="text-3xl font-bold mt-1 counter"
                        data-type="currency"
                        data-value="{{ $totalInvoice }}">
                        Rp 0
                    </h2>
                    <p class="text-xs mt-2">{!! trend($percentTotalInvoice) !!} dari bulan lalu</p>
                    <canvas id="sparkTotal" class="sparkline" height="50"></canvas>
                </div>

                <!-- PAID -->
                <div class="rounded-xl p-6 text-white bg-gradient-to-br from-emerald-500 to-emerald-700 elevation">
                    <p class="text-sm opacity-90">Total Paid</p>
                    <h2 class="text-3xl font-bold mt-1 counter"
                        data-type="currency"
                        data-value="{{ $totalPaid }}">
                        Rp 0
                    </h2>
                    <p class="text-xs mt-2">{!! trend($percentTotalPaid) !!} dari bulan lalu</p>
                    <canvas id="sparkPaid" class="sparkline" height="50"></canvas>
                </div>

                <!-- UNPAID -->
                <div class="rounded-xl p-6 text-white bg-gradient-to-br from-rose-500 to-rose-700 elevation">
                    <p class="text-sm opacity-90">Total Unpaid</p>
                    <h2 class="text-3xl font-bold mt-1 counter"
                        data-type="currency"
                        data-value="{{ $totalUnpaid }}">
                        Rp 0
                    </h2>
                    <p class="text-xs mt-2">{!! trend($percentTotalUnpaid) !!} dari bulan lalu</p>
                    <canvas id="sparkUnpaid" class="sparkline" height="50"></canvas>
                </div>

            </section>

            <!-- ================= SECONDARY STATS ================= -->
            <section class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="rounded-xl p-5 bg-indigo-50 elevation">
                    <p class="text-sm text-indigo-600">Jumlah Invoice</p>
                    <h3 class="text-2xl font-bold text-indigo-800 counter"
                        data-type="number"
                        data-value="{{ $countInvoice }}">
                        0
                    </h3>
                    <p class="text-xs text-indigo-500">{!! trend($percentCountInvoice) !!}</p>
                    <canvas id="sparkCount" class="sparkline" height="40"></canvas>
                </div>

                <div class="rounded-xl p-5 bg-emerald-50 elevation">
                    <p class="text-sm text-emerald-600">Invoice Paid</p>
                    <h3 class="text-2xl font-bold text-emerald-800 counter"
                        data-type="number"
                        data-value="{{ $countPaid }}">
                        0
                    </h3>
                    <p class="text-xs text-emerald-500">{!! trend($percentCountPaid) !!}</p>
                    <canvas id="sparkPaidCount" class="sparkline" height="40"></canvas>
                </div>

                <div class="rounded-xl p-5 bg-rose-50 elevation">
                    <p class="text-sm text-rose-600">Invoice Unpaid</p>
                    <h3 class="text-2xl font-bold text-rose-800 counter"
                        data-type="number"
                        data-value="{{ $countUnpaid }}">
                        0
                    </h3>
                    <p class="text-xs text-rose-500">{!! trend($percentCountUnpaid) !!}</p>
                    <canvas id="sparkUnpaidCount" class="sparkline" height="40"></canvas>
                </div>

            </section>

            <!-- ================= ANALYTICS ================= -->
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl p-5 elevation">
                    <canvas id="lineChart"></canvas>
                </div>
                <div class="bg-white rounded-xl p-5 elevation">
                    <canvas id="barChart"></canvas>
                </div>
                <div class="bg-white rounded-xl p-5 elevation">
                    <canvas id="pieChart"></canvas>
                </div>
            </section>

        </main>

        @include('layouts.footer_admin')
    </div>
</div>

<script>
    lucide.createIcons();

    /* ================= DATA ================= */
    const labels = {!! json_encode($monthly->pluck('month')) !!};

    const sparkData = {
        total: {!! json_encode($monthly->pluck('total')) !!},
        paid: {!! json_encode($monthly->pluck('paid')) !!},
        unpaid: {!! json_encode($monthly->pluck('unpaid')) !!},
        count: {!! json_encode($monthly->pluck('jumlah')) !!},
        paidCount: {!! json_encode($monthly->pluck('paid_count')) !!},
        unpaidCount: {!! json_encode($monthly->pluck('unpaid_count')) !!}
    };

    /* ================= SPARKLINE ================= */
    function spark(id, data, color = '#ffffff'){
        new Chart(document.getElementById(id), {
            type: 'line',
            data: {
                labels: data.map((_,i)=>i),
                datasets: [{
                    data,
                    borderColor: color,
                    borderWidth: 2,
                    tension: .45,
                    pointRadius: 0
                }]
            },
            options: {
                responsive:true,
                plugins:{ legend:{display:false}},
                scales:{ x:{display:false}, y:{display:false}}
            }
        });
    }

    spark('sparkTotal', sparkData.total);
    spark('sparkPaid', sparkData.paid);
    spark('sparkUnpaid', sparkData.unpaid);
    spark('sparkCount', sparkData.count, '#6366f1');
    spark('sparkPaidCount', sparkData.paidCount, '#10b981');
    spark('sparkUnpaidCount', sparkData.unpaidCount, '#ef4444');

    /* ================= MAIN CHARTS ================= */
    new Chart(lineChart,{
        type:'line',
        data:{ labels, datasets:[{ data:sparkData.total, borderColor:'#6366f1', tension:.4 }]}
    });

    new Chart(barChart,{
        type:'bar',
        data:{ labels, datasets:[{ data:sparkData.total, backgroundColor:'#6366f1' }]}
    });

    new Chart(pieChart,{
        type:'pie',
        data:{
            labels:['Paid','Unpaid'],
            datasets:[{
                data:[{{ $countPaid }}, {{ $countUnpaid }}],
                backgroundColor:['#10b981','#ef4444']
            }]
        }
    });

    /* ================= COUNT UP ================= */
    document.querySelectorAll('.counter').forEach(el => {
        const target = Number(el.dataset.value);
        const type   = el.dataset.type || 'number';
        const start  = performance.now();
        const duration = 900;

        function format(val){
            if(type === 'currency'){
                return 'Rp ' + val.toLocaleString('id-ID');
            }
            return val.toLocaleString('id-ID');
        }

        function animate(now){
            const progress = Math.min((now - start) / duration, 1);
            const value = Math.floor(progress * target);
            el.textContent = format(value);

            if(progress < 1) requestAnimationFrame(animate);
        }

        requestAnimationFrame(animate);
    });
</script>
</body>
</html>
