@extends('layouts.guest')

@section('title', 'تسجيل الدخول')

@section('styles')
<style>
    body, .login-bg {
        background: #eaf1fb;
        min-height: 100vh;
    }
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0;
    }
    .login-card {
        background: #fff;
        box-shadow: 0 8px 32px rgba(11, 88, 202, 0.10);
        padding: 2.5rem 2rem 2rem 2rem;
        max-width: 430px;
        border-radius: 1.2rem;
        width: 100%;
        position: relative;
        z-index: 2;
        animation: fadeInUp 1s cubic-bezier(.39,.575,.56,1.000) both;
        overflow: visible;
        margin: 0 auto;
    }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(40px) scale(0.95); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    .login-title {
        font-size: 2rem;
        font-weight: bold;
        color: #0b58ca;
        margin-bottom: 0.5rem;
        letter-spacing: -1px;
    }
    .login-subtitle {
        color: #888;
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }
    .login-card .form-label {
        color: #0b58ca;
        font-weight: 500;
    }
    .form-control:focus {
        border-color: #0b58ca;
        box-shadow: 0 0 0 0.2rem rgba(11,88,202,0.10);
    }
    .btn-primary {
        background: linear-gradient(90deg, #0b58ca 0%, #3a7be6 100%);
        border: none;
        /* border-radius: 2rem; */
        font-weight: 600;
        font-size: 1.1rem;
        transition: background 0.3s, box-shadow 0.3s;
        box-shadow: 0 2px 8px rgba(11,88,202,0.08);
    }
    .btn-primary:hover, .btn-primary:focus {
        background: linear-gradient(90deg, #094aab 0%, #2561b6 100%);
        box-shadow: 0 4px 16px rgba(11,88,202,0.18);
    }
    .login-photo-loop-right {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 350px;
    }
    .login-photo-loop {
        position: relative;
        width: 600px;
        height: 430px;
        margin-bottom: 1.5rem;
    }
    .login-photo {
        position: absolute;
        width: 100%;
        height: 100%;
        /* border-radius: 50%; */
        opacity: 0;
        /* transition: opacity 0.4s, filter 0.4s; */
        /* filter: brightness(1.2) drop-shadow(0 2px 8px #0b58ca33); */
        /* object-fit: cover; */
    }
 .login-photo.active {
        opacity: 1;
        transition: transform 3.2s cubic-bezier(.39,.575,.56,1.000), opacity 0.4s;
    }
    /* Keyframes for movement directions */
    @keyframes move-top { 0% {transform: translateY(0);} 100% {transform: translateY(-40px);} }
    @keyframes move-bottom { 0% {transform: translateY(0);} 100% {transform: translateY(40px);} }
    @keyframes move-left { 0% {transform: translateX(0);} 100% {transform: translateX(-40px);} }
    @keyframes move-right { 0% {transform: translateX(0);} 100% {transform: translateX(40px);} }
    @media (max-width: 991px) {
        .login-photo-loop-right {
            display: flex !important;
            flex-direction: row;
            justify-content: center;
            align-items: flex-end;
            min-height: unset;
            margin-bottom: 1.5rem;
        }
        .login-photo-loop {
            width: 400px;
            height: 400px;
            margin-bottom: 0;
        }
        .login-photo {
            width: 100%;
    height: 100%;
        }
        .login-card {
            /* border-radius: 1.2rem; */
            margin-bottom: 2rem;
        }
    }
    @media (max-width: 767px) {
        .login-container {
            flex-direction: column;
            padding: 1rem 0;
        }
        .login-card {
            max-width: 98vw;
            padding: 2rem 0.5rem 1.5rem 0.5rem;
            margin: 10px !important
        }
        .login-photo-loop-right {
            margin-bottom: 1.5rem;
        }
    }
</style>
<!-- Responsive meta tag for mobile scaling -->
<meta name="viewport" content="width=device-width, initial-scale=1">
@endsection

@section('content')
<div class="login-bg">
    <div class="container-fluid login-container">
        <div class="row w-100 justify-content-center align-items-center flex-lg-row flex-column-reverse g-0">
            <div class="col-lg-6 col-md-10 col-12 order-lg-1 order-2 d-flex align-items-center justify-content-center">
                <div class="login-card mx-auto">
                    <div class="mb-4">
                        <div class="login-title">تسجيل الدخول</div>
                        <div class="login-subtitle">أدخل بياناتك للمتابعة</div>
                    </div>
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <form method="POST" action="{{ url('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">اسم المستخدم أو البريد الإلكتروني</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2">دخول</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 d-flex flex-column align-items-center justify-content-center order-lg-2 order-1 login-photo-loop-right">
                <div class="login-photo-loop">
                    <img src="/images/1.webp" class="login-photo active img-fluid" alt="login photo">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const photo = document.querySelector('.login-photo.active');
    const maxX = 60; // px
    const maxY = 60; // px
    let lastX = 0, lastY = 0;
    function randomMove() {
        // Pick a random direction and distance
        let x = Math.floor(Math.random() * (maxX * 2 + 1)) - maxX;
        let y = Math.floor(Math.random() * (maxY * 2 + 1)) - maxY;
        // Avoid repeating the same position
        if (x === lastX && y === lastY) {
            x = -x;
            y = -y;
        }
        lastX = x;
        lastY = y;
        photo.style.transform = `translate(${x}px, ${y}px)`;
    }
    setInterval(randomMove, 1800);
});
</script>
@endsection
