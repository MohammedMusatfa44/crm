@extends('layouts.layout')

@section('title', 'إدارة الأقسام')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>الأقسام</h4>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">إضافة قسم</button>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3" v-for="section in [1,2,3]">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>القسم الرئيسي {{ section }}</span>
                    <button class="btn btn-sm btn-light" data-bs-toggle="collapse" :data-bs-target="'#subSections'+section">عرض الأقسام الفرعية</button>
                </div>
                <div class="card-body">
                    <div class="mb-2">عدد العملاء: <span class="badge bg-info">10</span></div>
                    <div class="mb-2">عدد الموظفين: <span class="badge bg-secondary">3</span></div>
                    <button class="btn btn-warning btn-sm me-2">تعديل</button>
                    <button class="btn btn-danger btn-sm">حذف</button>
                </div>
                <div class="collapse" :id="'subSections'+section">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">قسم فرعي 1</li>
                        <li class="list-group-item">قسم فرعي 2</li>
                    </ul>
                </div>
            </div>
        </div>
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
                <form>
                    <div class="mb-3">
                        <label class="form-label">اسم القسم</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">حفظ</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
