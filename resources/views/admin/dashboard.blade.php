@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@push('styles')
<!-- Custom CSS -->
<style>
    /* Overall Styling */
    h2.dashboard-title {
        font-size: 2.5rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;
    }

    /* Card Styling */
    .card {
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s;
        min-height: 220px;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    /* Icon Styling */
    .icon-container {
        padding: 15px;
        background-color: #f1f1f1;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 2.5rem;
    }

    /* Chart Container */
    .chart-container {
        margin-top: 15px;
        height: 180px;
    }

    /* Card Title and Description */
    .card-title {
        font-size: 1.8rem;
        font-weight: bold;
    }

    .card-text {
        font-size: 1.2rem;
        color: #6c757d;
    }

    /* Stats Card Title and Description */
    .stats-card-title {
        font-size: 1.8rem;
        font-weight: bold;
    }

    .stats-card-text {
        font-size: 1.2rem;
        color: #6c757d;
    }

    /* Responsive behavior */
    @media (max-width: 768px) {
        .card {
            min-height: 180px;
        }
        .chart-container {
            height: 150px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <!-- First Row: Monthly Sales and Reservations -->
    <div class="row">
        <!-- Monthly Sales Card -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title">Monthly Sales</h3>
                        <p class="card-text">Sales for this month</p>
                    </div>
                    <div class="icon-container text-success">
                        <span style="font-size: 2.5rem;">₱</span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Reservations Card -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title">Monthly Reservations</h3>
                        <p class="card-text">Reservations for this month</p>
                    </div>
                    <div class="icon-container text-primary">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="reservationsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

<!-- Second Row: Other Stats -->
<div class="row">
    <!-- Completed Reservations Card -->
    <div class="col-lg-4 col-md-12 mb-4">
        <div class="card text-center">
            <div class="icon-container text-info mb-3">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h3 class="stats-card-title">Completed Reservations</h3>
            <p class="stats-card-text">{{ $completedReservationsCount }} completed reservations</p> <!-- Shows the total completed reservations -->
        </div>
    </div>

    <!-- Pending Orders Card -->
    <div class="col-lg-4 col-md-12 mb-4">
        <div class="card text-center">
            <div class="icon-container text-warning mb-3">
                <i class="fas fa-clock"></i>
            </div>
            <h3 class="stats-card-title">Pending Orders</h3>
            <p class="stats-card-text">{{ $pendingOrdersCount }} pending orders</p> <!-- Shows the total pending orders -->
        </div>
    </div>

    <!-- Completed Orders Card -->
    <div class="col-lg-4 col-md-12 mb-4">
        <div class="card text-center">
            <div class="icon-container text-success mb-3">
                <i class="fas fa-check"></i>
            </div>
            <h3 class="stats-card-title">Completed Orders</h3>
            <p class="stats-card-text">{{ $completedOrdersCount }} completed orders</p> <!-- Shows the total completed orders -->
        </div>
    </div>
</div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the sales and reservations data passed from the controller
        var salesData = @json($salesData);
        var reservationsData = @json($reservationsData);

        // Sales chart
        var ctx1 = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Sales',
                    data: salesData,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    fill: true,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Reservations chart (For "Done" reservations)
        var ctx2 = document.getElementById('reservationsChart').getContext('2d');
        var reservationsChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Reservations',
                    data: reservationsData,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    fill: true,
                }]
            }
        });
    });
</script>
@endpush
