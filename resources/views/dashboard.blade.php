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
    .reports-filters input[type="date"] {
        min-width: 140px;
        padding: 0.3rem 0.7rem;
        border-radius: 0.7rem;
        border: 1px solid #e3e6ea;
        font-size: 1rem;
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
    .reports-filters {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }
    .reports-filters .form-control {
        border: 2px solid #e9ecef;
        border-radius: 0.8rem;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        min-width: 150px;
    }
    .reports-filters .form-control:focus {
        border-color: #4f5bd5;
        box-shadow: 0 0 0 0.2rem rgba(79, 91, 213, 0.25);
        outline: none;
    }
    .search-section {
        margin-bottom: 2rem;
    }
    .search-box {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.08);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .search-box .form-control {
        border: 2px solid #e9ecef;
        border-radius: 0.8rem;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        flex: 1;
    }
    .search-box .form-control:focus {
        border-color: #4f5bd5;
        box-shadow: 0 0 0 0.2rem rgba(79, 91, 213, 0.25);
        outline: none;
    }
    .search-box .btn {
        background: #4f5bd5;
        color: #fff;
        border: none;
        border-radius: 0.8rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .search-box .btn:hover {
        background: #3f4db5;
        transform: translateY(-1px);
    }
    .chart-card {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.08);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        height: 300px;
    }
    .chart-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #222;
    }
    .report-card {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.08);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: transform 0.2s ease;
        cursor: pointer;
    }
    .report-card:hover {
        transform: translateY(-2px);
    }
    .report-card .report-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.5rem;
        color: #fff;
    }
    .report-card .report-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #222;
    }
    .report-card .report-value {
        font-size: 1.8rem;
        font-weight: bold;
        color: #ffffff;
    }
    .report-card .report-change {
        font-size: 0.9rem;
        color: #28a745;
        margin-top: 0.5rem;
    }
    .report-card .report-change.negative {
        color: #dc3545;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .dashboard-bg {
            padding: 1rem 0;
        }
        .dashboard-title {
            font-size: 1.5rem;
        }
        .dashboard-subtitle {
            font-size: 1rem;
        }
        .top-actions {
            flex-direction: column;
            gap: 0.5rem;
        }
        .top-actions .btn {
            width: 100%;
            font-size: 0.85rem;
            padding: 0.6rem 1rem;
        }
        .stat-card {
            min-height: 90px;
            padding: 1rem;
        }
        .stat-card .stat-label {
            font-size: 1rem;
        }
        .stat-card .stat-value {
            font-size: 1.8rem;
        }
        .search-box {
            flex-direction: column;
            gap: 0.8rem;
        }
        .search-box .form-control {
            width: 100%;
        }
        .search-box .btn {
            width: 100%;
        }
        .reports-filters {
            flex-direction: column;
        }
        .reports-filters .form-control {
            width: 100%;
            min-width: auto;
        }
        .chart-card {
            height: 250px;
            padding: 1rem;
        }
        .chart-title {
            font-size: 1rem;
        }
        .report-card {
            padding: 1rem;
        }
        .report-card .report-icon {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }
        .report-card .report-title {
            font-size: 0.9rem;
        }
        .report-card .report-value {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .dashboard-header-card {
            padding: 1rem !important;
            margin-bottom: 1rem !important;
        }
        .stat-card {
            min-height: 80px;
            padding: 0.8rem;
        }
        .stat-card .stat-label {
            font-size: 0.9rem;
        }
        .stat-card .stat-value {
            font-size: 1.5rem;
        }
        .chart-card {
            height: 200px;
            padding: 0.8rem;
        }
        .report-card {
            padding: 0.8rem;
        }
        .report-card .report-icon {
            width: 35px;
            height: 35px;
            font-size: 1rem;
        }
        .report-card .report-title {
            font-size: 0.85rem;
        }
        .report-card .report-value {
            font-size: 1.3rem;
        }
        .top-actions .btn {
            font-size: 0.8rem;
            padding: 0.5rem 0.8rem;
        }
        .top-actions .btn i {
            margin-right: 0.3rem;
        }
    }

    @media (max-width: 480px) {
        .dashboard-bg {
            padding: 0.5rem 0;
        }
        .stat-card {
            min-height: 70px;
            padding: 0.6rem;
        }
        .stat-card .stat-label {
            font-size: 0.8rem;
        }
        .stat-card .stat-value {
            font-size: 1.3rem;
        }
        .chart-card {
            height: 180px;
            padding: 0.6rem;
        }
        .report-card {
            padding: 0.6rem;
        }
        .report-card .report-icon {
            width: 30px;
            height: 30px;
            font-size: 0.9rem;
        }
        .report-card .report-title {
            font-size: 0.8rem;
        }
        .report-card .report-value {
            font-size: 1.2rem;
        }
    }

    /* User Information Styles */
    .user-info-card {
        margin-top: 0.5rem;
        padding: 0.8rem 1.2rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 0.8rem;
        border-left: 4px solid #0b58ca;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .user-info-card .info-row {
        font-size: 0.9rem;
        color: #495057;
        margin-bottom: 0.3rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .user-info-card .info-row:last-child {
        margin-bottom: 0;
    }
    .user-info-card .info-label {
        font-weight: 600;
        color: #495057;
        min-width: 80px;
    }
    .user-info-card .info-value {
        color: #212529;
    }
    .role-badge {
        padding: 0.2rem 0.6rem;
        border-radius: 0.4rem;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .role-super-admin {
        background: #dc3545;
        color: white;
    }
    .role-admin {
        background: #fd7e14;
        color: white;
    }
    .role-employee {
        background: #28a745;
        color: white;
    }
    .role-unknown {
        background: #6c757d;
        color: white;
    }
    .permissions-list {
        font-size: 0.8rem;
        color: #6c757d;
        max-width: 300px;
        word-wrap: break-word;
        line-height: 1.3;
    }
    .permissions-list.empty {
        font-style: italic;
        color: #adb5bd;
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
                        <div class="dashboard-title">                                <span class="info-value">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="dashboard-subtitle">                                <span class="info-value">{{ auth()->user()->email }}</span>
                        </div>
                        <!-- User Information -->

                    </div>
                    <div class="top-actions">
                        @can('dashboard.add_section')
                        <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">إضافة قسم جديد</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        @can('dashboard.view')

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

            <!-- Debug Section (temporary) -->
            <div class="reports-filters">
                <span>القسم الرئيسي:</span>
                <select id="departmentFilter" class="form-select" style="width: auto;">
                    <option value="">جميع الأقسام</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
                <span>القسم الفرعي:</span>
                <select id="subDepartmentFilter" class="form-select" style="width: auto;">
                    <option value="">جميع الأقسام الفرعية</option>
                    @foreach($subDepartments as $subDept)
                        <option value="{{ $subDept->id }}" {{ $subDepartmentId == $subDept->id ? 'selected' : '' }}>
                            {{ $subDept->name }}
                        </option>
                    @endforeach
                </select>
                <span>من تاريخ:</span>
                <input type="date" id="startDateFilter" class="form-control" style="width: auto;"
                       value="{{ request('start_date', '') }}" placeholder="من تاريخ">
                <span>إلى تاريخ:</span>
                <input type="date" id="endDateFilter" class="form-control" style="width: auto;"
                       value="{{ request('end_date', '') }}" placeholder="إلى تاريخ">
                <button class="btn btn-primary" onclick="applyFilters()">تطبيق الفلتر</button>
                <button class="btn btn-secondary" onclick="clearFilters()">مسح الفلتر</button>
            </div>
            <div class="reports-grid">
                <div class="report-card blue">
                    <div class="report-label">No answer</div>
                    <div class="report-value">{{ $customerStatusCounts['no_answer'] ?? 0 }}</div>
                </div>
                <div class="report-card red">
                    <div class="report-label">Hot</div>
                    <div class="report-value">{{ $customerStatusCounts['hot'] ?? 0 }}</div>
                </div>
                <div class="report-card orange">
                    <div class="report-label">Western</div>
                    <div class="report-value">{{ $customerStatusCounts['western'] ?? 0 }}</div>
                </div>
                <div class="report-card purple">
                    <div class="report-label">Follow</div>
                    <div class="report-value">{{ $customerStatusCounts['follow'] ?? 0 }}</div>
                </div>
                <div class="report-card blue">
                    <div class="report-label">Deposits</div>
                    <div class="report-value">{{ $customerStatusCounts['deposits'] ?? 0 }}</div>
                </div>
                <div class="report-card gray">
                    <div class="report-label">Not interested</div>
                    <div class="report-value">{{ $customerStatusCounts['not_interested'] ?? 0 }}</div>
                </div>
                <div class="report-card blue">
                    <div class="report-label">No answer2</div>
                    <div class="report-value">{{ $customerStatusCounts['no_answer2'] ?? 0 }}</div>
                </div>
                <div class="report-card blue">
                    <div class="report-label">No answer1</div>
                    <div class="report-value">{{ $customerStatusCounts['no_answer1'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            ليس لديك صلاحية لعرض لوحة التحكم
        </div>
        @endcan
    </div>
</div>
<!-- Modal: إضافة قسم -->
@can('dashboard.add_section')
<div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDepartmentModalLabel">إضافة قسم جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('departments.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">اسم القسم</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">حفظ</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // Global variables for charts
    let mainChart, usersChart, reminderChart, subCategoriesChart, categoriesChart;

    // Chart data from controller
    const chartData = @json($chartData ?? []);
    const statusCounts = @json($customerStatusCounts ?? []);

    // Initialize charts with real data
    function initializeCharts() {
        // Main Chart
        const ctx = document.getElementById('mainChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, 'rgba(33,150,243,0.3)');
        gradient.addColorStop(1, 'rgba(33,150,243,0.05)');

        mainChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.months || ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                datasets: [{
                    label: 'العملاء الجدد',
                    data: chartData.customers || [12, 19, 3, 5, 2, 3],
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

        usersChart = new Chart(usersCtx, {
            type: 'line',
            data: {
                labels: chartData.months || ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                datasets: [{
                    label: 'Users',
                    data: chartData.users || [2, 3, 4, 3, 5, 6],
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

        reminderChart = new Chart(reminderCtx, {
            type: 'line',
            data: {
                labels: chartData.months || ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                datasets: [{
                    label: 'Reminder',
                    data: chartData.notifications || [0, 0, 0, 0, 0, 0], // Real notification data
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

        subCategoriesChart = new Chart(subCategoriesCtx, {
            type: 'line',
            data: {
                labels: chartData.months || ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                datasets: [{
                    label: 'Sub Categories',
                    data: chartData.sub_departments || [1, 1, 2, 2, 3, 2],
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

        categoriesChart = new Chart(categoriesCtx, {
            type: 'line',
            data: {
                labels: chartData.months || ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                datasets: [{
                    label: 'Categories',
                    data: chartData.departments || [2, 2, 2, 3, 3, 4],
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
    }

    // Update charts with new data
    function updateCharts(newChartData) {
        if (mainChart) {
            mainChart.data.datasets[0].data = newChartData.customers || [];
            mainChart.update();
        }
        if (usersChart) {
            usersChart.data.datasets[0].data = newChartData.users || [];
            usersChart.update();
        }
        if (reminderChart) {
            reminderChart.data.datasets[0].data = newChartData.notifications || [];
            reminderChart.update();
        }
        if (subCategoriesChart) {
            subCategoriesChart.data.datasets[0].data = newChartData.sub_departments || [];
            subCategoriesChart.update();
        }
        if (categoriesChart) {
            categoriesChart.data.datasets[0].data = newChartData.departments || [];
            categoriesChart.update();
        }
    }

    // Update reports with new data
    function updateReports(newStatusCounts) {
        document.querySelector('.report-card.blue .report-value').textContent = newStatusCounts.deposits || 0;
        document.querySelectorAll('.report-card.purple .report-value')[0].textContent = newStatusCounts.follow || 0;
        document.querySelectorAll('.report-card.orange .report-value')[0].textContent = newStatusCounts.western || 0;
        document.querySelectorAll('.report-card.red .report-value')[0].textContent = newStatusCounts.hot || 0;
        document.querySelectorAll('.report-card.blue .report-value')[1].textContent = newStatusCounts.no_answer || 0;
        document.querySelectorAll('.report-card.blue .report-value')[2].textContent = newStatusCounts.no_answer1 || 0;
        document.querySelectorAll('.report-card.blue .report-value')[3].textContent = newStatusCounts.no_answer2 || 0;
        document.querySelector('.report-card.gray .report-value').textContent = newStatusCounts.not_interested || 0;
    }

    // Apply filters
    function applyFilters() {
        const departmentId = document.getElementById('departmentFilter').value;
        const subDepartmentId = document.getElementById('subDepartmentFilter').value;
        const startDate = document.getElementById('startDateFilter').value;
        const endDate = document.getElementById('endDateFilter').value;

        // Validate date range
        if (startDate && endDate && startDate > endDate) {
            alert('تاريخ البداية يجب أن يكون قبل تاريخ النهاية');
            return;
        }

        // Update URL with filters
        const url = new URL(window.location);
        if (departmentId) url.searchParams.set('department_id', departmentId);
        else url.searchParams.delete('department_id');
        if (subDepartmentId) url.searchParams.set('sub_department_id', subDepartmentId);
        else url.searchParams.delete('sub_department_id');
        if (startDate) url.searchParams.set('start_date', startDate);
        else url.searchParams.delete('start_date');
        if (endDate) url.searchParams.set('end_date', endDate);
        else url.searchParams.delete('end_date');

        // Reload page with filters
        window.location.href = url.toString();
    }

    // Clear filters
    function clearFilters() {
        document.getElementById('departmentFilter').value = '';
        document.getElementById('subDepartmentFilter').value = '';
        document.getElementById('startDateFilter').value = '';
        document.getElementById('endDateFilter').value = '';
        window.location.href = window.location.pathname;
    }

    // Load sub-departments when department changes
    document.getElementById('departmentFilter').addEventListener('change', function() {
        const departmentId = this.value;
        const subDepartmentSelect = document.getElementById('subDepartmentFilter');

        // Clear sub-departments
        subDepartmentSelect.innerHTML = '<option value="">جميع الأقسام الفرعية</option>';

        if (departmentId) {
            // Fetch sub-departments
            fetch(`/dashboard/sub-departments?department_id=${departmentId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(subDept => {
                        const option = document.createElement('option');
                        option.value = subDept.id;
                        option.textContent = subDept.name;
                        subDepartmentSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading sub-departments:', error));
        }
    });

    // Initialize charts when page loads
    document.addEventListener('DOMContentLoaded', function() {
        initializeCharts();
    });
</script>
@endsection
