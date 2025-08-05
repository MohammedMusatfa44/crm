<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'نظام إدارة علاقات العملاء')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4@5.0.15/bootstrap-4.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Cairo', 'Tajawal', Arial, sans-serif;
        }
        .modern-header {
            background: #fff;
            box-shadow: 0 2px 12px rgba(33,150,243,0.07);
            border-bottom: 1px solid #e3e6ea;
            padding: 0.7rem 0;
        }
        .modern-header .navbar-brand {
            font-weight: bold;
            color: #0b58ca;
            font-size: 1.3rem;
            letter-spacing: 1px;
        }
        .modern-header .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .modern-header .user-info .nav-link {
            color: #0b58ca;
            position: relative;
        }
        .modern-header .user-info .badge {
            font-size: 0.7rem;
        }
        .modern-header .user-info img {
            border: 2px solid #0b58ca;
        }
        .modern-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            right: auto;
            height: 100vh;
            min-width: 220px;
            max-width: 240px;
            background: #fff;
            color: #222;
            border-radius: 0 1.2rem 1.2rem 0;
            box-shadow: 0 4px 24px rgba(33,150,243,0.10);
            padding: 2rem 1rem 1rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
            z-index: 1040;
        }
        .modern-sidebar .nav-link {
            color: #222;
            font-weight: 500;
            border-radius: 0.7rem;
            padding: 0.7rem 1rem;
            margin-bottom: 0.3rem;
            transition: background 0.18s, color 0.18s;
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }
        .modern-sidebar .nav-link.active, .modern-sidebar .nav-link:hover {
            background: #0b58ca;
            color: #fff;
        }
        .modern-sidebar .nav-link i {
            font-size: 1.2rem;
        }
        .modern-sidebar .sidebar-title {
            color: #0b58ca;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1.2rem;
            letter-spacing: 1px;
        }
        .modern-sidebar .logout-section {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid #e3e6ea;
        }
        .modern-sidebar .logout-btn {
            background: #dc3545;
            color: #fff;
            border: none;
            border-radius: 0.7rem;
            padding: 0.7rem 1rem;
            font-weight: 500;
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            transition: background 0.18s;
        }
        .modern-sidebar .logout-btn:hover {
            background: #c82333;
            color: #fff;
        }
        .main-content-fixed {
            margin-left: 240px;
            margin-right: 0;
        }
        @media (max-width: 991px) {
            .modern-sidebar {
                position: static;
                min-width: 100%;
                max-width: 100%;
                border-radius: 0 0 1.2rem 1.2rem;
                margin: 0 0 1.5rem 0;
                height: auto;
                flex-direction: row;
                gap: 0.5rem;
                padding: 1rem 0.5rem;
            }
            .main-content-fixed {
                margin-left: 0;
                margin-right: 0;
            }
            .modern-sidebar .logout-section {
                margin-top: 0;
                padding-top: 0;
                border-top: none;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar modern-header navbar-expand-lg">
        <div class="container-fluid justify-content-between">
            <div class="d-flex align-items-center">
                <a class="navbar-brand position-relative" href="#" id="headerNotificationBell">
                    نظام إدارة علاقات العملاء
                    @can('notifications.view')
                    @php
                        $notificationCount = 0;
                        if (auth()->check()) {
                            $notificationCount = \App\Models\Notification::where('user_id', auth()->id())
                                ->where('is_triggered', true)
                                ->where('is_read', false)
                                ->count();
                        }
                    @endphp
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="headerNotificationCount" style="font-size: 0.7rem; min-width: 18px; margin-left: 10px;">
                        {{ $notificationCount }}
                    </span>
                    @endcan
                </a>
            </div>
            <div class="user-info">
                <span class="me-3">@auth {{ Auth::user()->name }} @endauth</span>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'مستخدم') }}" class="rounded-circle ms-2" width="40" height="40" alt="User">
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row flex-row-reverse align-items-stretch min-vh-100">
            <aside class="col-md-2 modern-sidebar d-flex flex-column h-100" style="margin:0;">
                <div class="sidebar-title">القائمة الرئيسية</div>
                <ul class="nav flex-column">
                    @can('dashboard.view')
                    <li class="nav-item">
                        <a class="nav-link @if(request()->is('dashboard')) active @endif" href="/dashboard">
                            <i class="bi bi-speedometer2"></i> لوحة التحكم
                        </a>
                    </li>
                    @endcan

                    @can('clients.view')
                    <li class="nav-item">
                        <a class="nav-link @if(request()->is('customers*')) active @endif" href="/customers">
                            <i class="bi bi-people"></i> العملاء
                        </a>
                    </li>
                    @endcan

                    @can('users.view')
                    <li class="nav-item">
                        <a class="nav-link @if(request()->is('users*')) active @endif" href="/users">
                            <i class="bi bi-person-badge"></i> المستخدمون
                        </a>
                    </li>
                    @endcan

                    @can('sections.view')
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#sidebarDepartmentsMenu" role="button" aria-expanded="false" aria-controls="sidebarDepartmentsMenu">
                            <span><i class="bi bi-diagram-3"></i> الأقسام</span>
                            <i class="bi bi-chevron-down small"></i>
                        </a>
                        <ul class="collapse list-unstyled ps-4" id="sidebarDepartmentsMenu">
                            @php
                                $sidebarDepartments = \App\Models\Department::all();
                            @endphp
                            @foreach($sidebarDepartments as $dep)
                                <li class="mb-1">
                                    <a href="{{ url('departments/' . $dep->id) }}" class="text-decoration-none text-light-emphasis">• {{ $dep->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    @endcan

                    @can('notifications.view')
                    <li class="nav-item">
                        <a class="nav-link @if(request()->is('notifications*')) active @endif" href="/notifications">
                            <i class="bi bi-bell"></i> الإشعارات
                        </a>
                    </li>
                    @endcan

                    @can('support.send_ticket')
                    <li class="nav-item">
                        <a class="nav-link @if(request()->is('support-tickets*')) active @endif" href="/support-tickets">
                            <i class="bi bi-life-preserver"></i> الدعم الفني
                        </a>
                    </li>
                    @endcan

                    @can('roles.view')
                    <li class="nav-item">
                        <a class="nav-link @if(request()->is('permissions*')) active @endif" href="/permissions">
                            <i class="bi bi-shield-lock"></i> الصلاحيات
                        </a>
                    </li>
                    @endcan
                </ul>

                <!-- Logout Section -->
                <div class="logout-section">
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn" onclick="return confirm('هل أنت متأكد من تسجيل الخروج؟')">
                            <i class="bi bi-box-arrow-right"></i>
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            </aside>

            <main class="col-md-10 py-4 main-content-fixed" style="min-height:100vh;">
                @yield('content')
            </main>
        </div>
    </div>

    <footer class="text-center py-3 bg-light mt-4">
        جميع الحقوق محفوظة &copy; {{ date('Y') }}
    </footer>

    <!-- Global Notification System -->
    <!-- Modern Notification Alert Container -->
    <div id="notificationContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
        <!-- Close All Button (hidden by default) -->
        <div id="closeAllButton" style="display: none; margin-bottom: 10px;">
            <button type="button" class="btn btn-sm btn-outline-light" onclick="closeAllNotifications()" style="width: 100%; border-radius: 20px;">
                <i class="bi bi-x-circle"></i> إغلاق الكل
            </button>
        </div>
        <!-- Notifications will be added here dynamically -->
    </div>

    <!-- Template for individual notification -->
    <template id="notificationTemplate">
        <div class="notification-alert">
            <div class="notification-content">
                <div class="notification-header">
                    <div class="notification-icon">
                        <i class="bi bi-bell-fill"></i>
                    </div>
                    <div class="notification-title">
                        <strong class="notification-title-text"></strong>
                    </div>
                    <button type="button" class="notification-close" onclick="closeNotification(this)">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="notification-body">
                    <p class="notification-message mb-0"></p>
                </div>
            </div>
        </div>
    </template>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net@1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>

    <script>
        // Set up CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            xhrFields: {
                withCredentials: true
            }
        });

        // Configure toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };

        // Update notification count in header
        function updateHeaderNotificationCount() {
            $.ajax({
                url: '/notifications/count',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                xhrFields: {
                    withCredentials: true
                },
                success: function(response) {
                    if (response.success) {
                        const count = response.count;
                        const badge = $('#headerNotificationCount');
                        badge.text(count);

                        // Add animation if count increased
                        if (count > 0) {
                            badge.addClass('pulse-animation');
                            setTimeout(() => {
                                badge.removeClass('pulse-animation');
                            }, 1000);
                        }
                    }
                },
                error: function(xhr) {
                    console.log('Error updating notification count:', xhr);
                }
            });
        }

        // Make the title clickable to go to notifications
        $(document).ready(function() {
            $('#headerNotificationBell').click(function(e) {
                e.preventDefault();
                window.location.href = '/notifications';
            });

            // Update notification count immediately
            updateHeaderNotificationCount();

            // Check for notifications immediately when page loads
            console.log('Page loaded, checking for notifications immediately...');
            fetch('/notifications/triggered')
                .then(response => response.json())
                .then(data => {
                    console.log('Initial notification check response:', data);
                    if (data.success && data.count > 0) {
                        console.log(`Found ${data.count} notifications to show on page load`);
                        data.notifications.forEach(function(notification) {
                            console.log('Showing notification on page load:', notification.title);
                            // Show notification with customer information
                            const customerName = notification.customer ? notification.customer.full_name : null;
                            showNotificationAlert(notification.title, notification.message, customerName);

                            // Mark notification as read after showing it (with delay)
                            setTimeout(() => {
                                fetch(`/notifications/mark-read/${notification.id}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                }).then(response => response.json())
                                .then(result => {
                                    console.log('Marked notification as read on page load:', result);
                                })
                                .catch(error => console.log('Error marking notification as read on page load:', error));
                            }, 2000); // Wait 2 seconds before marking as read
                        });
                    } else {
                        console.log('No notifications to show on page load');
                    }
                })
                .catch(error => {
                    console.log('Error checking notifications on page load:', error);
                });
        });

        // Update notification count every 10 seconds
        setInterval(function() {
            updateHeaderNotificationCount();
        }, 10000);

        // Global Notification Functions
        // Debug function to check active notifications
        function debugNotifications() {
            console.log('Active notifications:', window.activeNotifications);
            const container = document.getElementById('notificationContainer');
            const notifications = container.querySelectorAll('.notification-alert');
            console.log('DOM notifications count:', notifications.length);
            notifications.forEach((notification, index) => {
                console.log(`Notification ${index + 1}:`, notification.id);
            });
        }

        // Simple alert functions
        function showAlert(title, message) {
            console.log('Creating alert:', title, message);
            return createNotification(title, message, null, false);
        }

        // Show notification alert with actual content (stays visible until manually closed)
        function showNotificationAlert(title, message, customer) {
            console.log('Creating notification alert:', title, message, customer);
            return createNotification(title, message, customer, true);
        }

        // Create a new notification
        function createNotification(title, message, customer, isRepeating = false) {
            // Prevent multiple notifications with the same content at the same time
            const notificationKey = `${title}-${message}-${customer}-${isRepeating}`;
            if (window.activeNotifications && window.activeNotifications[notificationKey]) {
                console.log('Notification already exists, skipping duplicate');
                return window.activeNotifications[notificationKey];
            }

            // Initialize active notifications object if it doesn't exist
            if (!window.activeNotifications) {
                window.activeNotifications = {};
            }

            const container = document.getElementById('notificationContainer');
            const template = document.getElementById('notificationTemplate');
            const notificationElement = template.content.cloneNode(true);

            // Generate unique ID for this notification
            const notificationId = 'notification_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            const alertElement = notificationElement.querySelector('.notification-alert');
            alertElement.id = notificationId;

            // Set notification content
            notificationElement.querySelector('.notification-title-text').textContent = title;
            notificationElement.querySelector('.notification-message').textContent = customer ? `${message}\n\nالعميل: ${customer}` : message;

            // Add to container
            container.appendChild(notificationElement);

            // Store the notification ID
            window.activeNotifications[notificationKey] = notificationId;

            // Show "Close All" button if there are multiple notifications
            const notifications = container.querySelectorAll('.notification-alert');
            const closeAllButton = document.getElementById('closeAllButton');
            if (notifications.length > 1) {
                closeAllButton.style.display = 'block';
            }

            // Play sound
            if (isRepeating) {
                playRepeatingSound(alertElement);
            } else {
                playSingleSound();
            }

            // Auto-hide simple alerts after 5 seconds
            if (!isRepeating) {
                setTimeout(() => {
                    const alert = document.getElementById(notificationId);
                    if (alert) {
                        closeNotificationById(notificationId);
                    }
                }, 5000);
            }

            return notificationId;
        }

        // Close notification by ID
        function closeNotificationById(notificationId) {
            const notificationElement = document.getElementById(notificationId);
            if (notificationElement) {
                // Stop repeating sound if exists
                if (notificationElement.soundInterval) {
                    clearInterval(notificationElement.soundInterval);
                    notificationElement.soundInterval = null;
                }

                // Remove from active notifications tracking
                if (window.activeNotifications) {
                    for (const key in window.activeNotifications) {
                        if (window.activeNotifications[key] === notificationId) {
                            delete window.activeNotifications[key];
                            break;
                        }
                    }
                }

                // Remove the notification
                notificationElement.remove();

                // Hide "Close All" button if only one notification remains
                const container = document.getElementById('notificationContainer');
                const remainingNotifications = container.querySelectorAll('.notification-alert');
                const closeAllButton = document.getElementById('closeAllButton');
                if (remainingNotifications.length <= 1) {
                    closeAllButton.style.display = 'none';
                }
            }
        }

        // Close individual notification
        function closeNotification(button) {
            const notificationElement = button.closest('.notification-alert');

            // Stop repeating sound if exists
            if (notificationElement.soundInterval) {
                clearInterval(notificationElement.soundInterval);
                notificationElement.soundInterval = null;
            }

            // Remove from active notifications tracking
            if (window.activeNotifications) {
                for (const key in window.activeNotifications) {
                    if (window.activeNotifications[key] === notificationElement.id) {
                        delete window.activeNotifications[key];
                        break;
                    }
                }
            }

            // Remove the notification
            notificationElement.remove();

            // Hide "Close All" button if only one notification remains
            const container = document.getElementById('notificationContainer');
            const remainingNotifications = container.querySelectorAll('.notification-alert');
            const closeAllButton = document.getElementById('closeAllButton');
            if (remainingNotifications.length <= 1) {
                closeAllButton.style.display = 'none';
            }
        }

        // Close all notifications and stop all sounds
        function closeAllNotifications() {
            const container = document.getElementById('notificationContainer');
            const notifications = container.querySelectorAll('.notification-alert');

            notifications.forEach(notification => {
                // Stop repeating sound if exists
                if (notification.soundInterval) {
                    clearInterval(notification.soundInterval);
                    notification.soundInterval = null;
                }
                // Remove the notification
                notification.remove();
            });

            // Clear all active notifications tracking
            window.activeNotifications = {};

            // Hide "Close All" button if all notifications are closed
            const closeAllButton = document.getElementById('closeAllButton');
            if (notifications.length === 0) {
                closeAllButton.style.display = 'none';
            }
        }

        // Legacy function for backward compatibility
        function hideAlert() {
            closeAllNotifications();
        }

        // Play single sound (for simple alerts)
        function playSingleSound() {
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
                oscillator.type = 'sine';

                gainNode.gain.setValueAtTime(0.2, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);

                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.3);

                console.log('Single sound played');
            } catch (e) {
                console.log('Could not play single sound:', e);
            }
        }

        // Play repeating sound for notification alerts
        function playRepeatingSound(notificationElement) {
            let soundInterval;
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();

                function playBeep() {
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();

                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);

                    oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
                    oscillator.type = 'sine';

                    gainNode.gain.setValueAtTime(0.2, audioContext.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);

                    oscillator.start(audioContext.currentTime);
                    oscillator.stop(audioContext.currentTime + 0.3);
                }

                // Play initial beep
                playBeep();

                // Repeat sound every 2 seconds
                soundInterval = setInterval(playBeep, 2000);

                // Store interval ID in the notification element
                notificationElement.soundInterval = soundInterval;

                console.log('Repeating sound started');
            } catch (e) {
                console.log('Could not play repeating sound:', e);
            }
        }

        // Check for triggered notifications every 10 seconds
        setInterval(function() {
            console.log('Checking for notifications...');
            fetch('/notifications/triggered')
                .then(response => response.json())
                .then(data => {
                    console.log('Notification check response:', data);
                    if (data.success && data.count > 0) {
                        console.log(`Found ${data.count} notifications to show`);
                        data.notifications.forEach(function(notification) {
                            console.log('Showing notification:', notification.title);
                            // Show notification with customer information
                            const customerName = notification.customer ? notification.customer.full_name : null;
                            showNotificationAlert(notification.title, notification.message, customerName);

                            // Mark notification as read after showing it (with delay)
                            setTimeout(() => {
                                fetch(`/notifications/mark-read/${notification.id}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                }).then(response => response.json())
                                .then(result => {
                                    console.log('Marked notification as read:', result);
                                })
                                .catch(error => console.log('Error marking notification as read:', error));
                            }, 2000); // Wait 2 seconds before marking as read
                        });
                    } else {
                        console.log('No notifications to show');
                    }
                })
                .catch(error => {
                    console.log('Error checking notifications:', error);
                });
        }, 10000);

        // Test function to manually trigger notifications (for testing)
        window.testNotification = function() {
            showNotificationAlert('اختبار التنبيه', 'هذا تنبيه تجريبي لاختبار النظام', 'عميل تجريبي');
        };

        // Test function to force trigger all notifications (for testing)
        window.forceTriggerNotifications = function() {
            fetch('/notifications/force-trigger', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('تم التفعيل', data.message);
                } else {
                    showAlert('خطأ', data.message);
                }
            })
            .catch(error => {
                console.error('Error forcing trigger:', error);
                showAlert('خطأ', 'حدث خطأ أثناء إجبار التفعيل');
            });
        };

        // Test function to check current notification state
        window.checkNotificationState = function() {
            console.log('Checking notification state...');
            fetch('/notifications/triggered')
                .then(response => response.json())
                .then(data => {
                    console.log('Current notification state:', data);
                    if (data.debug) {
                        console.log('Debug info:', data.debug);
                    }
                    showAlert('حالة التنبيهات', `تم العثور على ${data.count} تنبيه. راجع وحدة التحكم للحصول على التفاصيل.`);
                })
                .catch(error => {
                    console.error('Error checking notification state:', error);
                    showAlert('خطأ', 'حدث خطأ أثناء فحص حالة التنبيهات');
                });
        };

        // Test function to check basic notification functionality
        window.testBasicNotifications = function() {
            console.log('Testing basic notification functionality...');
            fetch('/test-notifications')
                .then(response => response.json())
                .then(data => {
                    console.log('Basic notification test response:', data);
                    showAlert('اختبار التنبيهات', `المستخدم: ${data.user_id}, إجمالي التنبيهات: ${data.total_notifications}`);
                })
                .catch(error => {
                    console.error('Error testing basic notifications:', error);
                    showAlert('خطأ', 'حدث خطأ أثناء اختبار التنبيهات الأساسية');
                });
        };
    </script>

    <style>
        /* Animation for notification badge */
        .pulse-animation {
            animation: pulse 1s ease-in-out;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        /* Title hover effect */
        #headerNotificationBell {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #headerNotificationBell:hover {
            transform: scale(1.02);
            color: #0b58ca !important;
        }

        /* Badge styling */
        #headerNotificationCount {
            transition: all 0.3s ease;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        /* Make the title more prominent */
        .navbar-brand {
            font-weight: bold;
            color: #0b58ca;
            font-size: 1.3rem;
            letter-spacing: 1px;
        }

        /* Global Notification Styles */
        .notification-alert {
            position: relative;
            max-width: 400px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border: none;
            color: white;
            animation: slideInRight 0.5s ease-out;
            margin-bottom: 10px;
            width: 100%;
        }

        #notificationContainer {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .notification-content {
            padding: 20px;
        }

        .notification-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .notification-icon {
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }

        .notification-icon i {
            font-size: 18px;
            color: white;
        }

        .notification-title {
            flex: 1;
            font-size: 16px;
            font-weight: 600;
        }

        .notification-close {
            background: rgba(255,255,255,0.2);
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notification-close:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.1);
        }

        .notification-body {
            font-size: 14px;
            line-height: 1.5;
            opacity: 0.9;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
    </style>

    @yield('scripts')

    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.iife.js"></script>
    <script>
        // Temporarily disabled Pusher to avoid connection errors
        /*
        Pusher.logToConsole = true;
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env('PUSHER_APP_KEY') }}',
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: true
        });
        Echo.channel('customers')
            .listen('CustomerAdded', (e) => {
                toastr.success(e.message + ' - ' + e.name);
                // Optionally play a sound or update a counter
            });
        */
    </script>
</body>
</html>
