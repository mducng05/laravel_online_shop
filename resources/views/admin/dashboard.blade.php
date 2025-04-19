@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">                    
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6">
                
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-4 col-6">                            
                <div class="small-box card">
                    <div class="inner">
                        <h3>{{$totalOrders}}</h3>
                        <p>Total Orders</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{route('orders.index')}}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            
            <div class="col-lg-4 col-6">                            
                <div class="small-box card">
                    <div class="inner">
                        <h3>{{$totalProduct}}</h3>
                        <p>Total Products</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{route('products.index')}}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-6">                            
                <div class="small-box card">
                    <div class="inner">
                        <h3>{{$totalCustomers}}</h3>
                        <p>Total Customer</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{route('users.index')}}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            
            <div class="col-lg-4 col-6">                            
                <div class="small-box card">
                    <div class="inner">
                        <h3>${{number_format($totalRevenue,2)}}</h3>
                        <p>Total Sale</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
                </div>
            </div>

<!--             <div class="col-lg-4 col-6">                           
                <div class="small-box card">
                    <div class="inner">
                        <h3>${{number_format($revenueThisMonth,2)}}</h3>
                        <p>Revenue this month</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
                </div>
            </div> -->

<!--             <div class="col-lg-4 col-6">                           
                <div class="small-box card">
                    <div class="inner">
                        <h3>${{number_format($revenueLastMonth,2)}}</h3>
                        <p>Revenue last month ({{$lastMonthName}})</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
                </div>
            </div> -->

            <div class="col-lg-4 col-6">                            
                <div class="small-box card">
                    <div class="inner">
                        <h3>${{number_format($revenueLastThirtyDays,2)}}</h3>
                        <p>Revenue last 30 days</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
                </div>
            </div>

                <div class="col-md-6">
                    <h4>Tổng doanh thu tháng này: {{ number_format($revenueThisMonth, 2) }} VNĐ</h4>
                </div>
                <div class="col-md-6">
                    <h4>Tổng doanh thu tháng trước: {{ number_format($revenueLastMonth, 2) }} VNĐ</h4>
                </div>


            <canvas id="revenueChart" width="500" height="300" style="max-height: 400px;"></canvas>
        </div>
    </div>                  
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script src="{{ asset('vendor/numeral.min.js') }}"></script>
<script src="{{ asset('vendor/Chartjs/Chart.min.js') }}"></script>
<script src="{{ asset('vendor/Chartjs/chart.js') }}"></script>
<script>
        var ctx = document.getElementById('revenueChart').getContext('2d');
        var revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Doanh thu tháng này', 'Doanh thu tháng trước', 'Doanh thu 30 ngày qua'],
                datasets: [{
                    label: 'Doanh thu',
                    data: [{{ $revenueThisMonth }}, {{ $revenueLastMonth }}, {{ $revenueLastThirtyDays }}],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Màu nền cho tất cả
                    borderColor: 'rgba(54, 162, 235, 1)', // Màu viền cho tất cả
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)', // Màu điểm
                    pointBorderColor: '#fff', // Màu viền điểm
                    pointBorderWidth: 2,
                    pointRadius: 5, // Kích thước điểm
                    fill: true // Để làm cho biểu đồ có màu nền
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Thời gian',
                            font: {
                                size: 16 // Kích thước chữ tiêu đề
                            }
                        },
                        grid: {
                            display: false // Ẩn grid trên trục x
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Doanh thu (VNĐ)',
                            font: {
                                size: 16 // Kích thước chữ tiêu đề
                            }
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('vi-VN') + ' VNĐ'; // Định dạng tiền tệ
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)', // Màu grid
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.formattedValue.toLocaleString('vi-VN') + ' VNĐ'; // Định dạng tooltip
                            }
                        }
                    }
                },
                // Thêm tiêu đề cho biểu đồ
                elements: {
                    line: {
                        tension: 0.4 // Độ cong cho đường biểu đồ
                    }
                }
            }
        });

    </script>
@endsection