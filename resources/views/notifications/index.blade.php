@extends('layouts.layout')

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
        flex-wrap: wrap;
    }
    .top-actions .btn {
        border-radius: 1.2rem;
        font-weight: 500;
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
    .modern-table {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.08);
        overflow: hidden;
    }
    .table-header {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
        color: #fff;
        padding: 1rem 1.5rem;
        font-weight: 600;
        font-size: 1.1rem;
    }
    .table-responsive {
        border-radius: 1.2rem;
    }
    .table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    .table th {
        background: #f8f9fa;
        border: none;
        font-weight: 600;
        color: #555;
        padding: 1.2rem 1rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e9ecef;
    }
    .table td {
        border: none;
        border-bottom: 1px solid #f0f0f0;
        padding: 1.2rem 1rem;
        vertical-align: middle;
        font-size: 0.95rem;
        color: #333;
    }
    .table tbody tr {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .table tbody tr:hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .table tbody tr:nth-child(even) {
        background: #fafbfc;
    }
    .table tbody tr:nth-child(even):hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
        border-radius: 1rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .btn-action {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
        margin: 0.3rem;
        border-radius: 0.8rem;
        width: 100%;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif



                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-0">التنبيهات الشخصية</h5>
                            <small class="text-muted">تنبيهاتك الشخصية - لا يمكن للآخرين رؤيتها</small>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNotificationModal">
                                <i class="bi bi-plus-lg"></i> إضافة تنبيه جديد
                            </button>

                        </div>
                    </div>

                    <div class="modern-table">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>العنوان</th>
                                        <th>الرسالة</th>
                                        <th>العميل</th>
                                        <th>وقت التذكير</th>
                                        <th>الوقت المتبقي</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($notifications->isEmpty())
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-bell-slash" style="font-size: 2rem;"></i>
                                                <p class="mt-2 mb-0">لا توجد تنبيهات شخصية</p>
                                                <small>أنشئ تنبيه جديد لتبدأ في استخدام النظام</small>
                                            </div>
                                        </td>
                                    </tr>
                                    @else
                                    @foreach($notifications as $notification)
                                    <tr>
                                        <td><strong>{{ $notification->title }}</strong></td>
                                        <td>{{ $notification->message }}</td>
                                        <td>{{ $notification->customer ? $notification->customer->full_name : 'غير محدد' }}</td>
                                        <td>{{ $notification->remind_at ? $notification->remind_at->format('Y-m-d H:i') : 'غير محدد' }}</td>
                                        <td>
                                            @if($notification->remind_at)
                                                <span class="countdown-timer" data-remind-at="{{ $notification->remind_at->format('Y-m-d H:i:s') }}">
                                                    @if($notification->remind_at->isPast())
                                                        <span class="text-danger">انتهى الوقت</span>
                                                    @else
                                                        <span class="text-warning">جاري العد...</span>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($notification->is_read)
                                                <span class="badge bg-success status-badge">مقروء</span>
                                            @elseif($notification->is_triggered)
                                                <span class="badge bg-warning status-badge">تم التنبيه</span>
                                            @else
                                                <span class="badge bg-info status-badge">في الانتظار</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary btn-action" onclick="markAsRead({{ $notification->id }})" title="تحديد كمقروء">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning btn-action" onclick="triggerNotification({{ $notification->id }}, event)" title="تشغيل التنبيه">
                                                <i class="bi bi-bell"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger btn-action" onclick="deleteNotification({{ $notification->id }})" title="حذف">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Notification Modal -->
<div class="modal fade" id="addNotificationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة تنبيه جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/notifications" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">العنوان</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الرسالة</label>
                        <textarea class="form-control" name="message" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">العميل (اختياري)</label>
                        <select class="form-control" name="customer_id">
                            <option value="">اختر العميل</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">وقت التذكير</label>
                        <input type="datetime-local" class="form-control" name="remind_at" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إضافة</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Server timezone information
const SERVER_TIMEZONE = 'Asia/Riyadh';
const SERVER_TIMEZONE_OFFSET = 3; // Riyadh is UTC+3

// Delete notification
function deleteNotification(id) {
    if (confirm('هل أنت متأكد من حذف هذا التنبيه؟')) {
        fetch(`/notifications/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(() => {
            location.reload();
        });
    }
}

// Mark notification as read
function markAsRead(id) {
    fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('تمت العملية', 'تم تحديث حالة التنبيه بنجاح.');
            location.reload(); // Reload to update the badge
        } else {
            showAlert('خطأ', 'حدث خطأ أثناء تحديث حالة التنبيه.');
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
        showAlert('خطأ', 'حدث خطأ أثناء تحديث حالة التنبيه.');
    });
}

// Trigger notification manually
function triggerNotification(id, event = null) {
    // Prevent multiple triggers of the same notification
    let button = null;
    if (event && event.target) {
        button = event.target.closest('button');
        if (button && button.disabled) {
            return;
        }
        // Disable the button temporarily
        if (button) {
            button.disabled = true;
        }
    }

    // Add a flag to prevent multiple calls
    if (window.triggeringNotification && window.triggeringNotification === id) {
        return;
    }
    window.triggeringNotification = id;

    fetch(`/notifications/${id}/trigger`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Get notification details from the table row using a more specific selector
            const row = event ? event.target.closest('tr') : document.querySelector(`tr:has(button[onclick*="triggerNotification(${id})"])`);
            if (!row) {
                console.error('Could not find notification row');
                return;
            }

            const title = row.querySelector('td:nth-child(1)').textContent.trim();
            const message = row.querySelector('td:nth-child(2)').textContent.trim();
            const customer = row.querySelector('td:nth-child(3)').textContent.trim();

            // Show notification with actual content
            showNotificationAlert(title, message, customer);

        } else {
            showAlert('خطأ', 'حدث خطأ أثناء تشغيل التنبيه.');
            if (button) {
                button.disabled = false; // Re-enable on error
            }
        }
    })
    .catch(error => {
        console.error('Error triggering notification:', error);
        showAlert('خطأ', 'حدث خطأ أثناء تشغيل التنبيه.');
        if (button) {
            button.disabled = false; // Re-enable on error
        }
    })
    .finally(() => {
        // Clear the flag after a short delay
        setTimeout(() => {
            window.triggeringNotification = null;
        }, 1000);
    });
}

// Countdown timer function
function startCountdown(element) {
    console.log('Starting countdown for element:', element);
    console.log('Remind at:', element.dataset.remindAt);

    // Parse the remind time - treat server time as local time
    const remindAtStr = element.dataset.remindAt;
    const remindAt = new Date(remindAtStr);
    const now = new Date();
    const distance = remindAt.getTime() - now.getTime();

    console.log('Remind time:', remindAt.toLocaleString());
    console.log('Current time:', now.toLocaleString());
    console.log('Distance:', distance, 'ms');

    // If time has already passed, show "انتهى الوقت" immediately
    if (distance < 0) {
        element.innerHTML = '<span class="text-danger">انتهى الوقت</span>';
        console.log('Time is up, triggering notification');

        // Check if notification is already triggered to prevent multiple triggers
        const row = element.closest('tr');
        const statusCell = row.querySelector('td:nth-child(6)'); // Status column
        const isTriggered = statusCell.textContent.includes('تم التنبيه');

        if (!isTriggered) {
            // Automatically trigger notification when time is up (only once)
            const notificationId = row.querySelector('button[onclick*="triggerNotification"]').getAttribute('onclick').match(/\d+/)[0];
            console.log('Triggering notification ID:', notificationId);

            // Get notification details from the table row
            const title = row.querySelector('td:nth-child(1)').textContent.trim();
            const message = row.querySelector('td:nth-child(2)').textContent.trim();
            const customer = row.querySelector('td:nth-child(3)').textContent.trim();

            // Show notification with actual content
            showNotificationAlert(title, message, customer);

            // Trigger the backend update
            triggerNotification(notificationId);
        }
        return;
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    const countdownText = `${days}d ${hours}h ${minutes}m ${seconds}s`;
    element.innerHTML = countdownText;
    console.log('Countdown:', countdownText);

    // Update every second
    setTimeout(() => startCountdown(element), 1000);
}

// Initialize countdown timers on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing countdowns');
    initializeCountdowns();
});

// Also initialize countdowns immediately if DOM is already loaded
if (document.readyState === 'loading') {
    // DOM is still loading, wait for DOMContentLoaded
} else {
    // DOM is already loaded, initialize immediately
    console.log('DOM already loaded, initializing countdowns immediately');
    initializeCountdowns();
}

// Function to initialize countdowns
function initializeCountdowns() {
    const countdownElements = document.querySelectorAll('.countdown-timer');
    console.log('Found countdown elements:', countdownElements.length);

    countdownElements.forEach(function(element, index) {
        console.log(`Starting countdown ${index + 1}:`, element);
        try {
            // Check if time has already passed and update immediately
            const remindAtStr = element.dataset.remindAt;
            if (remindAtStr) {
                const remindAt = new Date(remindAtStr);
                const now = new Date();
                const distance = remindAt.getTime() - now.getTime();

                console.log(`Notification ${index + 1} - Remind time:`, remindAt.toLocaleString());
                console.log(`Notification ${index + 1} - Current time:`, now.toLocaleString());
                console.log(`Notification ${index + 1} - Distance:`, distance, 'ms');

                if (distance < 0) {
                    // Time has passed, show "انتهى الوقت" immediately
                    element.innerHTML = '<span class="text-danger">انتهى الوقت</span>';
                    console.log(`Notification ${index + 1} time has passed`);
                } else {
                    // Start countdown for future notifications
                    startCountdown(element);
                }
            }
        } catch (error) {
            console.error(`Error starting countdown ${index + 1}:`, error);
        }
    });
}

// Force initialization after a short delay
setTimeout(function() {
    console.log('Forcing countdown initialization after delay');
    initializeCountdowns();
}, 1000);
</script>
@endsection
