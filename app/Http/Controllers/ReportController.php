<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Expense;
use App\Models\Medicine;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $preset = $request->get('preset');
        if ($preset === 'today') {
            $dateFrom = Carbon::today()->startOfDay();
            $dateTo   = Carbon::today()->endOfDay();
        } elseif ($preset === 'year') {
            $dateFrom = Carbon::now()->startOfYear();
            $dateTo   = Carbon::now()->endOfYear();
        } elseif ($preset === 'month') {
            $dateFrom = Carbon::now()->startOfMonth();
            $dateTo   = Carbon::now()->endOfMonth();
        } else {
            $dateFrom = $request->date_from ? Carbon::parse($request->date_from)->startOfDay() : Carbon::now()->startOfMonth();
            $dateTo   = $request->date_to   ? Carbon::parse($request->date_to)->endOfDay()   : Carbon::now()->endOfDay();
        }

        $reportType = $request->get('type', 'summary');

        // Summary metrics
        $totalSales     = Sale::whereBetween('created_at', [$dateFrom, $dateTo])->sum('grand_total');
        $totalSalesCount= Sale::whereBetween('created_at', [$dateFrom, $dateTo])->count();
        $totalPurchases = Purchase::whereBetween('created_at', [$dateFrom, $dateTo])->sum('grand_total');
        $totalExpenses  = Expense::whereBetween('expense_date', [$dateFrom->toDateString(), $dateTo->toDateString()])->sum('amount');

        // Accurate Cost of Goods Sold (COGS) calculation
        $cogs = SaleItem::whereHas('sale', function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('created_at', [$dateFrom, $dateTo]);
        })->join('medicines', 'sale_items.medicine_id', '=', 'medicines.id')
          ->sum(DB::raw('sale_items.quantity * medicines.purchase_price'));

        $grossProfit = max(0, $totalSales - $cogs);
        $netProfit   = $grossProfit - $totalExpenses;

        // Sales report data
        $sales = Sale::with(['customer', 'user'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Purchase report data
        $purchases = Purchase::with(['supplier', 'user'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Expense report data
        $expenses = Expense::with('user')
            ->whereBetween('expense_date', [$dateFrom->toDateString(), $dateTo->toDateString()])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Stock report
        $stockReport = Medicine::with('category')
            ->select('medicines.*')
            ->selectRaw('(quantity * purchase_price) as stock_value')
            ->active()
            ->orderBy('quantity')
            ->paginate(20)
            ->withQueryString();

        // Top selling medicines
        $topSelling = SaleItem::with('medicine')
            ->selectRaw('medicine_id, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->whereHas('sale', fn($q) => $q->whereBetween('created_at', [$dateFrom, $dateTo]))
            ->groupBy('medicine_id')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        return view('reports.index', compact(
            'dateFrom', 'dateTo', 'reportType', 'preset',
            'totalSales', 'totalSalesCount', 'totalPurchases',
            'totalExpenses', 'cogs', 'grossProfit', 'netProfit',
            'sales', 'purchases', 'expenses', 'stockReport', 'topSelling'
        ));
    }
}
