@extends('layouts.layout')

@section('title', 'تعديل العميل')

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
    .modern-form {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.08);
        padding: 2rem;
        margin-bottom: 1.5rem;
    }
    .form-header {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
        color: #fff;
        padding: 1rem 1.5rem;
        border-radius: 1.2rem 1.2rem 0 0;
        margin: -2rem -2rem 2rem -2rem;
        font-weight: 600;
        font-size: 1.1rem;
    }
    .form-label {
        font-weight: 600;
        color: #555;
        margin-bottom: 0.5rem;
    }
    .form-control, .form-select {
        border: 2px solid #e9ecef;
        border-radius: 0.8rem;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4f5bd5;
        box-shadow: 0 0 0 0.2rem rgba(79, 91, 213, 0.25);
        outline: none;
    }
    .btn-submit {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
        border: none;
        border-radius: 1.2rem;
        padding: 0.75rem 2rem;
        font-weight: 600;
        color: #fff;
        transition: all 0.2s ease;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 91, 213, 0.3);
    }
    .btn-cancel {
        background: #6c757d;
        border: none;
        border-radius: 1.2rem;
        padding: 0.75rem 2rem;
        font-weight: 600;
        color: #fff;
        transition: all 0.2s ease;
    }
    .btn-cancel:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }
    .required-field::after {
        content: " *";
        color: #dc3545;
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<div class="dashboard-bg">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <div class="dashboard-header-card" style="background:#fff; border-radius:1.2rem; box-shadow:0 2px 12px rgba(33,150,243,0.07); padding:1.5rem 2rem 1.2rem 2rem; margin-bottom:1.5rem; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between;">
                    <div>
                        <div class="dashboard-title">تعديل العميل</div>
                        <div class="dashboard-subtitle">{{ $customer->full_name }}</div>
                    </div>
                    <div class="top-actions">
                        <a href="{{ route('customers.index') }}" class="btn btn-cancel">العودة للقائمة</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="modern-form">
                    <div class="form-header">معلومات العميل</div>

                    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">رقم الحساب</label>
                                <input type="text" class="form-control @error('ac_number') is-invalid @enderror"
                                       name="ac_number" value="{{ old('ac_number', $customer->ac_number) }}" required>
                                @error('ac_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">الاسم الكامل</label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                       name="full_name" value="{{ old('full_name', $customer->full_name) }}" required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">رقم الهاتف</label>
                                <input type="text" class="form-control @error('mobile_number') is-invalid @enderror"
                                       name="mobile_number" value="{{ old('mobile_number', $customer->mobile_number) }}" required>
                                @error('mobile_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email', $customer->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">الحالة</label>
                                <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                    <option value="">اختر الحالة</option>
                                    <option value="new" {{ old('status', $customer->status) == 'new' ? 'selected' : '' }}>جديد</option>
                                    <option value="in_progress" {{ old('status', $customer->status) == 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
                                    <option value="follow_up" {{ old('status', $customer->status) == 'follow_up' ? 'selected' : '' }}>متابعة</option>
                                    <option value="western" {{ old('status', $customer->status) == 'western' ? 'selected' : '' }}>غربي</option>
                                    <option value="hot" {{ old('status', $customer->status) == 'hot' ? 'selected' : '' }}>ساخن</option>
                                    <option value="closed" {{ old('status', $customer->status) == 'closed' ? 'selected' : '' }}>مغلق</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">القسم الفرعي</label>
                                <select class="form-select @error('sub_department_id') is-invalid @enderror" name="sub_department_id" required>
                                    <option value="">اختر القسم الفرعي</option>
                                    @foreach($subDepartments as $subDepartment)
                                        <option value="{{ $subDepartment->id }}" {{ old('sub_department_id', $customer->sub_department_id) == $subDepartment->id ? 'selected' : '' }}>
                                            {{ $subDepartment->name }} - {{ $subDepartment->department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sub_department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required-field">الموظف المسؤول</label>
                                <select class="form-select @error('assigned_employee_id') is-invalid @enderror" name="assigned_employee_id" required>
                                    <option value="">اختر الموظف</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('assigned_employee_id', $customer->assigned_employee_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">الجنسية</label>
                                <input type="text" class="form-control @error('nationality') is-invalid @enderror"
                                       name="nationality" value="{{ old('nationality', $customer->nationality) }}">
                                @error('nationality')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">المدينة</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror"
                                       name="city" value="{{ old('city', $customer->city) }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">طريقة التواصل</label>
                                <select class="form-select @error('contact_method') is-invalid @enderror" name="contact_method">
                                    <option value="">اختر طريقة التواصل</option>
                                    <option value="هاتف" {{ old('contact_method', $customer->contact_method) == 'هاتف' ? 'selected' : '' }}>هاتف</option>
                                    <option value="واتساب" {{ old('contact_method', $customer->contact_method) == 'واتساب' ? 'selected' : '' }}>واتساب</option>
                                    <option value="إيميل" {{ old('contact_method', $customer->contact_method) == 'إيميل' ? 'selected' : '' }}>إيميل</option>
                                    <option value="زيارة" {{ old('contact_method', $customer->contact_method) == 'زيارة' ? 'selected' : '' }}>زيارة</option>
                                    <option value="وسائل التواصل الاجتماعي" {{ old('contact_method', $customer->contact_method) == 'وسائل التواصل الاجتماعي' ? 'selected' : '' }}>وسائل التواصل الاجتماعي</option>
                                </select>
                                @error('contact_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">سبب الشكوى</label>
                                <textarea class="form-control @error('complaint_reason') is-invalid @enderror"
                                          name="complaint_reason" rows="3">{{ old('complaint_reason', $customer->complaint_reason) }}</textarea>
                                @error('complaint_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">التعليق</label>
                                <textarea class="form-control @error('comment') is-invalid @enderror"
                                          name="comment" rows="3">{{ old('comment', $customer->comment) }}</textarea>
                                @error('comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">تاريخ الريادة</label>
                                <input type="date" class="form-control @error('lead_date') is-invalid @enderror"
                                       name="lead_date" value="{{ old('lead_date', $customer->lead_date) }}">
                                @error('lead_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">تم التواصل مع طرف آخر</label>
                                <select class="form-select @error('contacted_other_party') is-invalid @enderror" name="contacted_other_party">
                                    <option value="0" {{ old('contacted_other_party', $customer->contacted_other_party) == 0 ? 'selected' : '' }}>لا</option>
                                    <option value="1" {{ old('contacted_other_party', $customer->contacted_other_party) == 1 ? 'selected' : '' }}>نعم</option>
                                </select>
                                @error('contacted_other_party')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-submit me-3">
                                    <i class="bi bi-check-circle"></i> حفظ التغييرات
                                </button>
                                <a href="{{ route('customers.index') }}" class="btn btn-cancel">
                                    <i class="bi bi-x-circle"></i> إلغاء
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
