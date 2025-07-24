@extends('layouts.layout')

@section('title', 'إدارة الصلاحيات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>الصلاحيات</h4>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addRoleModal">إضافة دور</button>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">جميع الصلاحيات</div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">إضافة عميل</li>
                        <li class="list-group-item">تعديل عميل</li>
                        <li class="list-group-item">حذف عميل</li>
                        <li class="list-group-item">عرض التقارير</li>
                        <li class="list-group-item">إدارة المستخدمين</li>
                        <li class="list-group-item">إدارة الأقسام</li>
                        <li class="list-group-item">إدارة الدعم الفني</li>
                        <li class="list-group-item">إدارة الصلاحيات</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">الأدوار</div>
                <div class="card-body">
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            مدير عام
                            <span><button class="btn btn-sm btn-info">تعديل</button></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            مدير
                            <span><button class="btn btn-sm btn-info">تعديل</button></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            موظف
                            <span><button class="btn btn-sm btn-info">تعديل</button></span>
                        </li>
                    </ul>
                    <form>
                        <div class="mb-3">
                            <label class="form-label">تعيين صلاحيات للمستخدم</label>
                            <select class="form-select mb-2">
                                <option>اختر مستخدم</option>
                            </select>
                            <select class="form-select mb-2">
                                <option>اختر صلاحية</option>
                            </select>
                            <button class="btn btn-primary w-100">تعيين</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: إضافة دور -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoleModalLabel">إضافة دور جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">اسم الدور</label>
                        <input type="text" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success w-100">حفظ</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
