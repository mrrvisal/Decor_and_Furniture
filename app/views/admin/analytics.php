<?php require __DIR__ . '/../helpers.php'; ?>
<?php
$monthlyJson      = json_encode(array_values($monthly ?? []));
$statusJson       = json_encode(array_values($statusBreakdown ?? []));
$categoryJson     = json_encode(array_values($categoryRevenue ?? []));
$paymentJson      = json_encode(array_values($paymentSplit ?? []));
$lowStockJson     = json_encode(array_values($lowStock ?? []));
$topProductsJson  = json_encode(array_values($topProducts ?? []));
$filters          = $filters ?? [];
$categories       = $categories ?? [];
$hasFilters       = ($filters['period'] ?? 'all') !== 'all'
  || !empty($filters['date_from'])
  || !empty($filters['date_to'])
  || !empty($filters['status'])
  || !empty($filters['payment_method'])
  || !empty($filters['category_id']);
?>
<section class="analytics-section">

  <!-- Header -->
  <div class="analytics-header">
    <div>
      <h1>Analytics Dashboard</h1>
      <p class="muted">Live business intelligence from your store data</p>
    </div>
    <span class="analytics-badge">
      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/>
        <polyline points="12 6 12 12 16 14"/></svg>
      Live data
    </span>
  </div>

  <form method="get" action="<?= base_url('admin/analytics') ?>" class="analytics-filters">
    <div class="analytics-filter-group">
      <label for="analytics-period">Period</label>
      <select name="period" id="analytics-period">
        <?php
          $periodOptions = [
            'all' => 'All time',
            'last_6_months' => 'Last 6 months',
            'last_7' => 'Last 7 days',
            'last_30' => 'Last 30 days',
            'last_90' => 'Last 90 days',
            'this_year' => 'This year',
            'custom' => 'Custom dates',
          ];
        ?>
        <?php foreach ($periodOptions as $value => $label): ?>
          <option value="<?= e($value) ?>" <?= (($filters['period'] ?? 'all') === $value) ? 'selected' : '' ?>>
            <?= e($label) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="analytics-filter-group analytics-custom-date">
      <label for="analytics-date-from">From</label>
      <input type="date" name="date_from" id="analytics-date-from" value="<?= e($filters['date_from'] ?? '') ?>">
    </div>

    <div class="analytics-filter-group analytics-custom-date">
      <label for="analytics-date-to">To</label>
      <input type="date" name="date_to" id="analytics-date-to" value="<?= e($filters['date_to'] ?? '') ?>">
    </div>

    <div class="analytics-filter-group">
      <label for="analytics-status">Status</label>
      <select name="status" id="analytics-status">
        <option value="">All statuses</option>
        <?php foreach (['pending', 'waiting_payment', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'] as $status): ?>
          <option value="<?= e($status) ?>" <?= (($filters['status'] ?? '') === $status) ? 'selected' : '' ?>>
            <?= e(ucfirst(str_replace('_', ' ', $status))) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="analytics-filter-group">
      <label for="analytics-payment">Payment</label>
      <select name="payment_method" id="analytics-payment">
        <option value="">All payments</option>
        <option value="qr_code" <?= (($filters['payment_method'] ?? '') === 'qr_code') ? 'selected' : '' ?>>QR code</option>
        <option value="pay_later" <?= (($filters['payment_method'] ?? '') === 'pay_later') ? 'selected' : '' ?>>Pay later</option>
      </select>
    </div>

    <div class="analytics-filter-group">
      <label for="analytics-category">Category</label>
      <select name="category" id="analytics-category">
        <option value="">All categories</option>
        <?php foreach ($categories as $category): ?>
          <option value="<?= (int) $category['id'] ?>" <?= (($filters['category_id'] ?? null) == $category['id']) ? 'selected' : '' ?>>
            <?= e($category['name'] ?? '') ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="analytics-filter-actions">
      <button type="submit" class="analytics-filter-btn">Apply</button>
      <?php if ($hasFilters): ?>
        <a href="<?= base_url('admin/analytics') ?>" class="analytics-clear-btn">Clear</a>
      <?php endif; ?>
    </div>
  </form>

  <!-- KPI Cards -->
  <div class="kpi-grid">
    <div class="kpi-card">
      <span class="kpi-icon" style="background:rgba(29,158,117,.12);color:#1D9E75">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/>
          <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      </span>
      <div>
        <p class="kpi-label">Total revenue</p>
        <p class="kpi-value">$<?= number_format($stats['total_revenue'] ?? 0, 2) ?></p>
      </div>
    </div>
    <div class="kpi-card">
      <span class="kpi-icon" style="background:rgba(55,138,221,.12);color:#378ADD">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
      </span>
      <div>
        <p class="kpi-label">Total orders</p>
        <p class="kpi-value"><?= (int)($stats['total_orders'] ?? 0) ?></p>
      </div>
    </div>
    <div class="kpi-card">
      <span class="kpi-icon" style="background:rgba(245,158,11,.12);color:#f59e0b">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/>
          <polyline points="12 6 12 12 16 14"/></svg>
      </span>
      <div>
        <p class="kpi-label">Pending orders</p>
        <p class="kpi-value"><?= (int)($stats['pending_orders'] ?? 0) ?></p>
      </div>
    </div>
    <div class="kpi-card">
      <span class="kpi-icon" style="background:rgba(127,119,221,.12);color:#7F77DD">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      </span>
      <div>
        <p class="kpi-label">Total customers</p>
        <p class="kpi-value"><?= (int)($userStats['total'] ?? 0) ?></p>
      </div>
    </div>
    <div class="kpi-card">
      <span class="kpi-icon" style="background:rgba(212,83,126,.12);color:#D4537E">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2">
          <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
          <line x1="7" y1="7" x2="7.01" y2="7"/></svg>
      </span>
      <div>
        <p class="kpi-label">Product total</p>
        <p class="kpi-value"><?= (int)($productTotal ?? 0) ?></p>
      </div>
    </div>
  </div>

  <!-- Row 1: Revenue + Status -->
  <div class="chart-row">
    <div class="chart-card chart-card-lg">
      <div class="chart-card-header">
        <div>
          <p class="chart-title">Monthly revenue</p>
          <p class="chart-sub">Order value for selected filters</p>
        </div>
      </div>
      <div style="position:relative;height:220px">
        <canvas id="revenueChart" role="img" aria-label="Line chart of monthly revenue">Monthly revenue</canvas>
      </div>
    </div>
    <div class="chart-card">
      <div class="chart-card-header">
        <div>
          <p class="chart-title">Order status</p>
          <p class="chart-sub">Distribution for selected filters</p>
        </div>
      </div>
      <div id="status-legend" class="chart-legend"></div>
      <div style="position:relative;height:180px">
        <canvas id="statusChart" role="img" aria-label="Doughnut chart of order statuses">Order statuses</canvas>
      </div>
    </div>
  </div>

  <!-- Row 2: Category + Payment -->
  <div class="chart-row">
    <div class="chart-card chart-card-lg">
      <div class="chart-card-header">
        <div>
          <p class="chart-title">Revenue by category</p>
          <p class="chart-sub">Order value by selected filters</p>
        </div>
      </div>
      <div style="position:relative;height:220px">
        <canvas id="categoryChart" role="img" aria-label="Bar chart of revenue by category">Category revenue</canvas>
      </div>
    </div>
    <div class="chart-card">
      <div class="chart-card-header">
        <div>
          <p class="chart-title">Payment methods</p>
          <p class="chart-sub">QR code vs pay later</p>
        </div>
      </div>
      <div id="pay-legend" class="chart-legend"></div>
      <div style="position:relative;height:180px">
        <canvas id="paymentChart" role="img" aria-label="Pie chart of payment methods">Payment split</canvas>
      </div>
    </div>
  </div>

  <!-- Row 3: Stock + Top Products -->
  <div class="chart-row">
    <div class="chart-card chart-card-lg">
      <div class="chart-card-header">
        <div>
          <p class="chart-title">Stock levels</p>
          <p class="chart-sub">
            <span style="color:#E24B4A">&#9632;</span> Out &nbsp;
            <span style="color:#EF9F27">&#9632;</span> Low (&le;10) &nbsp;
            <span style="color:#1D9E75">&#9632;</span> OK
          </p>
        </div>
      </div>
      <div style="position:relative;height:220px">
        <canvas id="stockChart" role="img" aria-label="Bar chart of current stock per product">Stock levels</canvas>
      </div>
    </div>
    <div class="chart-card">
      <p class="chart-title" style="margin-bottom:.75rem">Top products</p>
      <div id="top-products-list"></div>
    </div>
  </div>

</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
const monthly     = <?= $monthlyJson ?>;
const statuses    = <?= $statusJson ?>;
const categories  = <?= $categoryJson ?>;
const payments    = <?= $paymentJson ?>;
const topProds    = <?= $topProductsJson ?>;

const isDark = matchMedia('(prefers-color-scheme: dark)').matches;
const grid   = isDark ? 'rgba(255,255,255,.07)' : 'rgba(0,0,0,.06)';
const tick   = isDark ? '#999' : '#777';
const font   = { size: 11 };

const periodSelect = document.getElementById('analytics-period');
const customDateFields = document.querySelectorAll('.analytics-custom-date');
function syncCustomDateFields() {
  const showCustom = periodSelect && periodSelect.value === 'custom';
  customDateFields.forEach(field => {
    field.style.display = showCustom ? 'flex' : 'none';
  });
}
syncCustomDateFields();
periodSelect?.addEventListener('change', syncCustomDateFields);

/* ---- Revenue line chart ---- */
const monthLabels = monthly.map(r => r.month || r.month_num);
const monthRevs   = monthly.map(r => parseFloat(r.revenue));
new Chart(document.getElementById('revenueChart'), {
  type: 'line',
  data: {
    labels: monthLabels.length ? monthLabels : ['No data'],
    datasets: [{
      label: 'Revenue ($)',
      data: monthRevs.length ? monthRevs : [0],
      borderColor: '#1D9E75', backgroundColor: 'rgba(29,158,117,.1)',
      borderWidth: 2, pointBackgroundColor: '#1D9E75', pointRadius: 4,
      fill: true, tension: .4
    }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      x: { grid: { color: grid }, ticks: { color: tick, font } },
      y: { grid: { color: grid }, ticks: { color: tick, font, callback: v => '$' + Number(v).toLocaleString() } }
    }
  }
});

/* ---- Status doughnut ---- */
const stColors = { delivered:'#1D9E75', paid:'#378ADD', processing:'#7F77DD',
                   shipped:'#EF9F27', pending:'#888', waiting_payment:'#D4537E', cancelled:'#E24B4A' };
const stLabels = statuses.map(r => r.status);
const stVals   = statuses.map(r => parseInt(r.cnt));
const stCols   = stLabels.map(l => stColors[l] || '#888');
const legend   = document.getElementById('status-legend');
stLabels.forEach((l,i) => {
  legend.innerHTML += `<span style="display:flex;align-items:center;gap:4px;font-size:11px;color:var(--text-muted)">
    <span style="width:9px;height:9px;border-radius:2px;background:${stCols[i]};display:inline-block"></span>
    ${l.replace('_',' ')} (${stVals[i]})</span>`;
});
new Chart(document.getElementById('statusChart'), {
  type: 'doughnut',
  data: { labels: stLabels, datasets: [{ data: stVals, backgroundColor: stCols, borderWidth: 2, borderColor: isDark ? '#1a1a1a' : '#fff' }] },
  options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, cutout: '62%' }
});

/* ---- Category bar chart ---- */
const catLabels = categories.map(r => r.name);
const catRevs   = categories.map(r => parseFloat(r.revenue));
new Chart(document.getElementById('categoryChart'), {
  type: 'bar',
  data: {
    labels: catLabels.length ? catLabels : ['No data'],
    datasets: [{ label: 'Revenue ($)', data: catRevs.length ? catRevs : [0],
      backgroundColor: ['#1D9E75','#378ADD','#7F77DD','#EF9F27','#D4537E','#E24B4A'].slice(0, catLabels.length),
      borderRadius: 4 }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      x: { grid: { display: false }, ticks: { color: tick, font } },
      y: { grid: { color: grid }, ticks: { color: tick, font, callback: v => '$' + (v/1000).toFixed(0) + 'k' } }
    }
  }
});

/* ---- Payment pie ---- */
const payLabels = payments.map(r => r.payment_method.replace('_',' '));
const payVals   = payments.map(r => parseInt(r.cnt));
const payCols   = ['#1D9E75','#378ADD'];
const payTotal  = payVals.reduce((a,b)=>a+b,0);
const payLegend = document.getElementById('pay-legend');
payLabels.forEach((l,i) => {
  const pct = payTotal > 0 ? Math.round(payVals[i]/payTotal*100) : 0;
  payLegend.innerHTML += `<span style="display:flex;align-items:center;gap:4px;font-size:11px;color:var(--text-muted)">
    <span style="width:9px;height:9px;border-radius:2px;background:${payCols[i]};display:inline-block"></span>
    ${l} ${pct}%</span>`;
});
new Chart(document.getElementById('paymentChart'), {
  type: 'pie',
  data: { labels: payLabels, datasets: [{ data: payVals.length ? payVals : [1,0], backgroundColor: payCols, borderWidth: 2, borderColor: isDark ? '#1a1a1a' : '#fff' }] },
  options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
});

/* ---- Stock bar chart (from PHP low-stock + known products) ---- */
const lowStockData = <?= $lowStockJson ?>;
const stockLabels = lowStockData.map(r => r.name.length > 18 ? r.name.substring(0,18)+'…' : r.name);
const stockVals   = lowStockData.map(r => parseInt(r.stock));
const stockCols   = stockVals.map(v => v === 0 ? '#E24B4A' : v <= 5 ? '#D4537E' : '#EF9F27');
if (stockLabels.length) {
  new Chart(document.getElementById('stockChart'), {
    type: 'bar',
    data: { labels: stockLabels, datasets: [{ label: 'Units', data: stockVals, backgroundColor: stockCols, borderRadius: 3 }] },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false }, ticks: { color: tick, font: { size: 10 }, maxRotation: 40, autoSkip: false } },
        y: { grid: { color: grid }, ticks: { color: tick, font } }
      }
    }
  });
} else {
  document.getElementById('stockChart').parentElement.innerHTML =
    '<p style="text-align:center;color:var(--text-muted);padding:3rem 0;font-size:.9rem">All products have healthy stock levels</p>';
}

/* ---- Top products list ---- */
const maxRev = Math.max(...topProds.map(p => parseFloat(p.revenue)), 1);
const colors = ['#1D9E75','#378ADD','#7F77DD','#EF9F27','#D4537E'];
const list   = document.getElementById('top-products-list');
if (topProds.length) {
  topProds.forEach((p, i) => {
    const pct = Math.round(parseFloat(p.revenue) / maxRev * 100);
    const rev = parseFloat(p.revenue).toLocaleString('en-US', { style:'currency', currency:'USD', maximumFractionDigits:0 });
    list.innerHTML += `<div style="margin-bottom:10px">
      <div style="display:flex;justify-content:space-between;margin-bottom:3px">
        <span style="font-size:12px;color:var(--text);max-width:65%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${p.name}</span>
        <span style="font-size:12px;font-weight:600;color:var(--text)">${rev}</span>
      </div>
      <div style="height:6px;background:var(--border);border-radius:3px;overflow:hidden">
        <div style="height:100%;width:${pct}%;background:${colors[i]};border-radius:3px"></div>
      </div>
      <span style="font-size:11px;color:var(--text-muted)">${parseInt(p.units)} units sold</span>
    </div>`;
  });
} else {
  list.innerHTML = '<p style="color:var(--text-muted);font-size:.9rem">No confirmed orders yet</p>';
}
</script>

<style>
.analytics-section { padding: 0; }
.analytics-header {
  display: flex; justify-content: space-between; align-items: flex-start;
  margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;
}
.analytics-header h1 { margin: 0 0 .25rem; font-size: 1.75rem; font-weight: 700; color: var(--text); }
.analytics-badge {
  display: inline-flex; align-items: center; gap: .4rem;
  padding: .4rem .9rem; background: var(--success-bg); color: var(--success);
  border-radius: 999px; font-size: .8rem; font-weight: 600;
}
.analytics-filters {
  display: flex; align-items: flex-end; flex-wrap: wrap; gap: .8rem;
  margin-bottom: 1.5rem; padding: 1rem;
  background: var(--bg-card); border: 1px solid var(--border);
  border-radius: var(--radius); box-shadow: var(--shadow);
}
.analytics-filter-group {
  display: flex; flex-direction: column; gap: .35rem;
  min-width: 145px; flex: 1 1 145px;
}
.analytics-filter-group label {
  color: var(--text-muted); font-size: .74rem; font-weight: 700;
  text-transform: uppercase; letter-spacing: .02em;
}
.analytics-filter-group select,
.analytics-filter-group input {
  width: 100%; height: 40px; border: 1px solid var(--border);
  background: var(--bg); color: var(--text); border-radius: var(--radius-sm);
  padding: 0 .75rem; font: inherit; font-size: .9rem;
}
.analytics-filter-group select:focus,
.analytics-filter-group input:focus {
  outline: none; border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(29,158,117,.12);
}
.analytics-filter-actions {
  display: flex; align-items: center; gap: .5rem; flex: 0 0 auto;
}
.analytics-filter-btn,
.analytics-clear-btn {
  height: 40px; display: inline-flex; align-items: center; justify-content: center;
  border-radius: var(--radius-sm); padding: 0 .9rem; font-weight: 700;
  font-size: .86rem; text-decoration: none; border: 1px solid transparent;
}
.analytics-filter-btn {
  background: var(--primary); color: #fff; cursor: pointer;
}
.analytics-clear-btn {
  color: var(--text); background: transparent; border-color: var(--border);
}
.kpi-grid {
  display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
  gap: 1rem; margin-bottom: 1.5rem;
}
.kpi-card {
  background: var(--bg-card); border-radius: var(--radius); box-shadow: var(--shadow);
  padding: 1.25rem; display: flex; align-items: center; gap: 1rem;
  transition: transform var(--transition), box-shadow var(--transition);
}
.kpi-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-hover); }
.kpi-icon {
  width: 44px; height: 44px; border-radius: var(--radius-sm);
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.kpi-label { font-size: .8rem; color: var(--text-muted); margin: 0 0 .2rem; }
.kpi-value { font-size: 1.4rem; font-weight: 700; color: var(--text); margin: 0; }
.chart-row {
  display: grid; grid-template-columns: 1.5fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;
}
@media (max-width: 768px) { .chart-row { grid-template-columns: 1fr; } }
@media (max-width: 768px) {
  .analytics-filter-group,
  .analytics-filter-actions {
    flex: 1 1 100%;
  }
  .analytics-filter-btn,
  .analytics-clear-btn {
    flex: 1;
  }
}
.chart-card {
  background: var(--bg-card); border-radius: var(--radius);
  box-shadow: var(--shadow); padding: 1.25rem; overflow: hidden;
}
.chart-card-lg {}
.chart-card-header { margin-bottom: 1rem; }
.chart-title { margin: 0 0 .2rem; font-size: 1rem; font-weight: 600; color: var(--text); }
.chart-sub { margin: 0; font-size: .8rem; color: var(--text-muted); }
.chart-legend { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 10px; }
.alert-card {
  background: var(--bg-card); border-radius: var(--radius); box-shadow: var(--shadow);
  overflow: hidden; margin-bottom: 1.25rem;
}
.alert-card-header {
  display: flex; align-items: center; gap: .6rem;
  padding: 1rem 1.25rem; background: var(--warning-bg);
  color: #92400e; font-weight: 600; font-size: .95rem;
  border-bottom: 1px solid var(--border);
}
</style>
