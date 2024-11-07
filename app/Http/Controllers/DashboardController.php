<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Define the start and end of the current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Initialize empty data arrays for weekly data
        $salesData = [];
        $reservationsData = [];
        $pendingOrdersData = [];
        $completedOrdersData = [];

        // Define monthly totals for "Pending" and "Completed" orders
        $pendingOrdersCount = 0;
        $completedOrdersCount = 0;

        // Count completed reservations for the entire month
        $completedReservationsCount = DB::table('reservations')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('status', 'Completed')
            ->count();

        // Loop through each week in the month
        for ($week = 1; $week <= 4; $week++) {
            // Define start and end of the week
            $startOfWeek = $startOfMonth->copy()->addWeeks($week - 1)->startOfWeek();
            $endOfWeek = $startOfMonth->copy()->addWeeks($week - 1)->endOfWeek();

            if ($week == Carbon::now()->weekOfMonth) {
                $endOfWeek = Carbon::now();
            }

            // Weekly total sales (Completed orders only)
            $weeklySales = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                                ->where('status', 'Completed')
                                ->sum('total_price');
            $salesData[] = $weeklySales;

            // Weekly "Done" reservations count
            $weeklyReservations = DB::table('reservations')
                ->whereBetween('date', [$startOfWeek, $endOfWeek])
                ->where('status', 'Done')
                ->count();
            $reservationsData[] = $weeklyReservations;

            // Weekly "Pending" orders count
            $pendingOrders = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                                  ->where('status', 'Pending')
                                  ->count();
            $pendingOrdersData[] = $pendingOrders;

            // Weekly "Completed" orders count
            $completedOrders = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                                    ->where('status', 'Completed')
                                    ->count();
            $completedOrdersData[] = $completedOrders;
        }

        // Monthly total for "Pending" orders
        $pendingOrdersCount = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                                    ->where('status', 'Pending')
                                    ->count();

        // Monthly total for "Completed" orders
        $completedOrdersCount = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                                      ->where('status', 'Completed')
                                      ->count();

        return view('admin.dashboard', compact(
            'salesData',
            'reservationsData',
            'pendingOrdersData',
            'completedOrdersData',
            'completedReservationsCount',
            'pendingOrdersCount',
            'completedOrdersCount'
        ));
    }
}
