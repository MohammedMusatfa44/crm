@extends('layouts.layout')

@section('title', 'إدارة الصلاحيات')

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
    .permission-card {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.08);
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .card-header {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
        color: #fff;
        padding: 1.5rem;
        border-radius: 1.2rem 1.2rem 0 0;
        font-size: 1.2rem;
        font-weight: 600;
        margin: -2rem -2rem 2rem -2rem;
    }
    .role-item {
        background: #f8f9fa;
        border-radius: 0.8rem;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid #e3e6ea;
        transition: all 0.2s ease;
    }
    .role-item:hover {
        background: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .role-name {
        font-weight: 600;
        color: #333;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }
    .role-permissions {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 1rem;
    }
    .permission-tag {
        display: inline-block;
        background: #e3f2fd;
        color: #1976d2;
        padding: 0.2rem 0.6rem;
        border-radius: 0.4rem;
        font-size: 0.8rem;
        margin: 0.1rem;
    }
    .btn-edit-role {
        background: #007bff;
        color: #fff;
        border: none;
        border-radius: 0.6rem;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        transition: background 0.2s;
    }
    .btn-edit-role:hover {
        background: #0056b3;
        color: #fff;
    }
    .form-control, .form-select {
        border-radius: 0.8rem;
        border: 1px solid #e3e6ea;
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0b58ca;
        box-shadow: 0 0 0 0.2rem rgba(11, 88, 202, 0.25);
    }
    .btn-primary {
        background: #0b58ca;
        border: none;
        border-radius: 0.8rem;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
    }
    .btn-primary:hover {
        background: #1976d2;
    }
    .permission-group {
        background: #f8f9fa;
        border-radius: 0.8rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .permission-group-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }
    .permission-checkbox {
        margin-right: 0.5rem;
    }
    .permission-label {
        font-size: 0.9rem;
        color: #555;
        margin-bottom: 0.3rem;
    }
    .user-role-form {
        background: #f8f9fa;
        border-radius: 0.8rem;
        padding: 1.5rem;
        border: 1px solid #e3e6ea;
    }
</style>
@endsection

@section('content')
<div class="dashboard-bg">
    <div class="container-fluid">
        <!-- Header Card -->
        <div class="dashboard-header-card">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="dashboard-title">إدارة الصلاحيات</h1>
                    <p class="dashboard-subtitle">إدارة الأدوار والصلاحيات في النظام</p>
                </div>
                <div class="col-md-6 text-end">
                    @can('roles.create')
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                        إضافة دور جديد
                    </button>
                    @endcan
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Roles Management -->
            <div class="col-md-8">
                <div class="permission-card">
                    <div class="card-header">
                        إدارة الأدوار والصلاحيات
                    </div>

                    @foreach($roles as $role)
                    <div class="role-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="role-name">{{ $role->name == 'super_admin' ? 'مدير عام' : ($role->name == 'admin' ? 'مدير' : 'موظف') }}</div>
                                <div class="role-permissions">
                                    <strong>الصلاحيات:</strong>
                                    @foreach($role->permissions as $permission)
                                        <span class="permission-tag">{{ $permission->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @can('roles.edit')
                            <button class="btn btn-edit-role" onclick="editRole('{{ $role->id }}', '{{ $role->name }}')" data-bs-toggle="modal" data-bs-target="#editRoleModal">
                                تعديل
                            </button>
                            @endcan
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Simple User Role Assignment -->
            <div class="col-md-4">
                <div class="permission-card">
                    <div class="card-header">
                        تعيين دور للمستخدم
                    </div>

                    <div class="user-role-form">
                        <form id="assignRoleForm">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">اختر المستخدم</label>
                                <select class="form-select" id="userSelect" name="user_id" required>
                                    <option value="">اختر مستخدم</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">اختر الدور</label>
                                <select class="form-select" id="roleSelect" name="role_id" required>
                                    <option value="">اختر دور</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name == 'super_admin' ? 'مدير عام' : ($role->name == 'admin' ? 'مدير' : 'موظف') }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                تعيين
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: إضافة دور جديد -->
@can('roles.create')
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoleModalLabel">إضافة دور جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form id="addRoleForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">اسم الدور</label>
                        <input type="text" class="form-control" name="role_name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الصلاحيات</label>
                        <div class="permission-group">
                            <div class="permission-group-title">لوحة التحكم</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="dashboard.view" class="permission-checkbox">
                                        عرض لوحة التحكم
                                    </div>
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="dashboard.add_section" class="permission-checkbox">
                                        إضافة قسم
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="permission-group">
                            <div class="permission-group-title">العملاء</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="clients.view" class="permission-checkbox">
                                        عرض العملاء
                                    </div>
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="clients.create" class="permission-checkbox">
                                        إضافة عميل
                                    </div>
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="clients.edit" class="permission-checkbox">
                                        تعديل عميل
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="clients.delete" class="permission-checkbox">
                                        حذف عميل
                                    </div>
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="clients.update_status" class="permission-checkbox">
                                        تحديث حالة العميل
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="permission-group">
                            <div class="permission-group-title">المستخدمون</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="users.view" class="permission-checkbox">
                                        عرض المستخدمين
                                    </div>
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="users.create_admin" class="permission-checkbox">
                                        إضافة مدير
                                    </div>
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="users.create_employee" class="permission-checkbox">
                                        إضافة موظف
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="users.edit" class="permission-checkbox">
                                        تعديل مستخدم
                                    </div>
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="users.delete" class="permission-checkbox">
                                        حذف مستخدم
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="permission-group">
                            <div class="permission-group-title">الأقسام</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="sections.view" class="permission-checkbox">
                                        عرض الأقسام
                                    </div>
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="sections.create_main" class="permission-checkbox">
                                        إضافة قسم رئيسي
                                    </div>
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="sections.create_sub" class="permission-checkbox">
                                        إضافة قسم فرعي
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="sections.edit" class="permission-checkbox">
                                        تعديل قسم
                                    </div>
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="sections.delete" class="permission-checkbox">
                                        حذف قسم
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="permission-group">
                            <div class="permission-group-title">الإشعارات</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="notifications.view" class="permission-checkbox">
                                        عرض الإشعارات
                                    </div>
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="notifications.create" class="permission-checkbox">
                                        إضافة إشعار
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="notifications.edit" class="permission-checkbox">
                                        تعديل إشعار
                                    </div>
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="notifications.delete" class="permission-checkbox">
                                        حذف إشعار
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="permission-group">
                            <div class="permission-group-title">الدعم الفني</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="support.send_ticket" class="permission-checkbox">
                                        إرسال تذكرة دعم
                                    </div>
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="support.view_all_tickets" class="permission-checkbox">
                                        عرض جميع التذاكر
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="support.view_my_team_tickets" class="permission-checkbox">
                                        عرض تذاكر الفريق
                                    </div>
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="support.view_own_tickets" class="permission-checkbox">
                                        عرض تذاكري
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="permission-group">
                            <div class="permission-group-title">الأدوار والصلاحيات</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="roles.view" class="permission-checkbox">
                                        عرض الأدوار
                                    </div>
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="roles.create" class="permission-checkbox">
                                        إضافة دور
                                    </div>
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="roles.edit" class="permission-checkbox">
                                        تعديل دور
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="roles.delete" class="permission-checkbox">
                                        حذف دور
                                    </div>
                                    <div class="permission-label">
                                        <input type="checkbox" name="permissions[]" value="permissions.assign" class="permission-checkbox">
                                        تعيين صلاحيات
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">إضافة الدور</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan

<!-- Modal: تعديل دور -->
@can('roles.edit')
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel">تعديل الدور</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form id="editRoleForm">
                    @csrf
                    <input type="hidden" id="editRoleId" name="role_id">
                    <div class="mb-3">
                        <label class="form-label">اسم الدور</label>
                        <input type="text" class="form-control" id="editRoleName" name="role_name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الصلاحيات</label>
                        <div id="editPermissionsContainer">
                            <!-- Permissions will be loaded here -->
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">حفظ التغييرات</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Assign role to user
    $('#assignRoleForm').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: '/permissions/assign',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم بنجاح',
                        text: response.message,
                        confirmButtonText: 'حسناً'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: response.message,
                        confirmButtonText: 'حسناً'
                    });
                }
            },
            error: function(xhr) {
                console.error('Error details:', xhr.responseText);
                var errorMessage = 'حدث خطأ أثناء تعيين الدور';
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

    // Add new role
    $('#addRoleForm').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: '{{ route("permissions.roles.store") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم بنجاح',
                        text: response.message,
                        confirmButtonText: 'حسناً'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: response.message,
                        confirmButtonText: 'حسناً'
                    });
                }
            },
            error: function(xhr) {
                console.error('Error details:', xhr.responseText);
                var errorMessage = 'حدث خطأ أثناء إضافة الدور';
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

    // Edit role
    $('#editRoleForm').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: '{{ route("permissions.roles.update") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم بنجاح',
                        text: response.message,
                        confirmButtonText: 'حسناً'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: response.message,
                        confirmButtonText: 'حسناً'
                    });
                }
            },
            error: function(xhr) {
                console.error('Error details:', xhr.responseText);
                var errorMessage = 'حدث خطأ أثناء تحديث الدور';
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

function editRole(roleId, roleName) {
    $('#editRoleId').val(roleId);
    $('#editRoleName').val(roleName);

    // Load role permissions
    $.ajax({
        url: '{{ route("permissions.roles.permissions", ":roleId") }}'.replace(':roleId', roleId),
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#editPermissionsContainer').html(response.html);
            }
        },
        error: function() {
            $('#editPermissionsContainer').html('<p>حدث خطأ أثناء تحميل الصلاحيات</p>');
        }
    });
}
</script>
@endsection
