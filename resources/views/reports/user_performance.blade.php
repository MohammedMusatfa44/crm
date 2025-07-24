@extends('layouts.layout')

@section('title', 'تقرير أداء المستخدمين')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>تقرير أداء المستخدمين</h4>
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
            <label class="form-label">المستخدم</label>
            <select class="form-select">
                <option>الكل</option>
                <option>مستخدم 1</option>
                <option>مستخدم 2</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100">تصفية</button>
        </div>
    </form>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>المستخدم</th>
                <th>عدد العملاء المعالجين</th>
                <th>عدد التذاكر المغلقة</th>
                <th>عدد التنبيهات المرسلة</th>
                <th>آخر تسجيل دخول</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>مستخدم 1</td>
                <td>25</td>
                <td>10</td>
                <td>5</td>
                <td>2024-07-15 12:00</td>
            </tr>
            <tr>
                <td>مستخدم 2</td>
                <td>18</td>
                <td>7</td>
                <td>3</td>
                <td>2024-07-14 09:30</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
