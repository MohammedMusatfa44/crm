@extends('layouts.layout')

@section('title', 'الإشعارات والتنبيهات')

@section('styles')
<style>
    .dashboard-bg {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 2rem 0;
    }
    .dashboard-title {
        font-size: 2.1rem;
        font-weight: 700;
        color: #222;
        margin-bottom: 0.2rem;
    }
    .dashboard-subtitle {
        color: #888;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }
    .top-actions {
        display: flex;
        gap: 0.7rem;
        justify-content: flex-end;
        margin-bottom: 1.2rem;
    }
    .top-actions .btn {
        border-radius: 1.2rem;
        font-weight: 500;
        font-size: 1rem;
        padding: 0.5rem 1.2rem;
    }
    .top-actions .btn-add {
        background: #0b58ca;
        color: #fff;
        border: none;
    }
    .top-actions .btn-add:hover {
        background: #1976d2;
    }
    .header-card {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.07);
        padding: 1.5rem 2rem 1.2rem 2rem;
        margin-bottom: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
    }
    .table-container {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.07);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .table-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e9ecef;
    }
    .table-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #222;
        margin: 0;
    }
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .modern-table thead th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
        padding: 1rem;
        border: none;
        border-bottom: 2px solid #dee2e6;
        text-align: right;
    }
    .modern-table tbody td {
        padding: 1rem;
        border: none;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }
    .modern-table tbody tr:hover {
        background: #f8f9fa;
    }
    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status-read {
        background: #d4edda;
        color: #155724;
    }
    .status-unread {
        background: #fff3cd;
        color: #856404;
    }
    .status-triggered {
        background: #f8d7da;
        color: #721c24;
    }
    .btn-action {
        padding: 0.3rem 0.8rem;
        border-radius: 0.5rem;
        font-size: 0.8rem;
        font-weight: 500;
        border: none;
        margin: 0 0.2rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-view {
        background: #e3f0ff;
        color: #0b58ca;
    }
    .btn-view:hover {
        background: #0b58ca;
        color: #fff;
    }
    .btn-edit {
        background: #fff3cd;
        color: #856404;
    }
    .btn-edit:hover {
        background: #856404;
        color: #fff;
    }
    .btn-delete {
        background: #f8d7da;
        color: #721c24;
    }
    .btn-delete:hover {
        background: #721c24;
        color: #fff;
    }
    .countdown {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 500;
    }
    .countdown.urgent {
        color: #dc3545;
        font-weight: 600;
    }
    .countdown.warning {
        color: #fd7e14;
        font-weight: 600;
    }
    .role-unknown {
        background: #6c757d;
        color: white;
    }
    .permissions-list {
        font-size: 0.8rem;
        color: #6c757d;
        max-width: 300px;
        word-wrap: break-word;
        line-height: 1.3;
    }
    .permissions-list.empty {
        font-style: italic;
        color: #adb5bd;
    }

    /* Side Notification Panel */
    .side-notification-panel {
        position: fixed;
        top: 0;
        right: -400px;
        width: 380px;
        height: 100vh;
        background: #fff;
        box-shadow: -5px 0 15px rgba(0,0,0,0.1);
        z-index: 9999;
        transition: right 0.3s ease-in-out;
        border-left: 4px solid #0b58ca;
    }

    .side-notification-panel.show {
        right: 0;
    }

    .side-notification-header {
        background: linear-gradient(135deg, #0b58ca 0%, #1976d2 100%);
        color: white;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .side-notification-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
    }

    .side-notification-close {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background 0.2s;
    }

    .side-notification-close:hover {
        background: rgba(255,255,255,0.2);
    }

    .side-notification-content {
        padding: 1.5rem;
        max-height: calc(100vh - 80px);
        overflow-y: auto;
    }

    .side-notification-item {
        background: #f8f9fa;
        border-radius: 0.8rem;
        padding: 1rem;
        margin-bottom: 1rem;
        border-left: 4px solid #ffc107;
        transition: all 0.2s;
    }

    .side-notification-item:hover {
        background: #e9ecef;
        transform: translateX(-5px);
    }

    .side-notification-item.unread {
        border-left-color: #dc3545;
        background: #fff5f5;
    }

    .side-notification-item-title {
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .side-notification-item-message {
        color: #6c757d;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .side-notification-item-time {
        font-size: 0.8rem;
        color: #adb5bd;
        margin-bottom: 0.5rem;
    }

    .side-notification-item-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .side-notification-btn {
        padding: 0.3rem 0.8rem;
        border: none;
        border-radius: 0.4rem;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .side-notification-btn-primary {
        background: #0b58ca;
        color: white;
    }

    .side-notification-btn-primary:hover {
        background: #1976d2;
    }

    .side-notification-btn-secondary {
        background: #6c757d;
        color: white;
    }

    .side-notification-btn-secondary:hover {
        background: #5a6268;
    }

    .side-notification-empty {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }

    .side-notification-empty i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Sound Control */
    .sound-control {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        background: #fff;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .sound-control:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(0,0,0,0.2);
    }

    .sound-control.muted {
        background: #dc3545;
        color: white;
    }

    .sound-control i {
        font-size: 1.2rem;
    }
</style>
@endsection

@section('content')
@can('notifications.view')
<div class="dashboard-bg">
    <div class="container-fluid">
        <!-- Header Card -->
        <div class="header-card">
            <div>
                <div class="dashboard-title">الإشعارات والتنبيهات</div>
                <div class="dashboard-subtitle">إدارة التنبيهات والتذكيرات</div>
            </div>
            <div class="top-actions">
                @can('notifications.create')
                <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addNotificationModal">
                    إضافة تنبيه جديد
                </button>
                @endcan
                <button class="btn btn-secondary" id="soundToggle" onclick="toggleSound()" title="تبديل الصوت">
                    <i class="fas fa-volume-up"></i>
                </button>
            </div>
        </div>

        <!-- Table Container -->
        <div class="table-container">
            <div class="table-header">
                <h3 class="table-title">قائمة التنبيهات</h3>
            </div>

            <table id="notificationsTable" class="modern-table">
                <thead>
                    <tr>
                        <th>العنوان</th>
                        <th>الرسالة</th>
                        <th>العميل</th>
                        <th>تاريخ التذكير</th>
                        <th>العد التنازلي</th>
                        <th>الحالة</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notifications as $notification)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $notification->title }}</div>
                        </td>
                        <td>{{ Str::limit($notification->message, 50) }}</td>
                        <td>
                            @if($notification->customer)
                                <span class="badge bg-primary">{{ $notification->customer->full_name }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($notification->remind_at)
                                {{ $notification->remind_at->format('d/m/Y H:i') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($notification->remind_at)
                                <div class="countdown" id="countdown-{{ $notification->id }}">
                                    @php
                                        $now = now();
                                        $remindAt = $notification->remind_at;
                                        $diff = $now->diff($remindAt);
                                        $isPast = $now->gt($remindAt);
                                    @endphp
                                    @if($isPast)
                                        <span class="urgent">منذ {{ $diff->days }} يوم {{ $diff->h }} ساعة {{ $diff->i }} دقيقة</span>
                                    @else
                                        <span class="warning">بعد {{ $diff->days }} يوم {{ $diff->h }} ساعة {{ $diff->i }} دقيقة</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($notification->is_triggered)
                                <span class="status-badge status-triggered">تم التنبيه</span>
                            @elseif($notification->is_read)
                                <span class="status-badge status-read">مقروء</span>
                            @else
                                <span class="status-badge status-unread">غير مقروء</span>
                            @endif
                        </td>
                        <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <button class="btn-action btn-view" onclick="viewNotification({{ $notification->id }})" title="عرض">
                                عرض
                            </button>
                            @if(!$notification->is_read)
                            <button class="btn-action btn-edit" onclick="markAsRead({{ $notification->id }})" title="تعليم كمقروء">
                                تعليم كمقروء
                            </button>
                            @endif
                            @can('notifications.delete')
                            <button class="btn-action btn-delete" onclick="deleteNotification({{ $notification->id }})" title="حذف">
                                حذف
                            </button>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@can('notifications.create')
<!-- Add Notification Modal -->
<div class="modal fade" id="addNotificationModal" tabindex="-1" aria-labelledby="addNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNotificationModalLabel">إضافة تنبيه جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form id="addNotificationForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">العنوان *</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">العميل (اختياري)</label>
                                <select class="form-select" name="customer_id">
                                    <option value="">اختر العميل</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الرسالة *</label>
                        <textarea class="form-control" name="message" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تاريخ التذكير (اختياري)</label>
                        <input type="datetime-local" class="form-control" name="remind_at">
                        <small class="text-muted">اتركه فارغاً إذا كنت تريد تنبيه فوري</small>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ التنبيه</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan

<!-- View Notification Modal -->
<div class="modal fade" id="viewNotificationModal" tabindex="-1" aria-labelledby="viewNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewNotificationModalLabel">تفاصيل التنبيه</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body" id="notificationDetails">
                <!-- Notification details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Real-time Notification Alert Modal -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="alertModalLabel">
                    <i class="fas fa-bell me-2"></i>تنبيه مهم!
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <div id="alertContent">
                    <!-- Alert content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <button type="button" class="btn btn-primary" onclick="markAlertAsRead()">تعليم كمقروء</button>
            </div>
        </div>
    </div>
</div>

<!-- Notification Sound -->
<audio id="notificationSound" preload="auto">
    <source src="https://cdn.pixabay.com/audio/2022/07/26/audio_124bfa4c3e.mp3" type="audio/mpeg">
</audio>

<!-- Desktop Notification Permission -->
<div id="notificationPermission" style="display: none;">
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <strong>تنبيه!</strong> للاستفادة من التنبيهات الصوتية، يرجى السماح بالتنبيهات في المتصفح.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>

<!-- Sound Control Button -->
<div class="sound-control" id="soundControl" onclick="toggleSound()" title="تبديل الصوت">
    <i class="fas fa-volume-up"></i>
</div>

<!-- Side Notification Panel -->
<div class="side-notification-panel" id="sideNotificationPanel">
    <div class="side-notification-header">
        <h5 class="side-notification-title">
            <i class="fas fa-bell me-2"></i>التنبيهات
        </h5>
        <button class="side-notification-close" onclick="closeSideNotificationPanel()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="side-notification-content" id="sideNotificationContent">
        <!-- Notifications will be loaded here -->
    </div>
</div>

@else
<div class="container-fluid">
    <div class="alert alert-warning text-center">
        <h4>ليس لديك صلاحية لعرض الإشعارات</h4>
        <p>يرجى التواصل مع مدير النظام للحصول على الصلاحيات المطلوبة.</p>
    </div>
</div>
@endcan
@endsection

@section('scripts')
@can('notifications.view')
<script>
// Global variables for notification system
let currentAlertNotificationId = null;
let notificationCheckInterval = null;
let soundEnabled = true;

$(document).ready(function() {
    // Initialize DataTable
    $('#notificationsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
        },
        order: [[6, 'desc']], // Sort by creation date descending
        pageLength: 25,
        responsive: true,
        columnDefs: [
            {
                targets: [3, 4, 5, 6, 7], // Customer, Remind Date, Countdown, Status, Created Date, Actions
                orderable: false
            }
        ],
        drawCallback: function() {
            // Re-initialize any custom elements after table redraw
            console.log('DataTable initialized successfully');
        }
    });

    // Request notification permission
    requestNotificationPermission();

    // Start checking for notifications every 30 seconds
    startNotificationChecking();

    // Add Notification Form Submission
    $('#addNotificationForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: '{{ route("notifications.store") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم بنجاح!',
                        text: response.message,
                        confirmButtonText: 'حسناً'
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                var message = 'حدث خطأ أثناء إضافة التنبيه';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ!',
                    text: message,
                    confirmButtonText: 'حسناً'
                });
            }
        });
    });

    // Update countdown timers every minute
    setInterval(function() {
        updateCountdowns();
    }, 60000); // Every minute

    // Clean up interval when page is unloaded
    $(window).on('beforeunload', function() {
        if (notificationCheckInterval) {
            clearInterval(notificationCheckInterval);
        }
    });

    // Load sound preference on page load
    loadSoundPreference();
});

// Request notification permission
function requestNotificationPermission() {
    if ('Notification' in window) {
        if (Notification.permission === 'default') {
            Notification.requestPermission().then(function(permission) {
                if (permission === 'granted') {
                    console.log('Notification permission granted');
                } else {
                    $('#notificationPermission').show();
                }
            });
        } else if (Notification.permission === 'denied') {
            $('#notificationPermission').show();
        }
    }
}

// Start checking for notifications
function startNotificationChecking() {
    // Check immediately
    checkForTriggeredNotifications();

    // Then check every 30 seconds
    notificationCheckInterval = setInterval(function() {
        checkForTriggeredNotifications();
    }, 10000); // 30 seconds
}

// Check for triggered notifications
function checkForTriggeredNotifications() {
    $.ajax({
        url: '/notifications/triggered',
        type: 'GET',
        success: function(response) {
            if (response.success && response.count > 0) {
                response.notifications.forEach(function(notification) {
                    showNotificationAlert(notification);
                });
            }
        },
        error: function(xhr) {
            console.log('Error checking notifications:', xhr);
        }
    });
}

// Show notification alert
function showNotificationAlert(notification) {
    // Play sound
    playNotificationSound();

    // Show desktop notification
    showDesktopNotification(notification);

    // Show side notification panel
    showSideNotificationPanel(notification);
}

// Show side notification panel
function showSideNotificationPanel(notification) {
    // Add notification to the side panel
    addNotificationToSidePanel(notification);

    // Show the panel
    $('#sideNotificationPanel').addClass('show');

    // Auto-hide after 10 seconds if not dismissed
    setTimeout(function() {
        if ($('#sideNotificationPanel').hasClass('show')) {
            closeSideNotificationPanel();
        }
    }, 10000);
}

// Add notification to side panel
function addNotificationToSidePanel(notification) {
    const notificationHtml = `
        <div class="side-notification-item unread" data-notification-id="${notification.id}">
            <div class="side-notification-item-title">${notification.title}</div>
            <div class="side-notification-item-message">${notification.message}</div>
            <div class="side-notification-item-time">
                <i class="fas fa-clock me-1"></i>
                ${new Date(notification.remind_at).toLocaleString('ar-SA', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    timeZone: 'Asia/Riyadh'
                })}
            </div>
            <div class="side-notification-item-actions">
                <button class="side-notification-btn side-notification-btn-primary" onclick="markNotificationAsRead(${notification.id})">
                    <i class="fas fa-check me-1"></i>تعليم كمقروء
                </button>
                <button class="side-notification-btn side-notification-btn-secondary" onclick="dismissNotification(${notification.id})">
                    <i class="fas fa-times me-1"></i>إغلاق
                </button>
            </div>
        </div>
    `;

    // Add to the top of the panel
    $('#sideNotificationContent').prepend(notificationHtml);

    // Update notification count in header
    updateNotificationCount();
}

// Close side notification panel
function closeSideNotificationPanel() {
    $('#sideNotificationPanel').removeClass('show');

    // Mark all visible notifications as read
    $('.side-notification-item.unread').each(function() {
        const notificationId = $(this).data('notification-id');
        markNotificationAsRead(notificationId);
    });
}

// Mark notification as read
function markNotificationAsRead(notificationId) {
    $.ajax({
        url: '/notifications/' + notificationId + '/mark-as-read',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                // Remove the notification from side panel
                $(`.side-notification-item[data-notification-id="${notificationId}"]`).remove();

                // Update notification count
                updateNotificationCount();

                // If no more notifications, close the panel
                if ($('.side-notification-item').length === 0) {
                    closeSideNotificationPanel();
                }
            }
        },
        error: function(xhr) {
            console.log('Error marking notification as read:', xhr);
        }
    });
}

// Dismiss notification (mark as read and remove)
function dismissNotification(notificationId) {
    markNotificationAsRead(notificationId);
}

// Update notification count in header
function updateNotificationCount() {
    const count = $('.side-notification-item').length;
    if (count === 0) {
        $('#sideNotificationContent').html(`
            <div class="side-notification-empty">
                <i class="fas fa-bell-slash"></i>
                <p>لا توجد تنبيهات جديدة</p>
            </div>
        `);
    }
}

// Play notification sound
function playNotificationSound() {
    if (soundEnabled) {
        const audio = document.getElementById('notificationSound');
        audio.currentTime = 0;
        audio.play().catch(function(error) {
            console.log('Error playing sound:', error);
        });
    }
}

// Show desktop notification
function showDesktopNotification(notification) {
    if ('Notification' in window && Notification.permission === 'granted') {
        const desktopNotification = new Notification('تنبيه مهم!', {
            body: notification.title + '\n' + notification.message,
            icon: '/favicon.ico',
            badge: '/favicon.ico',
            tag: 'notification-' + notification.id,
            requireInteraction: true
        });

        // Handle notification click
        desktopNotification.onclick = function() {
            window.focus();
            this.close();
        };

        // Auto close after 10 seconds
        setTimeout(function() {
            desktopNotification.close();
        }, 10000);
    }
}

// Show modal alert
function showModalAlert(notification) {
    currentAlertNotificationId = notification.id;

    // Format the reminder time in local timezone
    const remindAt = new Date(notification.remind_at);
    const localTime = remindAt.toLocaleString('ar-SA', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        timeZone: 'Asia/Riyadh'
    });

    // Calculate time difference
    const now = new Date();
    const diffMs = remindAt - now;
    const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
    const diffHours = Math.floor((diffMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

    let timeStatus = '';
    if (diffMs < 0) {
        timeStatus = `منذ ${Math.abs(diffDays)} يوم ${Math.abs(diffHours)} ساعة ${Math.abs(diffMinutes)} دقيقة`;
    } else {
        timeStatus = `بعد ${diffDays} يوم ${diffHours} ساعة ${diffMinutes} دقيقة`;
    }

    const alertContent = `
        <div class="text-center">
            <div class="mb-3">
                <i class="fas fa-bell fa-3x text-warning"></i>
            </div>
            <h5 class="mb-3">${notification.title}</h5>
            <p class="mb-3">${notification.message}</p>
            ${notification.customer ? `<p class="text-muted"><strong>العميل:</strong> ${notification.customer.full_name}</p>` : ''}
            <p class="text-muted"><strong>وقت التذكير:</strong> ${localTime}</p>
            <p class="text-muted"><strong>الوقت المتبقي:</strong> ${timeStatus}</p>
        </div>
    `;

    $('#alertContent').html(alertContent);
    $('#alertModal').modal('show');
}

// Mark alert as read
function markAlertAsRead() {
    if (currentAlertNotificationId) {
        markNotificationAsRead(currentAlertNotificationId);
        $('#alertModal').modal('hide');
        currentAlertNotificationId = null;
    }
}

// Toggle sound
function toggleSound() {
    soundEnabled = !soundEnabled;
    const icon = soundEnabled ? 'fas fa-volume-up' : 'fas fa-volume-mute';
    $('#soundToggle i').attr('class', icon);

    // Save preference to localStorage
    localStorage.setItem('notificationSoundEnabled', soundEnabled);
}

// View Notification
function viewNotification(id) {
    // For now, just show a simple alert. You can enhance this to load full details
    Swal.fire({
        title: 'تفاصيل التنبيه',
        text: 'سيتم إضافة عرض تفاصيل كاملة للتنبيه قريباً',
        icon: 'info',
        confirmButtonText: 'حسناً'
    });
}

// Mark as Read (for table actions)
function markAsRead(id) {
    Swal.fire({
        title: 'تأكيد',
        text: 'هل تريد تعليم هذا التنبيه كمقروء؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'نعم',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            markNotificationAsRead(id);
        }
    });
}

// Delete Notification
function deleteNotification(id) {
    Swal.fire({
        title: 'تأكيد الحذف',
        text: 'هل أنت متأكد من حذف هذا التنبيه؟',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء',
        confirmButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/notifications/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم بنجاح!',
                            text: response.message,
                            confirmButtonText: 'حسناً'
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    var message = 'حدث خطأ أثناء حذف التنبيه';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: message,
                        confirmButtonText: 'حسناً'
                    });
                }
            });
        }
    });
}

// Update countdown timers
function updateCountdowns() {
    $('.countdown').each(function() {
        const countdownElement = $(this);
        const notificationId = countdownElement.attr('id').replace('countdown-', '');

        // Get the reminder time from the data attribute or calculate it
        const remindAtText = countdownElement.find('span').text();

        // For now, we'll update the display every minute
        // You can enhance this to calculate the exact time difference
        console.log('Updating countdown for notification:', notificationId);
    });
}
</script>
@endcan
@endsection
