@extends('layouts.layout')

@section('title', 'تقارير العملاء')

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
    .stat-card {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
        color: #fff;
        border: none;
        border-radius: 1.2rem;
        box-shadow: 0 4px 24px rgba(33,150,243,0.10);
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
        min-height: 110px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        padding: 1.2rem 1.5rem;
    }
    .stat-card .stat-label {
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 0.3rem;
        opacity: 0.9;
    }
    .stat-card .stat-value {
        font-size: 2.2rem;
        font-weight: bold;
        opacity: 0.95;
    }
    .stat-card.no-answer {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
    }
    .stat-card.hot {
        background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
    }
    .stat-card.western {
        background: linear-gradient(135deg, #ffa726 0%, #ffcc02 100%);
    }
    .stat-card.follow {
        background: linear-gradient(135deg, #7e57c2 0%, #9c27b0 100%);
    }
    .stat-card.deposits {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
    }
    .stat-card.not-interested {
        background: linear-gradient(135deg, #9e9e9e 0%, #757575 100%);
    }
    .stat-card.no-answer2 {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
    }
    .stat-card.no-answer1 {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
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
    .back-btn {
        background: #6c757d;
        color: #fff;
        border: none;
        border-radius: 1.2rem;
        padding: 0.5rem 1.2rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .back-btn:hover {
        background: #5a6268;
        color: #fff;
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div class="dashboard-bg">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="dashboard-title">تقارير العملاء</h1>
                        <p class="dashboard-subtitle">إحصائيات وتقارير شاملة لجميع العملاء</p>
                    </div>
                    <div class="top-actions">
                        <a href="{{ route('customers.index') }}" class="btn back-btn">
                            <i class="fas fa-arrow-right"></i> العودة للعملاء
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4 g-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-label">إجمالي العملاء</div>
                    <div class="stat-value">{{ $totalCustomers }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-label">الحالات الجديدة</div>
                    <div class="stat-value">{{ $newCustomers }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-label">الحالات النشطة</div>
                    <div class="stat-value">{{ $activeCustomers }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-label">الحالات المغلقة</div>
                    <div class="stat-value">{{ $closedCustomers }}</div>
                </div>
            </div>
        </div>

        <!-- Reports Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="modern-table">
                    <div class="table-header">التقارير حسب الحالة</div>
                    <div class="p-4">
                        <div class="row g-3">
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-card no-answer">
                                    <div class="stat-label">No answer</div>
                                    <div class="stat-value">{{ $statusCounts['no_answer'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-card hot">
                                    <div class="stat-label">Hot</div>
                                    <div class="stat-value">{{ $statusCounts['hot'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-card western">
                                    <div class="stat-label">Western</div>
                                    <div class="stat-value">{{ $statusCounts['western'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-card follow">
                                    <div class="stat-label">Follow</div>
                                    <div class="stat-value">{{ $statusCounts['follow'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-card deposits">
                                    <div class="stat-label">Deposits</div>
                                    <div class="stat-value">{{ $statusCounts['deposits'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-card not-interested">
                                    <div class="stat-label">Not interested</div>
                                    <div class="stat-value">{{ $statusCounts['not_interested'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-card no-answer2">
                                    <div class="stat-label">No answer2</div>
                                    <div class="stat-value">{{ $statusCounts['no_answer2'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-card no-answer1">
                                    <div class="stat-label">No answer1</div>
                                    <div class="stat-value">{{ $statusCounts['no_answer1'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
