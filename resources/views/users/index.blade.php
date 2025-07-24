@extends('layouts.layout')

@section('title', 'إدارة المستخدمين')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>المستخدمون</h4>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">إضافة مستخدم</button>
    </div>
    <table id="usersTable" class="table table-striped table-bordered w-100">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>البريد الإلكتروني</th>
                <th>الدور</th>
                <th>الحالة</th>
                <th>رقم الجوال</th>
                <th>الجلسات النشطة</th>
                <th>IP</th>
                <th>MAC</th>
                <th>خيارات</th>
            </tr>
        </thead>
        <tbody>
            <!-- بيانات المستخدمين ستظهر هنا -->
        </tbody>
    </table>

    <!-- Modal: إضافة مستخدم -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">إضافة مستخدم جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">الاسم</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الدور</label>
                            <select class="form-select">
                                <option>مدير عام</option>
                                <option>مدير</option>
                                <option>موظف</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">كلمة المرور</label>
                            <input type="password" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success w-100">حفظ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#usersTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
        }
    });
});
</script>
@endsection
