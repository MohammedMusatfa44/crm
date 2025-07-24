@extends('layouts.layout')

@section('title', 'لوحة التحكم')

@section('styles')
<style>
    .dashboard-bg {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 2rem 0;
    }
    .dashboard-title {
        font-size: 2.1rem;
        font-weight: 700;
        color: #222;
        margin-bottom: 0.2rem;
    }
    .dashboard-subtitle {
        color: #888;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }
    .top-actions {
        display: flex;
        gap: 0.7rem;
        justify-content: flex-end;
        margin-bottom: 1.2rem;
    }
    .top-actions .btn {
        border-radius: 1.2rem;
        font-weight: 500;
        font-size: 1rem;
        padding: 0.5rem 1.2rem;
    }
    .top-actions .btn-settings {
        background: #e3f0ff;
        color: #0b58ca;
        border: none;
    }
    .top-actions .btn-add {
        background: #0b58ca;
        color: #fff;
        border: none;
    }
    .top-actions .btn-add:hover {
        background: #1976d2;
    }
    .stat-card {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
        color: #fff;
        border: none;
        border-radius: 1.2rem;
        box-shadow: 0 4px 24px rgba(33,150,243,0.10);
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
        min-height: 110px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        padding: 1.2rem 1.5rem;
    }
    .stat-card .stat-label {
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 0.3rem;
        opacity: 0.9;
    }
    .stat-card .stat-value {
        font-size: 2.2rem;
        font-weight: bold;
        opacity: 0.95;
    }
    .stat-card.reminder {
        background: linear-gradient(135deg, #4f5bd5 0%, #7f53ac 100%);
    }
    .stat-card.users {
        background: linear-gradient(135deg, #4f5bd5 0%, #43cea2 100%);
    }
    .stat-card.sub {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
    }
    .stat-card.categories {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
    }
    .reports-section {
        margin-top: 2.5rem;
    }
    .reports-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1.2rem;
        color: #222;
    }
    .reports-filters {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        align-items: center;
        margin-bottom: 1.2rem;
    }
    .reports-filters input, .reports-filters select {
        border-radius: 0.7rem;
        border: 1px solid #e3e6ea;
        padding: 0.3rem 0.7rem;
        font-size: 1rem;
        min-width: 60px;
    }
    .reports-filters .btn {
        background: #1976d2;
        color: #fff;
        border-radius: 0.7rem;
        font-weight: 500;
        padding: 0.3rem 1.2rem;
    }
    .reports-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.2rem;
    }
    .report-card {
        border-radius: 1.2rem;
        color: #fff;
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        min-height: 90px;
        padding: 1.2rem 1.2rem 1.2rem 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.08);
    }
    .report-card.blue { background: linear-gradient(135deg, #1976d2 0%, #42a5f5 100%); }
    .report-card.purple { background: linear-gradient(135deg, #7f53ac 0%, #647dee 100%); }
    .report-card.green { background: linear-gradient(135deg, #43cea2 0%, #185a9d 100%); }
    .report-card.orange { background: linear-gradient(135deg, #f7971eb0 0%, #ffd2005e 100%);}
    .report-card.red { background: linear-gradient(135deg, #f9536b 0%, #b91d73 100%); }
    .report-card.gray { background: linear-gradient(135deg, #bdc3c7 0%, #2c3e50 100%); }
    .report-card .report-label {
        font-size: 1.05rem;
        margin-bottom: 0.2rem;
        opacity: 0.92;
    }
    .report-card .report-value {
        font-size: 1.5rem;
        font-weight: bold;
        opacity: 0.98;
    }
</style>
@endsection

@section('content')
<div class="dashboard-bg">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <div class="dashboard-header-card" style="background:#fff; border-radius:1.2rem; box-shadow:0 2px 12px rgba(33,150,243,0.07); padding:1.5rem 2rem 1.2rem 2rem; margin-bottom:1.5rem; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between;">
                    <div>
                        <div class="dashboard-title">Dashboard</div>
                        <div class="dashboard-subtitle">Elfurat CRM.</div>
                    </div>
                    <div class="top-actions">
                        <button class="btn btn-settings">الإعدادات</button>
                        <button class="btn btn-add">إضافة قسم جديد</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <form class="dashboard-search-bar" style="margin-bottom:1.5rem;">
                    <input type="text" class="form-control" placeholder="Search ..." style="max-width:400px; border-radius:1.2rem; box-shadow:0 1px 4px rgba(33,150,243,0.07); display:inline-block;">
                </form>
            </div>
        </div>
        <div class="row mb-4 g-4">
            <div class="col-md-3">
                <div class="stat-card categories">
                    <div class="stat-label">Categories</div>
                    <div class="stat-value">{{ $departmentCount ?? 0 }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card sub">
                    <div class="stat-label">Sub Categories</div>
                    <div class="stat-value">{{ $subDepartmentCount ?? 0 }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card users">
                    <div class="stat-label">Users</div>
                    <div class="stat-value">{{ $userCount ?? 0 }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card reminder">
                    <div class="stat-label">Reminder</div>
                    <div class="stat-value">{{ $alertCount ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card" style="border-radius:1.2rem; box-shadow:0 2px 12px rgba(33,150,243,0.07); padding:1.2rem;">
                    <div style="font-weight:600; font-size:1.05rem; color:#1976d2; margin-bottom:0.7rem;">Users Chart</div>
                    <canvas id="usersChart" height="120"></canvas>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card" style="border-radius:1.2rem; box-shadow:0 2px 12px rgba(33,150,243,0.07); padding:1.2rem;">
                    <div style="font-weight:600; font-size:1.05rem; color:#1976d2; margin-bottom:0.7rem;">Reminder Chart</div>
                    <canvas id="reminderChart" height="120"></canvas>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card" style="border-radius:1.2rem; box-shadow:0 2px 12px rgba(33,150,243,0.07); padding:1.2rem;">
                    <div style="font-weight:600; font-size:1.05rem; color:#1976d2; margin-bottom:0.7rem;">Sub Categories Chart</div>
                    <canvas id="subCategoriesChart" height="120"></canvas>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card" style="border-radius:1.2rem; box-shadow:0 2px 12px rgba(33,150,243,0.07); padding:1.2rem;">
                    <div style="font-weight:600; font-size:1.05rem; color:#1976d2; margin-bottom:0.7rem;">Categories Chart</div>
                    <canvas id="categoriesChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card" style="border-radius:1.2rem; box-shadow:0 2px 12px rgba(33,150,243,0.07); padding:1.5rem;">
                    <div style="font-weight:600; font-size:1.1rem; color:#1976d2; margin-bottom:1rem;">Statistics Chart</div>
                    <canvas id="mainChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="reports-section">
            <div class="reports-title">Reports</div>
            <div class="reports-filters">
                <span>From:</span>
                <input type="text" placeholder="DD" maxlength="2" size="2">
                <input type="text" placeholder="MM" maxlength="2" size="2">
                <input type="text" placeholder="YY" maxlength="2" size="2">
                <span>To:</span>
                <input type="text" placeholder="DD" maxlength="2" size="2">
                <input type="text" placeholder="MM" maxlength="2" size="2">
                <input type="text" placeholder="YY" maxlength="2" size="2">
                <input type="text" placeholder="use" style="width:60px;">
                <select><option>الكويت</option></select>
                <button class="btn">Search</button>
            </div>
            <div class="reports-grid">
                <div class="report-card blue">
                    <div class="report-label">Deposits</div>
                    <div class="report-value">0</div>
                </div>
                <div class="report-card purple">
                    <div class="report-label">Follow</div>
                    <div class="report-value">0</div>
                </div>
                <div class="report-card orange">
                    <div class="report-label">Western</div>
                    <div class="report-value">0</div>
                </div>
                <div class="report-card red">
                    <div class="report-label">Hot</div>
                    <div class="report-value">0</div>
                </div>
                <div class="report-card blue">
                    <div class="report-label">No answer</div>
                    <div class="report-value">0</div>
                </div>
                <div class="report-card blue">
                    <div class="report-label">No answer1</div>
                    <div class="report-value">0</div>
                </div>
                <div class="report-card blue">
                    <div class="report-label">No answer2</div>
                    <div class="report-value">0</div>
                </div>
                <div class="report-card gray">
                    <div class="report-label">Not interested</div>
                    <div class="report-value">0</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // Modern Chart.js line chart with gradient
    const ctx = document.getElementById('mainChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 200);
    gradient.addColorStop(0, 'rgba(33,150,243,0.3)');
    gradient.addColorStop(1, 'rgba(33,150,243,0.05)');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
            datasets: [{
                label: 'العملاء الجدد',
                data: [12, 19, 3, 5, 2, 3],
                fill: true,
                backgroundColor: gradient,
                borderColor: '#0b58ca',
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#0b58ca',
                pointRadius: 6,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#e3e6ea' },
                    ticks: { color: '#888' }
                },
                x: {
                    grid: { color: '#f3f4f6' },
                    ticks: { color: '#888' }
                }
            }
        }
    });

    // Users Chart
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    const usersGradient = usersCtx.createLinearGradient(0, 0, 0, 200);
    usersGradient.addColorStop(0, 'rgba(33,150,243,0.3)');
    usersGradient.addColorStop(1, 'rgba(33,150,243,0.05)');
    new Chart(usersCtx, {
        type: 'line',
        data: {
            labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
            datasets: [{
                label: 'Users',
                data: [2, 3, 4, 3, 5, 6],
                fill: true,
                backgroundColor: usersGradient,
                borderColor: '#0b58ca',
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#0b58ca',
                pointRadius: 6,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#e3e6ea' }, ticks: { color: '#888' } },
                x: { grid: { color: '#f3f4f6' }, ticks: { color: '#888' } }
            }
        }
    });
    // Reminder Chart
    const reminderCtx = document.getElementById('reminderChart').getContext('2d');
    const reminderGradient = reminderCtx.createLinearGradient(0, 0, 0, 200);
    reminderGradient.addColorStop(0, 'rgba(123, 67, 151, 0.3)');
    reminderGradient.addColorStop(1, 'rgba(123, 67, 151, 0.05)');
    new Chart(reminderCtx, {
        type: 'line',
        data: {
            labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
            datasets: [{
                label: 'Reminder',
                data: [1, 2, 1, 3, 2, 4],
                fill: true,
                backgroundColor: reminderGradient,
                borderColor: '#7f53ac',
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#7f53ac',
                pointRadius: 6,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#e3e6ea' }, ticks: { color: '#888' } },
                x: { grid: { color: '#f3f4f6' }, ticks: { color: '#888' } }
            }
        }
    });
    // Sub Categories Chart
    const subCategoriesCtx = document.getElementById('subCategoriesChart').getContext('2d');
    const subCategoriesGradient = subCategoriesCtx.createLinearGradient(0, 0, 0, 200);
    subCategoriesGradient.addColorStop(0, 'rgba(33,150,243,0.3)');
    subCategoriesGradient.addColorStop(1, 'rgba(33,150,243,0.05)');
    new Chart(subCategoriesCtx, {
        type: 'line',
        data: {
            labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
            datasets: [{
                label: 'Sub Categories',
                data: [1, 1, 2, 2, 3, 2],
                fill: true,
                backgroundColor: subCategoriesGradient,
                borderColor: '#5f8fff',
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#5f8fff',
                pointRadius: 6,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#e3e6ea' }, ticks: { color: '#888' } },
                x: { grid: { color: '#f3f4f6' }, ticks: { color: '#888' } }
            }
        }
    });
    // Categories Chart
    const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
    const categoriesGradient = categoriesCtx.createLinearGradient(0, 0, 0, 200);
    categoriesGradient.addColorStop(0, 'rgba(33,150,243,0.3)');
    categoriesGradient.addColorStop(1, 'rgba(33,150,243,0.05)');
    new Chart(categoriesCtx, {
        type: 'line',
        data: {
            labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
            datasets: [{
                label: 'Categories',
                data: [2, 2, 2, 3, 3, 4],
                fill: true,
                backgroundColor: categoriesGradient,
                borderColor: '#4f5bd5',
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4f5bd5',
                pointRadius: 6,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#e3e6ea' }, ticks: { color: '#888' } },
                x: { grid: { color: '#f3f4f6' }, ticks: { color: '#888' } }
            }
        }
    });
</script>
@endsection
