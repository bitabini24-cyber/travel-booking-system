<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../backend/middleware/admin.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';

// Revenue by month (last 6 months)
$revenueStmt = $pdo->query("
    SELECT DATE_FORMAT(created_at, '%b %Y') as month,
           SUM(total_price) as revenue,
           COUNT(*) as bookings
    FROM bookings
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY created_at ASC
");
$revenueData = $revenueStmt->fetchAll();

// Top hotels by bookings
$topHotels = $pdo->query("
    SELECT h.name, h.city, COUNT(b.id) as total_bookings, SUM(b.total_price) as revenue
    FROM hotels h LEFT JOIN bookings b ON h.id = b.hotel_id
    GROUP BY h.id ORDER BY total_bookings DESC LIMIT 5
")->fetchAll();

// Booking status breakdown
$statusData = $pdo->query("
    SELECT status, COUNT(*) as count FROM bookings GROUP BY status
")->fetchAll();

// New users per month
$usersData = $pdo->query("
    SELECT DATE_FORMAT(created_at, '%b') as month, COUNT(*) as count
    FROM users
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY created_at ASC
")->fetchAll();

$pageTitle = 'Analytics - Admin';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="dashboard-layout">
    <?php require_once __DIR__ . '/../../includes/admin-sidebar.php'; ?>
    <main class="dashboard-main">
        <h1 style="font-size:1.8rem; font-weight:800; margin-bottom:8px;">Analytics</h1>
        <p style="color:var(--text-light); margin-bottom:32px;">Platform performance overview</p>

        <!-- REVENUE CHART -->
        <div style="display:grid; grid-template-columns:2fr 1fr; gap:24px; margin-bottom:32px;">
            <div class="hotel-card" style="padding:28px;">
                <h3 style="font-weight:700; margin-bottom:20px;">Revenue (Last 6 Months)</h3>
                <canvas id="revenueChart" height="120"></canvas>
            </div>
            <div class="hotel-card" style="padding:28px;">
                <h3 style="font-weight:700; margin-bottom:20px;">Booking Status</h3>
                <canvas id="statusChart" height="120"></canvas>
            </div>
        </div>

        <!-- TOP HOTELS -->
        <div class="hotel-card" style="padding:28px; margin-bottom:32px;">
            <h3 style="font-weight:700; margin-bottom:20px;">Top Hotels by Bookings</h3>
            <div class="table-wrap" style="box-shadow:none;">
                <table class="data-table">
                    <thead>
                        <tr><th>Hotel</th><th>City</th><th>Bookings</th><th>Revenue</th><th>Performance</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $maxBookings = max(array_column($topHotels, 'total_bookings') ?: [1]);
                        foreach ($topHotels as $h):
                            $pct = $maxBookings > 0 ? round(($h['total_bookings'] / $maxBookings) * 100) : 0;
                        ?>
                        <tr>
                            <td style="font-weight:600;"><?= htmlspecialchars($h['name']) ?></td>
                            <td><?= htmlspecialchars($h['city']) ?></td>
                            <td><?= $h['total_bookings'] ?></td>
                            <td style="color:var(--primary); font-weight:700;"><?= formatPrice($h['revenue'] ?? 0) ?></td>
                            <td style="min-width:120px;">
                                <div style="background:var(--border); border-radius:50px; height:8px; overflow:hidden;">
                                    <div style="background:var(--gradient); height:100%; width:<?= $pct ?>%; border-radius:50px; transition:width 1s ease;"></div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- NEW USERS CHART -->
        <div class="hotel-card" style="padding:28px;">
            <h3 style="font-weight:700; margin-bottom:20px;">New Users (Last 6 Months)</h3>
            <canvas id="usersChart" height="80"></canvas>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const chartDefaults = {
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } }
};

// Revenue Chart
const revenueData = <?= json_encode($revenueData) ?>;
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: revenueData.map(d => d.month),
        datasets: [{
            label: 'Revenue',
            data: revenueData.map(d => parseFloat(d.revenue || 0)),
            backgroundColor: 'rgba(108,99,255,0.8)',
            borderRadius: 8,
        }]
    },
    options: { ...chartDefaults, plugins: { legend: { display: true } } }
});

// Status Pie Chart
const statusData = <?= json_encode($statusData) ?>;
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: statusData.map(d => d.status.charAt(0).toUpperCase() + d.status.slice(1)),
        datasets: [{
            data: statusData.map(d => d.count),
            backgroundColor: ['#f59e0b','#10b981','#ef4444','#6C63FF'],
            borderWidth: 0,
        }]
    },
    options: { plugins: { legend: { position: 'bottom' } }, cutout: '65%' }
});

// Users Chart
const usersData = <?= json_encode($usersData) ?>;
new Chart(document.getElementById('usersChart'), {
    type: 'line',
    data: {
        labels: usersData.map(d => d.month),
        datasets: [{
            label: 'New Users',
            data: usersData.map(d => d.count),
            borderColor: '#FF6584',
            backgroundColor: 'rgba(255,101,132,0.1)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#FF6584',
        }]
    },
    options: chartDefaults
});
</script>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
