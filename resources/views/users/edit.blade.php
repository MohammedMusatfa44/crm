@extends('layouts.layout')

@section('title', 'تعديل المستخدم')

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
    .edit-form-card {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.08);
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }
    .form-control, .form-select {
        border-radius: 0.8rem;
        border: 1px solid #e3e6ea;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0b58ca;
        box-shadow: 0 0 0 0.2rem rgba(11, 88, 202, 0.25);
    }
    .btn-save {
        background: #28a745;
        color: #fff;
        border: none;
        border-radius: 0.8rem;
        padding: 0.75rem 2rem;
        font-weight: 500;
        font-size: 1rem;
        transition: background 0.2s;
    }
    .btn-save:hover {
        background: #218838;
        color: #fff;
    }
    .btn-cancel {
        background: #6c757d;
        color: #fff;
        border: none;
        border-radius: 0.8rem;
        padding: 0.75rem 2rem;
        font-weight: 500;
        font-size: 1rem;
        transition: background 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    .btn-cancel:hover {
        background: #5a6268;
        color: #fff;
        text-decoration: none;
    }
    .password-note {
        font-size: 0.9rem;
        color: #6c757d;
        margin-top: 0.25rem;
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
                    <h1 class="dashboard-title">تعديل المستخدم</h1>
                    <p class="dashboard-subtitle">تعديل معلومات المستخدم: {{ $user->name }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('users.index') }}" class="btn-cancel">
                        رجوع
                    </a>
                </div>
            </div>
        </div>

        <!-- Edit Form Card -->
        <div class="edit-form-card">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">الاسم</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">رقم الجوال</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="role" class="form-label">الدور</label>
                            <select class="form-select @error('role') is-invalid @enderror"
                                    id="role" name="role" required>
                                @php
                                    $userRole = $user->roles->first();
                                    $currentRole = $userRole ? $userRole->name : 'employee';
                                @endphp
                                <option value="super_admin" {{ old('role', $currentRole) == 'super_admin' ? 'selected' : '' }}>
                                    مدير عام
                                </option>
                                <option value="admin" {{ old('role', $currentRole) == 'admin' ? 'selected' : '' }}>
                                    مدير
                                </option>
                                <option value="employee" {{ old('role', $currentRole) == 'employee' ? 'selected' : '' }}>
                                    موظف
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور الجديدة</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password">
                            <div class="password-note">اتركها فارغة إذا كنت لا تريد تغيير كلمة المرور</div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">الحالة</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                       value="1" {{ $user->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    نشط
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('users.index') }}" class="btn-cancel me-2">
                        إلغاء
                    </a>
                    <button type="submit" class="btn-save">
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('form').on('submit', function(e) {
        var password = $('#password').val();
        var passwordConfirmation = $('#password_confirmation').val();

        if (password && password !== passwordConfirmation) {
            e.preventDefault();
            alert('كلمة المرور وتأكيد كلمة المرور غير متطابقين');
            return false;
        }
    });
});
</script>
@endsection
