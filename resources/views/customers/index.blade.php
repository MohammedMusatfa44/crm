@extends('layouts.layout')

@section('title', 'إدارة العملاء')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>العملاء</h4>
        <div>
            <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#importModal">رفع إكسل</button>
            <button class="btn btn-outline-success me-2">استخراج البيانات</button>
            <button class="btn btn-outline-warning me-2">تغيير الحالة</button>
            <button class="btn btn-outline-info me-2">تخصيص للموظفين</button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCustomerModal">إضافة عميل</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            <table id="customersTable" class="table table-striped table-bordered w-100">
                <thead>
                    <tr>
                        <th>رقم الحساب</th>
                        <th>الاسم الكامل</th>
                        <th>رقم الجوال</th>
                        <th>البريد الإلكتروني</th>
                        <th>الحالة</th>
                        <th>الموظف المسؤول</th>
                        <th>القسم الفرعي</th>
                        <th>المدينة</th>
                        <th>خيارات</th>
                    </tr>
                    <tr>
                        <th><input type="text" class="form-control form-control-sm" placeholder="بحث..."></th>
                        <th><input type="text" class="form-control form-control-sm" placeholder="بحث..."></th>
                        <th><input type="text" class="form-control form-control-sm" placeholder="بحث..."></th>
                        <th><input type="text" class="form-control form-control-sm" placeholder="بحث..."></th>
                        <th><select class="form-select form-select-sm"><option>الكل</option></select></th>
                        <th><select class="form-select form-select-sm"><option>الكل</option></select></th>
                        <th><select class="form-select form-select-sm"><option>الكل</option></select></th>
                        <th><input type="text" class="form-control form-control-sm" placeholder="بحث..."></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- بيانات العملاء ستظهر هنا -->
                </tbody>
            </table>
        </div>
        <div class="col-md-3">
            <div class="card" id="customerSidebar" style="display:none;">
                <div class="card-header bg-info text-white">معلومات العميل</div>
                <div class="card-body">
                    <!-- تفاصيل العميل ستظهر هنا -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: إضافة عميل -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerModalLabel">إضافة عميل جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">الاسم الكامل</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">رقم الجوال</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success w-100">حفظ</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal: رفع إكسل -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">رفع ملف إكسل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <input type="file" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">رفع</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    var table = $('#customersTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
        }
    });
    // عند الضغط على صف، عرض تفاصيل العميل في الشريط الجانبي
    $('#customersTable tbody').on('click', 'tr', function () {
        $('#customerSidebar').show();
        // جلب بيانات العميل وعرضها هنا
    });
});
</script>
@endsection
