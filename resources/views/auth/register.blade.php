@extends('layouts.guest')

@section('title', 'إنشاء حساب جديد')

@section('styles')
<style>
    body, .signup-bg {
        background: #edeaff;
        min-height: 100vh;
    }
    .signup-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .signup-card {
        background: #fff;
        border-radius: 2rem;
        box-shadow: 0 8px 32px rgba(120, 90, 255, 0.10);
        padding: 2.5rem 2rem 2rem 2rem;
        max-width: 430px;
        width: 100%;
        position: relative;
        z-index: 2;
        animation: fadeInUp 1s cubic-bezier(.39,.575,.56,1.000) both;
    }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(40px) scale(0.95); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    .signup-title {
        font-size: 2rem;
        font-weight: bold;
        color: #3d2c8d;
        margin-bottom: 0.5rem;
        letter-spacing: -1px;
    }
    .signup-subtitle {
        color: #888;
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }
    .signup-card .form-label {
        color: #7c5fe6;
        font-weight: 500;
    }
    .form-control:focus {
        border-color: #7c5fe6;
        box-shadow: 0 0 0 0.2rem rgba(120,90,255,0.10);
    }
    .btn-primary {
        background: linear-gradient(90deg, #7c5fe6 0%, #b8aaff 100%);
        border: none;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 1.1rem;
        transition: background 0.3s, box-shadow 0.3s;
        box-shadow: 0 2px 8px rgba(120,90,255,0.08);
    }
    .btn-primary:hover, .btn-primary:focus {
        background: linear-gradient(90deg, #5f4bb6 0%, #a89cff 100%);
        box-shadow: 0 4px 16px rgba(120,90,255,0.18);
    }
    .social-btn {
        border-radius: 2rem;
        font-weight: 500;
        font-size: 1rem;
        margin: 0 0.25rem;
        padding: 0.5rem 1.2rem;
        border: none;
        color: #fff;
        background: #b8aaff;
        transition: background 0.2s;
    }
    .social-btn.instagram { background: #e1306c; }
    .social-btn.facebook { background: #4267B2; }
    .social-btn:hover { opacity: 0.9; }
    .signup-illustration {
        background: linear-gradient(135deg, #b8aaff 0%, #edeaff 100%);
        border-radius: 2rem 0 0 2rem;
        min-height: 480px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 2rem;
        font-weight: bold;
        position: relative;
        overflow: hidden;
    }
    .signup-illustration .placeholder-3d {
        width: 260px;
        height: 340px;
        background: #a89cff;
        border-radius: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #fff;
        box-shadow: 0 8px 32px rgba(120,90,255,0.10);
    }
    @media (max-width: 991px) {
        .signup-illustration { display: none; }
        .signup-card { border-radius: 2rem; }
    }
</style>
@endsection

@section('content')
<div class="signup-bg">
    <div class="container-fluid signup-container">
        <div class="row w-100 justify-content-center align-items-center">
            <div class="col-lg-6 d-none d-lg-flex flex-column align-items-center justify-content-center">
                <div class="signup-illustration w-100 h-100">
                    <div class="placeholder-3d">
                        <!-- ضع هنا صورة 3D أو SVG لاحقًا -->
                        <i class="bi bi-person-bounding-box" style="font-size:4rem;"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-10 col-12">
                <div class="signup-card mx-auto">
                    <div class="mb-4">
                        <div class="signup-title">مرحبًا بك في نظامنا</div>
                        <div class="signup-subtitle">أنشئ حسابك الجديد بسهولة</div>
                    </div>
                    <form method="POST" action="#">
                        @csrf
                        <div class="row g-2">
                            <div class="col-6 mb-3">
                                <label class="form-label">الاسم الأول</label>
                                <input type="text" class="form-control" name="first_name" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">اسم العائلة</label>
                                <input type="text" class="form-control" name="last_name" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">رقم الجوال</label>
                            <input type="text" class="form-control" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="row g-2">
                            <div class="col-6 mb-3">
                                <label class="form-label">كلمة المرور</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">تأكيد كلمة المرور</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="news" name="news">
                            <label class="form-check-label" for="news">أرغب في تلقي آخر الأخبار والتحديثات</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">أوافق على <a href="#">الشروط وسياسة الخصوصية</a></label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mb-2">إنشاء حساب</button>
                        <div class="d-flex justify-content-center mt-2">
                            <button type="button" class="social-btn instagram mx-1"><i class="bi bi-instagram"></i> Instagram</button>
                            <button type="button" class="social-btn facebook mx-1"><i class="bi bi-facebook"></i> Facebook</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
