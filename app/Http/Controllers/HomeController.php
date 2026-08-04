<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date') ?? ($startDate ? now()->format('Y-m-d') : null);

        // Base Query
        $query = Order::query();

        // Date Filter
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ]);
        }

        // Collection fetch (Single Main Query)
        $orders = $query->get();

        // Totals & Status Metrics
        $totalOrders      = $orders->count(); // Fixed: Ab filter ke mutabiq total count aayega

        $deliveredOrders  = $orders->where('status', 'delivered')->count();
        $processingOrders = $orders->where('status', 'processing')->count();
        $pendingOrders    = $orders->where('status', 'pending')->count();
        $cancelledOrders  = $orders->where('status', 'cancelled')->count();

        $deliveredAmount  = $orders->where('status', 'delivered')->sum('total');
        $processingAmount = $orders->where('status', 'processing')->sum('total');
        $pendingAmount    = $orders->where('status', 'pending')->sum('total');
        $cancelledAmount  = $orders->where('status', 'cancelled')->sum('total');

        // 📊 CHART DATA 1: Daily Trend (Last 30 Days)
        $dailyData = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(total) as total_revenue')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $dailyLabels  = $dailyData->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'));
        $dailyOrders  = $dailyData->pluck('total_orders');
        $dailyRevenue = $dailyData->pluck('total_revenue');

        // 📊 CHART DATA 2: Monthly Trend (Last 12 Months)
        $monthlyData = Order::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(total) as total_revenue')
        )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get();

        $monthlyLabels  = $monthlyData->pluck('month')->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->format('M Y'));
        $monthlyOrders  = $monthlyData->pluck('total_orders');
        $monthlyRevenue = $monthlyData->pluck('total_revenue');

        return view('home', compact(
            'startDate',
            'endDate',
            'totalOrders',
            'deliveredOrders',
            'processingOrders',
            'pendingOrders',
            'cancelledOrders',
            'deliveredAmount',
            'processingAmount',
            'pendingAmount',
            'cancelledAmount',
            'dailyLabels',
            'dailyOrders',
            'dailyRevenue',
            'monthlyLabels',
            'monthlyOrders',
            'monthlyRevenue'
        ));
    }
}
