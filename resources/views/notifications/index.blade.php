@extends('layouts.layout')

@section('title', 'الإشعارات والتنبيهات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>الإشعارات</h4>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addNotificationModal">إضافة تنبيه</button>
    </div>
    <table id="notificationsTable" class="table table-striped table-bordered w-100">
        <thead>
            <tr>
                <th>العنوان</th>
                <th>الرسالة</th>
                <th>العميل</th>
                <th>المستخدم</th>
                <th>تاريخ التذكير</th>
                <th>الحالة</th>
                <th>العد التنازلي</th>
                <th>خيارات</th>
            </tr>
        </thead>
        <tbody>
            <!-- بيانات الإشعارات ستظهر هنا -->
        </tbody>
    </table>
</div>

<!-- Modal: إضافة تنبيه -->
<div class="modal fade" id="addNotificationModal" tabindex="-1" aria-labelledby="addNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNotificationModalLabel">إضافة تنبيه جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">العنوان</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الرسالة</label>
                        <textarea class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تاريخ التذكير</label>
                        <input type="datetime-local" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success w-100">حفظ</button>
                </form>
            </div>
        </div>
    </div>
</div>
<audio id="notificationSound" src="https://cdn.pixabay.com/audio/2022/07/26/audio_124bfa4c3e.mp3" preload="auto"></audio>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#notificationsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
        }
    });
    // مثال: تشغيل صوت عند وصول تنبيه جديد
    function playNotificationSound() {
        document.getElementById('notificationSound').play();
    }
    // مثال: تحديث عداد العد التنازلي
    setInterval(function() {
        // تحديث العد التنازلي لكل تنبيه
    }, 1000);
});
</script>
@endsection
