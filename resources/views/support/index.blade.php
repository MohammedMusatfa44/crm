@extends('layouts.layout')

@section('title', 'الدعم الفني')

@section('styles')
<style>
    .dashboard-bg {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 2rem 0;
    }
    .dashboard-header-card {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.08);
        padding: 2rem;
        margin-bottom: 2rem;
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
    .modern-table {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.08);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    .table-header {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
        color: #fff;
        padding: 1.5rem;
        font-size: 1.2rem;
        font-weight: 600;
    }
    .table-responsive {
        border-radius: 1.2rem;
        overflow: hidden;
    }
    .table {
        margin-bottom: 0;
    }
    .table th {
        background: #f8f9fa;
        border: none;
        padding: 1rem;
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
        text-align: center;
    }
    .table td {
        border: none;
        padding: 1rem;
        vertical-align: middle;
        text-align: center;
        border-bottom: 1px solid #f1f3f4;
    }
    .table tbody tr:hover {
        background: #f8f9fa;
        transform: scale(1.01);
        transition: all 0.2s ease;
    }
    .table tbody tr:nth-child(even) {
        background: #fafbfc;
    }
    .priority-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 0.8rem;
        font-size: 0.8rem;
        font-weight: 500;
    }
    .priority-badge.low {
        background: #d4edda;
        color: #155724;
    }
    .priority-badge.medium {
        background: #fff3cd;
        color: #856404;
    }
    .priority-badge.high {
        background: #f8d7da;
        color: #721c24;
    }
    .priority-badge.urgent {
        background: #d1ecf1;
        color: #0c5460;
    }
    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 0.8rem;
        font-size: 0.8rem;
        font-weight: 500;
    }
    .status-badge.open {
        background: #d1ecf1;
        color: #0c5460;
    }
    .status-badge.in_progress {
        background: #fff3cd;
        color: #856404;
    }
    .status-badge.closed {
        background: #d4edda;
        color: #155724;
    }
    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 0.6rem;
        font-size: 0.8rem;
        font-weight: 500;
        border: none;
        margin: 0.1rem;
        transition: all 0.2s ease;
    }
    .btn-action.btn-view {
        background: #e3f2fd;
        color: #1976d2;
    }
    .btn-action.btn-view:hover {
        background: #bbdefb;
        color: #1565c0;
    }
    .btn-action.btn-reply {
        background: #e8f5e8;
        color: #2e7d32;
    }
    .btn-action.btn-reply:hover {
        background: #c8e6c9;
        color: #1b5e20;
    }
</style>
@endsection

@section('content')
@can('support.send_ticket')
<div class="dashboard-bg">
    <div class="container-fluid">
        <!-- Header Card -->
        <div class="dashboard-header-card">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="dashboard-title">
                        @if(auth()->user()->hasRole('super_admin'))
                            الدعم الفني
                        @else
                            تذاكر الدعم الشخصية
                        @endif
                    </h1>
                    <p class="dashboard-subtitle">
                        @if(auth()->user()->hasRole('super_admin'))
                            إدارة جميع تذاكر الدعم الفني
                        @else
                            تذاكر الدعم الفني الخاصة بك - لا يمكن للآخرين رؤيتها
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="top-actions">
                        <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addTicketModal">
                            إضافة تذكرة دعم
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support Tickets Table -->
        <div class="modern-table">
            <div class="table-header">
                @if(auth()->user()->hasRole('super_admin'))
                    قائمة جميع تذاكر الدعم الفني
                @else
                    قائمة تذاكر الدعم الفني الشخصية
                @endif
            </div>
            <div class="table-responsive">
                <table id="supportTicketsTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>الموضوع</th>
                            <th>الوصف</th>
                            <th>الأولوية</th>
                            <th>الحالة</th>
                            @if(auth()->check() && auth()->user()->hasRole('super_admin'))
                                <th>المستخدم</th>
                            @endif
                            <th>رد الإدارة</th>
                            <th>تاريخ الرد</th>
                            <th>خيارات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($tickets->isEmpty())
                        <tr>
                            <td class="text-center py-4" style="border: none;">
                                <div class="text-muted">
                                    <i class="bi bi-ticket-detailed" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">
                                        @if(auth()->user()->hasRole('super_admin'))
                                            لا توجد تذاكر دعم فني
                                        @else
                                            لا توجد تذاكر دعم فني شخصية
                                        @endif
                                    </p>
                                    <small>أنشئ تذكرة دعم جديدة لتبدأ في استخدام النظام</small>
                                </div>
                            </td>
                            <td style="border: none;"></td>
                            <td style="border: none;"></td>
                            <td style="border: none;"></td>
                            @if(auth()->user()->hasRole('super_admin'))
                                <td style="border: none;"></td>
                            @endif
                            <td style="border: none;"></td>
                            <td style="border: none;"></td>
                            <td style="border: none;"></td>
                        </tr>
                        @else
                        @foreach($tickets as $ticket)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $ticket->subject }}</div>
                            </td>
                            <td>{{ Str::limit($ticket->description, 50) }}</td>
                            <td>
                                @if($ticket->priority == 'low')
                                    <span class="priority-badge low">منخفضة</span>
                                @elseif($ticket->priority == 'medium')
                                    <span class="priority-badge medium">متوسطة</span>
                                @elseif($ticket->priority == 'high')
                                    <span class="priority-badge high">مرتفعة</span>
                                @else
                                    <span class="priority-badge urgent">عاجلة</span>
                                @endif
                            </td>
                            <td>
                                @if($ticket->status == 'open')
                                    <span class="status-badge open">مفتوحة</span>
                                @elseif($ticket->status == 'in_progress')
                                    <span class="status-badge in_progress">قيد المعالجة</span>
                                @else
                                    <span class="status-badge closed">مغلقة</span>
                                @endif
                            </td>
                            @if(auth()->check() && auth()->user()->hasRole('super_admin'))
                                <td>{{ $ticket->user->name ?? 'غير محدد' }}</td>
                            @endif
                            <td>{{ Str::limit($ticket->admin_reply, 30) ?? '-' }}</td>
                            <td>{{ $ticket->replied_at ? $ticket->replied_at->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                <button class="btn btn-action btn-view" onclick="viewTicket({{ $ticket->id }})" title="عرض">
                                    عرض
                                </button>
                                @php
                                    $canReply = false;
                                    $currentUser = auth()->user();
                                    $ticketCreator = $ticket->user;

                                    if ($currentUser->hasRole('super_admin')) {
                                        $canReply = true;
                                    } elseif ($currentUser->hasRole('admin')) {
                                        // Admin can only reply to tickets created by employees they created
                                        $canReply = $ticketCreator->hasRole('employee') && $ticketCreator->created_by === $currentUser->id;
                                    }
                                    // Employees cannot reply to any tickets
                                @endphp
                                @if($canReply)
                                <button class="btn btn-action btn-reply" onclick="replyTicket({{ $ticket->id }})" title="رد">
                                    رد
                                </button>
                                @endif
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

<!-- Modal: إضافة تذكرة دعم -->
<div class="modal fade" id="addTicketModal" tabindex="-1" aria-labelledby="addTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTicketModalLabel">إضافة تذكرة دعم جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form id="addTicketForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">الموضوع</label>
                        <input type="text" class="form-control" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea class="form-control" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الأولوية</label>
                        <select class="form-select" name="priority" required>
                            <option value="low">منخفضة</option>
                            <option value="medium">متوسطة</option>
                            <option value="high">مرتفعة</option>
                            <option value="urgent">عاجلة</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">إرسال</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal: عرض التذكرة -->
<div class="modal fade" id="viewTicketModal" tabindex="-1" aria-labelledby="viewTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTicketModalLabel">تفاصيل التذكرة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body" id="ticketDetails">
                <!-- Ticket details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Modal: رد على التذكرة -->
<div class="modal fade" id="replyTicketModal" tabindex="-1" aria-labelledby="replyTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replyTicketModalLabel">رد على التذكرة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form id="replyTicketForm">
                    @csrf
                    <input type="hidden" id="replyTicketId" name="ticket_id">
                    <div class="mb-3">
                        <label class="form-label">رد الإدارة</label>
                        <textarea class="form-control" name="admin_reply" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">إرسال الرد</button>
                </form>
            </div>
        </div>
    </div>
</div>
@else
<div class="container-fluid">
    <div class="alert alert-warning text-center">
        <h4>ليس لديك صلاحية لإرسال تذاكر الدعم الفني</h4>
        <p>يرجى التواصل مع مدير النظام للحصول على الصلاحيات المطلوبة.</p>
    </div>
</div>
@endcan
@endsection

@section('scripts')
@can('support.send_ticket')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Check if table already has DataTable instance
    if ($.fn.DataTable.isDataTable('#supportTicketsTable')) {
        $('#supportTicketsTable').DataTable().destroy();
    }

    // Initialize DataTable with proper configuration
    var table = $('#supportTicketsTable').DataTable({
        language: {
            "sProcessing": "جاري المعالجة...",
            "sLengthMenu": "أظهر _MENU_ مدخلات",
            "sZeroRecords": "لم يعثر على أية سجلات",
            "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
            "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
            "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
            "sInfoPostFix": "",
            "sSearch": "ابحث:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "الأول",
                "sPrevious": "السابق",
                "sNext": "التالي",
                "sLast": "الأخير"
            }
        },
        responsive: true,
        autoWidth: false,
        destroy: true,
        pageLength: 10,
        columnDefs: [
            {
                targets: '_all',
                defaultContent: ''
            }
        ]
    });

    // Add ticket form submission
    $('#addTicketForm').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: '{{ route("support-tickets.store") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم بنجاح',
                    text: 'تم إرسال التذكرة بنجاح',
                    confirmButtonText: 'حسناً'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                var errorMessage = 'حدث خطأ أثناء إرسال التذكرة';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: errorMessage,
                    confirmButtonText: 'حسناً'
                });
            }
        });
    });

    // Reply ticket form submission
    $('#replyTicketForm').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var ticketId = $('#replyTicketId').val();

        $.ajax({
            url: '/support-tickets/' + ticketId + '/reply',
            type: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم بنجاح',
                    text: 'تم إرسال الرد بنجاح',
                    confirmButtonText: 'حسناً'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                var errorMessage = 'حدث خطأ أثناء إرسال الرد';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: errorMessage,
                    confirmButtonText: 'حسناً'
                });
            }
        });
    });
});

function viewTicket(ticketId) {
    // Load ticket details via AJAX
    $.ajax({
        url: '/support-tickets/' + ticketId,
        type: 'GET',
        success: function(response) {
            var ticket = response.ticket;
            var html = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>الموضوع:</h6>
                        <p>${ticket.subject}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>الأولوية:</h6>
                        <p>${getPriorityText(ticket.priority)}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6>الحالة:</h6>
                        <p>${getStatusText(ticket.status)}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>المستخدم:</h6>
                        <p>${ticket.user ? ticket.user.name : 'غير محدد'}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6>الوصف:</h6>
                        <p>${ticket.description}</p>
                    </div>
                </div>
                ${ticket.admin_reply ? `
                <div class="row">
                    <div class="col-12">
                        <h6>رد الإدارة:</h6>
                        <p>${ticket.admin_reply}</p>
                        <small class="text-muted">تاريخ الرد: ${ticket.replied_at}</small>
                    </div>
                </div>
                ` : ''}
            `;
            $('#ticketDetails').html(html);
            $('#viewTicketModal').modal('show');
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'حدث خطأ أثناء تحميل تفاصيل التذكرة',
                confirmButtonText: 'حسناً'
            });
        }
    });
}

function replyTicket(ticketId) {
    $('#replyTicketId').val(ticketId);
    $('#replyTicketModal').modal('show');
}

function getPriorityText(priority) {
    switch(priority) {
        case 'low': return 'منخفضة';
        case 'medium': return 'متوسطة';
        case 'high': return 'مرتفعة';
        case 'urgent': return 'عاجلة';
        default: return priority;
    }
}

function getStatusText(status) {
    switch(status) {
        case 'open': return 'مفتوحة';
        case 'in_progress': return 'قيد المعالجة';
        case 'closed': return 'مغلقة';
        default: return status;
    }
}
</script>
@endcan
@endsection
