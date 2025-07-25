@extends('layouts.layout')

@section('title', 'إدارة الأقسام')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>الأقسام</h4>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">إضافة قسم</button>
    </div>
    <div class="row">
        @foreach($departments as $department)
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span>{{ $department->name }}</span>
                        <div>
                            <button class="btn btn-sm btn-light" data-bs-toggle="collapse" data-bs-target="#subSections{{ $department->id }}">عرض الأقسام الفرعية</button>
                            <button class="btn btn-sm btn-success ms-2" data-bs-toggle="modal" data-bs-target="#addSubDepartmentModal{{ $department->id }}">إضافة قسم فرعي</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">عدد العملاء: <span class="badge bg-info">{{ $department->customers_count ?? 0 }}</span></div>
                        <div class="mb-2">عدد الموظفين: <span class="badge bg-secondary">{{ $department->employees_count ?? 0 }}</span></div>
                        <button class="btn btn-warning btn-sm me-2">تعديل</button>
                        <button class="btn btn-danger btn-sm">حذف</button>
                    </div>
                    <div class="collapse" id="subSections{{ $department->id }}">
                        <ul class="list-group list-group-flush">
                            @foreach($department->subDepartments ?? [] as $sub)
                                <li class="list-group-item">{{ $sub->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal: إضافة قسم -->
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
@foreach($departments as $department)
<div class="modal fade" id="addSubDepartmentModal{{ $department->id }}" tabindex="-1" aria-labelledby="addSubDepartmentModalLabel{{ $department->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubDepartmentModalLabel{{ $department->id }}">إضافة قسم فرعي لـ {{ $department->name }}</h5>
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
@endforeach
@endsection
