@extends('layouts.layout')

@section('title', 'تقرير الأقسام')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>تقرير الأقسام</h4>
        <div>
            <a href="/reports/export-excel" class="btn btn-success me-2">تصدير Excel</a>
            <a href="/reports/export-pdf" class="btn btn-danger">تصدير PDF</a>
        </div>
    </div>
    <form class="row mb-4">
        <div class="col-md-3">
            <label class="form-label">القسم الرئيسي</label>
            <select class="form-select">
                <option>الكل</option>
                <option>قسم 1</option>
                <option>قسم 2</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">القسم الفرعي</label>
            <select class="form-select">
                <option>الكل</option>
                <option>فرعي 1</option>
                <option>فرعي 2</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">الحالة</label>
            <select class="form-select">
                <option>الكل</option>
                <option>نشط</option>
                <option>غير نشط</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100">تصفية</button>
        </div>
    </form>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>القسم الرئيسي</th>
                <th>القسم الفرعي</th>
                <th>عدد العملاء</th>
                <th>عدد الموظفين</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>قسم 1</td>
                <td>فرعي 1</td>
                <td>30</td>
                <td>5</td>
                <td>نشط</td>
            </tr>
            <tr>
                <td>قسم 2</td>
                <td>فرعي 2</td>
                <td>18</td>
                <td>3</td>
                <td>غير نشط</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
