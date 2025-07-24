@extends('layouts.layout')

@section('title', 'لوحة التحكم')

@section('styles')
<style>
    .dashboard-bg {
        background: linear-gradient(135deg, #1976d2 0%, #42a5f5 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }
    .stat-card {
        background: linear-gradient(135deg, #2196f3 0%, #64b5f6 100%);
        color: #fff;
        border: none;
        border-radius: 1rem;
        box-shadow: 0 4px 24px rgba(33,150,243,0.12);
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-6px) scale(1.03);
        box-shadow: 0 8px 32px rgba(33,150,243,0.18);
    }
    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.2;
        position: absolute;
        top: 1rem;
        left: 1rem;
    }
    .stat-value {
        font-size: 2.5rem;
        font-weight: bold;
        animation: countUp 1.2s ease;
    }
    @keyframes countUp {
        0% { opacity: 0; transform: scale(0.8); }
        100% { opacity: 1; transform: scale(1); }
    }
    .modern-card {
        border-radius: 1rem;
        box-shadow: 0 2px 16px rgba(33,150,243,0.08);
        border: none;
    }
    .modern-card .card-header {
        background: linear-gradient(90deg, #1976d2 0%, #42a5f5 100%);
        color: #fff;
        border-radius: 1rem 1rem 0 0;
    }
</style>
@endsection

@section('content')
<div class="dashboard-bg">
    <div class="container-fluid">
        <div class="row mb-4 g-4">
            <div class="col-md-3">
                <div class="card stat-card text-center shadow position-relative">
                    <span class="stat-icon"><i class="bi bi-people"></i></span>
                    <div class="card-body">
                        <h6 class="card-title">عدد العملاء</h6>
                        <div class="stat-value" id="customerCount">{{ $customerCount ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card text-center shadow position-relative">
                    <span class="stat-icon"><i class="bi bi-person-badge"></i></span>
                    <div class="card-body">
                        <h6 class="card-title">عدد المستخدمين</h6>
                        <div class="stat-value" id="userCount">{{ $userCount ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card text-center shadow position-relative">
                    <span class="stat-icon"><i class="bi bi-diagram-3"></i></span>
                    <div class="card-body">
                        <h6 class="card-title">عدد الأقسام</h6>
                        <div class="stat-value" id="departmentCount">{{ $departmentCount ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card text-center shadow position-relative">
                    <span class="stat-icon"><i class="bi bi-bell"></i></span>
                    <div class="card-body">
                        <h6 class="card-title">عدد التنبيهات</h6>
                        <div class="stat-value" id="alertCount">{{ $alertCount ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4 g-4">
            <div class="col-md-8">
                <div class="card modern-card shadow">
                    <div class="card-header">التقارير والإحصائيات</div>
                    <div class="card-body">
                        <canvas id="mainChart" height="120"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card modern-card shadow mb-3">
                    <div class="card-header">فلاتر التاريخ والتصنيفات</div>
                    <div class="card-body">
                        <form>
                            <div class="mb-2">
                                <label class="form-label">من تاريخ</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">إلى تاريخ</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">القسم الفرعي</label>
                                <select class="form-select">
                                    <option>الكل</option>
                                    <option>القسم 1</option>
                                    <option>القسم 2</option>
                                </select>
                            </div>
                            <button class="btn btn-primary w-100">تصفية</button>
                        </form>
                    </div>
                </div>
                <div class="card modern-card shadow">
                    <div class="card-header bg-info text-white">بحث عام</div>
                    <div class="card-body">
                        <input type="text" class="form-control" placeholder="ابحث هنا...">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-end">
                <a href="/departments/create" class="btn btn-success"><i class="bi bi-plus-circle"></i> إضافة قسم جديد</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // Animated counter
    function animateValue(id, start, end, duration) {
        let range = end - start;
        let current = start;
        let increment = end > start ? 1 : -1;
        let stepTime = Math.abs(Math.floor(duration / range));
        const obj = document.getElementById(id);
        if (!obj) return;
        let timer = setInterval(function() {
            current += increment;
            obj.textContent = current;
            if (current == end) {
                clearInterval(timer);
            }
        }, stepTime);
    }
    document.addEventListener('DOMContentLoaded', function() {
        animateValue('customerCount', 0, parseInt(document.getElementById('customerCount').textContent), 1000);
        animateValue('userCount', 0, parseInt(document.getElementById('userCount').textContent), 1000);
        animateValue('departmentCount', 0, parseInt(document.getElementById('departmentCount').textContent), 1000);
        animateValue('alertCount', 0, parseInt(document.getElementById('alertCount').textContent), 1000);
    });
    // Chart.js example
    const ctx = document.getElementById('mainChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
            datasets: [{
                label: 'العملاء الجدد',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(33, 150, 243, 0.7)',
                    'rgba(66, 165, 245, 0.7)',
                    'rgba(33, 150, 243, 0.5)',
                    'rgba(66, 165, 245, 0.5)',
                    'rgba(33, 150, 243, 0.3)',
                    'rgba(66, 165, 245, 0.3)'
                ],
                borderColor: 'rgba(33, 150, 243, 1)',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
