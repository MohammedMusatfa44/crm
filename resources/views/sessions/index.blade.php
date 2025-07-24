@extends('layouts.layout')

@section('title', 'مراقبة الجلسات')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">الجلسات النشطة</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>IP</th>
                <th>MAC</th>
                <th>الجهاز</th>
                <th>تاريخ الدخول</th>
                <th>تاريخ الخروج</th>
                <th>الحالة</th>
                <th>خيارات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sessions as $session)
            <tr>
                <td>{{ $session->ip_address }}</td>
                <td>{{ $session->mac_address }}</td>
                <td>{{ $session->user_agent }}</td>
                <td>{{ $session->login_at }}</td>
                <td>{{ $session->logout_at }}</td>
                <td>{{ $session->is_active ? 'نشطة' : 'منتهية' }}</td>
                <td>
                    @if($session->is_active)
                    <form method="POST" action="{{ url('sessions/'.$session->id.'/terminate') }}">
                        @csrf
                        <button class="btn btn-danger btn-sm">إنهاء</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
