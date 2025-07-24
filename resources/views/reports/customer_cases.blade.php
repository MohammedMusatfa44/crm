@extends('layouts.layout')

@section('title', 'تقرير حالات العملاء')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>تقرير حالات العملاء</h4>
        <div>
            <a href="/reports/export-excel" class="btn btn-success me-2">تصدير Excel</a>
            <a href="/reports/export-pdf" class="btn btn-danger">تصدير PDF</a>
        </div>
    </div>
    <form class="row mb-4">
        <div class="col-md-3">
            <label class="form-label">من تاريخ</label>
            <input type="date" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">إلى تاريخ</label>
            <input type="date" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">الحالة</label>
            <select class="form-select">
                <option>الكل</option>
                <option>جديد</option>
                <option>قيد المتابعة</option>
                <option>مغلق</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100">تصفية</button>
        </div>
    </form>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>رقم الحساب</th>
                <th>الاسم الكامل</th>
                <th>رقم الجوال</th>
                <th>الحالة</th>
                <th>الموظف المسؤول</th>
                <th>تاريخ الإضافة</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1001</td>
                <td>أحمد علي</td>
                <td>0500000000</td>
                <td>جديد</td>
                <td>مستخدم 1</td>
                <td>2024-07-15</td>
            </tr>
            <tr>
                <td>1002</td>
                <td>سارة محمد</td>
                <td>0555555555</td>
                <td>قيد المتابعة</td>
                <td>مستخدم 2</td>
                <td>2024-07-14</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
