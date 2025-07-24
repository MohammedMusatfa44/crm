@extends('layouts.layout')

@section('title', 'الدعم الفني')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>تذاكر الدعم الفني</h4>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTicketModal">إضافة تذكرة دعم</button>
    </div>
    <table id="supportTicketsTable" class="table table-striped table-bordered w-100">
        <thead>
            <tr>
                <th>الموضوع</th>
                <th>الوصف</th>
                <th>الأولوية</th>
                <th>الحالة</th>
                <th>المستخدم</th>
                <th>رد الإدارة</th>
                <th>تاريخ الرد</th>
                <th>خيارات</th>
            </tr>
        </thead>
        <tbody>
            <!-- بيانات التذاكر ستظهر هنا -->
        </tbody>
    </table>
</div>

<!-- Modal: إضافة تذكرة دعم -->
<div class="modal fade" id="addTicketModal" tabindex="-1" aria-labelledby="addTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTicketModalLabel">إضافة تذكرة دعم جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">الموضوع</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الأولوية</label>
                        <select class="form-select">
                            <option>منخفضة</option>
                            <option>متوسطة</option>
                            <option>مرتفعة</option>
                            <option>عاجلة</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">إرسال</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#supportTicketsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
        }
    });
});
</script>
@endsection
