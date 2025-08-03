<div class="permission-group">
    <div class="permission-group-title">لوحة التحكم</div>
    <div class="row">
        <div class="col-md-6">
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="dashboard.view" class="permission-checkbox" {{ in_array('dashboard.view', $rolePermissions) ? 'checked' : '' }}>
                عرض لوحة التحكم
            </div>
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="dashboard.add_section" class="permission-checkbox" {{ in_array('dashboard.add_section', $rolePermissions) ? 'checked' : '' }}>
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
                <input type="checkbox" name="permissions[]" value="clients.view" class="permission-checkbox" {{ in_array('clients.view', $rolePermissions) ? 'checked' : '' }}>
                عرض العملاء
            </div>
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="clients.create" class="permission-checkbox" {{ in_array('clients.create', $rolePermissions) ? 'checked' : '' }}>
                إضافة عميل
            </div>
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="clients.edit" class="permission-checkbox" {{ in_array('clients.edit', $rolePermissions) ? 'checked' : '' }}>
                تعديل عميل
            </div>
        </div>
        <div class="col-md-6">
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="clients.delete" class="permission-checkbox" {{ in_array('clients.delete', $rolePermissions) ? 'checked' : '' }}>
                حذف عميل
            </div>
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="clients.update_status" class="permission-checkbox" {{ in_array('clients.update_status', $rolePermissions) ? 'checked' : '' }}>
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
                <input type="checkbox" name="permissions[]" value="users.view" class="permission-checkbox" {{ in_array('users.view', $rolePermissions) ? 'checked' : '' }}>
                عرض المستخدمين
            </div>
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="users.create_admin" class="permission-checkbox" {{ in_array('users.create_admin', $rolePermissions) ? 'checked' : '' }}>
                إضافة مدير
            </div>
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="users.create_employee" class="permission-checkbox" {{ in_array('users.create_employee', $rolePermissions) ? 'checked' : '' }}>
                إضافة موظف
            </div>
        </div>
        <div class="col-md-6">
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="users.edit" class="permission-checkbox" {{ in_array('users.edit', $rolePermissions) ? 'checked' : '' }}>
                تعديل مستخدم
            </div>
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="users.delete" class="permission-checkbox" {{ in_array('users.delete', $rolePermissions) ? 'checked' : '' }}>
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
                <input type="checkbox" name="permissions[]" value="sections.view" class="permission-checkbox" {{ in_array('sections.view', $rolePermissions) ? 'checked' : '' }}>
                عرض الأقسام
            </div>
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="sections.create_main" class="permission-checkbox" {{ in_array('sections.create_main', $rolePermissions) ? 'checked' : '' }}>
                إضافة قسم رئيسي
            </div>
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="sections.create_sub" class="permission-checkbox" {{ in_array('sections.create_sub', $rolePermissions) ? 'checked' : '' }}>
                إضافة قسم فرعي
            </div>
        </div>
        <div class="col-md-6">
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="sections.edit" class="permission-checkbox" {{ in_array('sections.edit', $rolePermissions) ? 'checked' : '' }}>
                تعديل قسم
            </div>
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="sections.delete" class="permission-checkbox" {{ in_array('sections.delete', $rolePermissions) ? 'checked' : '' }}>
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
                <input type="checkbox" name="permissions[]" value="notifications.view" class="permission-checkbox" {{ in_array('notifications.view', $rolePermissions) ? 'checked' : '' }}>
                عرض الإشعارات
            </div>
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="notifications.create" class="permission-checkbox" {{ in_array('notifications.create', $rolePermissions) ? 'checked' : '' }}>
                إضافة إشعار
            </div>
        </div>
        <div class="col-md-6">
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="notifications.edit" class="permission-checkbox" {{ in_array('notifications.edit', $rolePermissions) ? 'checked' : '' }}>
                تعديل إشعار
            </div>
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="notifications.delete" class="permission-checkbox" {{ in_array('notifications.delete', $rolePermissions) ? 'checked' : '' }}>
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
                <input type="checkbox" name="permissions[]" value="support.send_ticket" class="permission-checkbox" {{ in_array('support.send_ticket', $rolePermissions) ? 'checked' : '' }}>
                إرسال تذكرة دعم
            </div>
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="support.view_all_tickets" class="permission-checkbox" {{ in_array('support.view_all_tickets', $rolePermissions) ? 'checked' : '' }}>
                عرض جميع التذاكر
            </div>
        </div>
        <div class="col-md-6">
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="support.view_my_team_tickets" class="permission-checkbox" {{ in_array('support.view_my_team_tickets', $rolePermissions) ? 'checked' : '' }}>
                عرض تذاكر الفريق
            </div>
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="support.view_own_tickets" class="permission-checkbox" {{ in_array('support.view_own_tickets', $rolePermissions) ? 'checked' : '' }}>
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
                <input type="checkbox" name="permissions[]" value="roles.view" class="permission-checkbox" {{ in_array('roles.view', $rolePermissions) ? 'checked' : '' }}>
                عرض الأدوار
            </div>
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="roles.create" class="permission-checkbox" {{ in_array('roles.create', $rolePermissions) ? 'checked' : '' }}>
                إضافة دور
            </div>
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="roles.edit" class="permission-checkbox" {{ in_array('roles.edit', $rolePermissions) ? 'checked' : '' }}>
                تعديل دور
            </div>
        </div>
        <div class="col-md-6">
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="roles.delete" class="permission-checkbox" {{ in_array('roles.delete', $rolePermissions) ? 'checked' : '' }}>
                حذف دور
            </div>
            <div class="permission-label">
                <input type="checkbox" name="permissions[]" value="permissions.assign" class="permission-checkbox" {{ in_array('permissions.assign', $rolePermissions) ? 'checked' : '' }}>
                تعيين صلاحيات
            </div>
        </div>
    </div>
</div>
