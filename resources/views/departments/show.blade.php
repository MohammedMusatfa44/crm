@extends('layouts.layout')

@section('title', $department->name)

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
    .stat-card.sub-departments {
        background: linear-gradient(135deg, #4f5bd5 0%, #7f53ac 100%);
    }
    .stat-card.customers {
        background: linear-gradient(135deg, #4f5bd5 0%, #43cea2 100%);
    }
    .stat-card.active {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
    }
    .stat-card.closed {
        background: linear-gradient(135deg, #4f5bd5 0%, #ff6b6b 100%);
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
        text-decoration: none;
        transition: transform 0.2s;
    }
    .report-card:hover {
        transform: translateY(-2px);
        color: #fff;
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
    .sub-departments-section {
        margin-top: 2rem;
    }
    .sub-departments-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1.2rem;
        color: #222;
    }
    .sub-department-card {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.08);
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: transform 0.2s;
    }
    .sub-department-card:hover {
        transform: translateY(-2px);
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
                        <div class="dashboard-title">{{ $department->name }}</div>
                        <div class="dashboard-subtitle">تفاصيل القسم الرئيسي</div>
                    </div>
                    <div class="top-actions">
                        <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addSubDepartmentModal">
                            إضافة قسم فرعي
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4 g-4">
            <div class="col-md-3">
                <div class="stat-card sub-departments">
                    <div class="stat-label">الأقسام الفرعية</div>
                    <div class="stat-value">{{ $department->subDepartments->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card customers">
                    <div class="stat-label">إجمالي العملاء</div>
                    <div class="stat-value">{{ $department->customers->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card active">
                    <div class="stat-label">الحالات النشطة</div>
                    <div class="stat-value">{{ $department->customers->where('status', 'in_progress')->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card closed">
                    <div class="stat-label">الحالات المغلقة</div>
                    <div class="stat-value">{{ $department->customers->where('status', 'closed')->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Sub-Departments Section -->
        <div class="sub-departments-section">
            <div class="sub-departments-title">الأقسام الفرعية</div>
            <div class="row">
                @if($department->subDepartments->count() > 0)
                    @foreach($department->subDepartments as $sub)
                        <div class="col-md-4 mb-3">
                            <div class="sub-department-card">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="mb-0">{{ $sub->name }}</h5>
                                    <span class="badge bg-primary">{{ $sub->customers->count() }} عميل</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('sub-departments.show', $sub->id) }}" class="btn btn-sm btn-outline-primary">عرض</a>
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editSubDepartmentModal{{ $sub->id }}">تعديل</button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteSubDepartment({{ $sub->id }}, '{{ $sub->name }}')">حذف</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-folder-x" style="font-size: 3rem; opacity: 0.5;"></i>
                            <p class="mt-3">لا توجد أقسام فرعية لهذا القسم</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Reports Section -->
        <div class="reports-section">
            <div class="reports-title">التقارير</div>
            <div class="reports-grid">
                <a href="#" class="report-card blue">
                    <div class="report-label">تقرير العملاء</div>
                    <div class="report-value">PDF</div>
                </a>
                <a href="#" class="report-card purple">
                    <div class="report-label">تقرير الأداء</div>
                    <div class="report-value">Excel</div>
                </a>
                <a href="#" class="report-card green">
                    <div class="report-label">تقرير الحالات</div>
                    <div class="report-value">PDF</div>
                </a>
                <a href="#" class="report-card orange">
                    <div class="report-label">إحصائيات شهرية</div>
                    <div class="report-value">Chart</div>
                </a>
                <a href="#" class="report-card red">
                    <div class="report-label">تقرير المبيعات</div>
                    <div class="report-value">Excel</div>
                </a>
                <a href="#" class="report-card gray">
                    <div class="report-label">تقرير الموظفين</div>
                    <div class="report-value">PDF</div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal: إضافة قسم فرعي -->
<div class="modal fade" id="addSubDepartmentModal" tabindex="-1" aria-labelledby="addSubDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubDepartmentModalLabel">إضافة قسم فرعي لـ {{ $department->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('sub-departments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="department_id" value="{{ $department->id }}">
                    <div class="mb-3">
                        <label class="form-label">اسم القسم الفرعي</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">حفظ</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modals for Sub-Departments -->
@foreach($department->subDepartments as $sub)
<div class="modal fade" id="editSubDepartmentModal{{ $sub->id }}" tabindex="-1" aria-labelledby="editSubDepartmentModalLabel{{ $sub->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubDepartmentModalLabel{{ $sub->id }}">تعديل القسم الفرعي: {{ $sub->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('sub-departments.update', $sub->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="department_id" value="{{ $department->id }}">
                    <div class="mb-3">
                        <label class="form-label">اسم القسم الفرعي</label>
                        <input type="text" class="form-control" name="name" value="{{ $sub->name }}" required>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">تحديث</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Delete Form (Hidden) -->
<form id="deleteSubDepartmentForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteSubDepartment(id, name) {
    if (confirm('هل أنت متأكد من حذف القسم الفرعي "' + name + '"؟')) {
        const form = document.getElementById('deleteSubDepartmentForm');
        form.action = '{{ url("sub-departments") }}/' + id;
        form.submit();
    }
}
</script>
@endsection
