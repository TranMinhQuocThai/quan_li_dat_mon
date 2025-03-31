<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\FoodType;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class RevenueStatisticsController extends Controller
{
    public function index(Request $request)
    {
        $type = urldecode($request->query('type', 'week'));
        $date = urldecode($request->query('date', ''));
        $foodTypeId = $request->query('foodTypeId');
        $selectedDate = Carbon::hasFormat($date, 'Y-m-d') ?
            Carbon::parse($date) : Carbon::now();

        if ($type == 'tháng') {
            return $this->getMonthlyRevenue($selectedDate, $foodTypeId);
        } elseif ($type == 'năm') {
            return $this->getYearlyRevenue($selectedDate, $foodTypeId);
        }
        return $this->getWeeklyRevenue($selectedDate, $foodTypeId);
    }

    private function getWeeklyRevenue(Carbon $date, $foodTypeId)
    {
        $startOfWeek = $date->copy()->startOfWeek();
        $endOfWeek = $date->copy()->endOfWeek();

        $inStoreOrders = $this->getRevenueQuery($startOfWeek, $endOfWeek, $foodTypeId)
            ->select(
                DB::raw('DATE(orders.created_at) as day'),
                DB::raw('SUM(order_details.quantity * order_details.price) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as total_orders')
            )
            ->groupBy('day')
            ->orderBy('day')
            ->get();
        $onlineOrders = $this->getOnlineRevenueQuery($startOfWeek, $endOfWeek, $foodTypeId)
            ->select(
                DB::raw('DATE(online_orders.created_at) as day'),
                DB::raw('SUM(online_orders_items.quantity * online_orders_items.price) as total_revenue'),
                DB::raw('COUNT(DISTINCT online_orders.id) as total_orders')
            )
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $weekLabels = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'];
        $labels = [];
        $inStoreRevenueData = [];
        $onlineRevenueData = [];
        $inStoreOrderCountData = [];
        $onlineOrderCountData = [];

        $topItems = $this->getTopItems($startOfWeek, $endOfWeek, $foodTypeId);

        for ($date = $startOfWeek; $date <= $endOfWeek; $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');
            $dayIndex = $date->dayOfWeekIso - 1; // Monday = 1, Sunday = 7

            $labels[] = $weekLabels[$dayIndex];

            // Find data for in-store orders
            $inStoreData = $inStoreOrders->firstWhere('day', $formattedDate);
            $inStoreRevenueData[] = $inStoreData ? $inStoreData->total_revenue : 0;
            // $inStoreOrderCountData[] = $inStoreData ? $inStoreData->total_orders : 0;

            // Find data for online orders
            $onlineData = $onlineOrders->firstWhere('day', $formattedDate);
            $onlineRevenueData[] = $onlineData ? $onlineData->total_revenue : 0;
            // $onlineOrderCountData[] = $onlineData ? $onlineData->total_orders : 0;
        }
        $foodTypes = FoodType::get();

        return view('statistics.index', compact('labels', 'inStoreRevenueData', 'onlineRevenueData', 'foodTypes', 'topItems'));
        // return response()->json(['labels' => $labels, 'data' => $data]);
    }

    private function getMonthlyRevenue(Carbon $date, $foodTypeId)
    {
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $inStoreOrders = $this->getRevenueQuery($startOfMonth, $endOfMonth, $foodTypeId)
            ->select(
                DB::raw('(1 + FLOOR((DAY(orders.created_at) - 1) / 7)) as week_number'),
                DB::raw('SUM(order_details.price * order_details.quantity) as total_revenue'),
            )
            ->groupBy('week_number')
            ->get();
        $onlineOrders = $this->getOnlineRevenueQuery($startOfMonth, $endOfMonth, $foodTypeId)
            ->select(
                DB::raw('(1 + FLOOR((DAY(online_orders.created_at) - 1) / 7)) as week_number'),
                DB::raw('SUM(online_orders_items.price * online_orders_items.quantity) as total_revenue'),
            )
            ->groupBy('week_number')
            ->get();

        $weekCount = ceil($startOfMonth->daysInMonth / 7);
        $labels = [];
        $inStoreRevenueData = [];
        $onlineRevenueData = [];
        $inStoreOrderCountData = [];
        $onlineOrderCountData = [];

        $topItems = $this->getTopItems($startOfMonth, $endOfMonth, $foodTypeId);

        for ($week = 1; $week <= $weekCount; $week++) {
            $labels[] = "Tuần $week";

            // Find data for in-store orders
            $inStoreData = $inStoreOrders->firstWhere('week_number', '=', $week);
            $inStoreRevenueData[] = $inStoreData ? $inStoreData->total_revenue : 0;
            // $inStoreOrderCountData[] = $inStoreData ? $inStoreData->total_orders : 0;

            // Find data for online orders
            $onlineData = $onlineOrders->firstWhere('week_number', '=', $week);
            $onlineRevenueData[] = $onlineData ? $onlineData->total_revenue : 0;
            // $onlineOrderCountData[] = $onlineData ? $onlineData->total_orders : 0;
        }
        $foodTypes = FoodType::get();

        return view('statistics.index', compact('labels', 'inStoreRevenueData', 'onlineRevenueData', 'foodTypes', 'topItems'));
        // return response()->json(['labels' => $labels, 'data' => $data]);
    }

    private function getYearlyRevenue(Carbon $date, $foodTypeId = null)
    {
        $startOfYear = $date->copy()->startOfYear();
        $endOfYear = $date->copy()->endOfYear();

        $inStoreOrders = $this->getRevenueQuery($startOfYear, $endOfYear, $foodTypeId)
            ->select(
                DB::raw('MONTH(orders.created_at) as month'),
                DB::raw('SUM(order_details.price * order_details.quantity) as total_revenue')
            )
            ->groupBy('month')
            ->get();

        $onlineOrders = $this->getOnlineRevenueQuery($startOfYear, $endOfYear, $foodTypeId)
            ->select(
                DB::raw('MONTH(online_orders.created_at) as month'),
                DB::raw('SUM(online_orders_items.price * online_orders_items.quantity) as total_revenue')
            )
            ->groupBy('month')
            ->get();

        $labels = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
        $inStoreRevenueData = [];
        $onlineRevenueData = [];
        $inStoreOrderCountData = [];
        $onlineOrderCountData = [];

        $topItems = $this->getTopItems($startOfYear, $endOfYear, $foodTypeId);

        for ($month = 1; $month <= 12; $month++) {
            // Find data for in-store orders
            $inStoreData = $inStoreOrders->firstWhere('month', $month);
            $inStoreRevenueData[] = $inStoreData ? $inStoreData->total_revenue : 0;
            // $inStoreOrderCountData[] = $inStoreData ? $inStoreData->total_orders : 0;

            // Find data for online orders
            $onlineData = $onlineOrders->firstWhere('month', $month);
            $onlineRevenueData[] = $onlineData ? $onlineData->total_revenue : 0;
            // $onlineOrderCountData[] = $onlineData ? $onlineData->total_orders : 0;
        }
        $foodTypes = FoodType::get();

        return view('statistics.index', compact('labels', 'inStoreRevenueData', 'onlineRevenueData', 'foodTypes', 'topItems'));
        // return response()->json(['labels' => $labels, 'data' => $data]);
    }

    public function getRevenueQuery(Carbon $startDate, Carbon $endDate, $foodTypeId)
    {
        return
            DB::table('orders')
                ->where('paid', true)
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->join('food_items', 'order_details.food_item_id', '=', 'food_items.id')
                ->when($foodTypeId, function ($query) use ($foodTypeId) {
                    return $query->where('food_items.food_type_id', '=', $foodTypeId);
                });
    }

    public function getOnlineRevenueQuery(Carbon $startDate, Carbon $endDate, $foodTypeId)
    {
        return
            DB::table('online_orders')
                ->where('paid', true)
                ->whereBetween('online_orders.created_at', [$startDate, $endDate])
                ->join('online_orders_items', 'online_orders.id', '=', 'online_orders_items.order_id')
                ->join('food_items', 'online_orders_items.food_item_id', '=', 'food_items.id')
                ->when($foodTypeId, function ($query) use ($foodTypeId) {
                    return $query->where('food_items.food_type_id', '=', $foodTypeId);
                });
    }
    public function getTopItems(Carbon $startDate, Carbon $endDate, $foodTypeId)
    {
        $inStoreQuery = DB::table('order_details')
            ->select(
                'food_items.id',
                'food_items.name',
                'food_items.image',
                'food_items.price',
                DB::raw('SUM(order_details.quantity) as sold_in_store'),
                DB::raw('0 as sold_online'), // Placeholder for merging
                DB::raw('SUM(order_details.quantity * food_items.price) as total_revenue')
            )
            ->join('food_items', 'order_details.food_item_id', '=', 'food_items.id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->when($foodTypeId, function ($query) use ($foodTypeId) {
                return $query->where('food_items.food_type_id', $foodTypeId);
            })
            ->groupBy('food_items.id', 'food_items.name', 'food_items.image', 'food_items.price');

        $onlineQuery = DB::table('online_orders_items')
            ->select(
                'food_items.id',
                'food_items.name',
                'food_items.image',
                'food_items.price',
                DB::raw('0 as sold_in_store'), // Placeholder for merging
                DB::raw('SUM(online_orders_items.quantity) as sold_online'),
                DB::raw('SUM(online_orders_items.quantity * food_items.price) as total_revenue')
            )
            ->join('food_items', 'online_orders_items.food_item_id', '=', 'food_items.id')
            ->join('online_orders', 'online_orders_items.order_id', '=', 'online_orders.id')
            ->whereBetween('online_orders.created_at', [$startDate, $endDate])
            ->when($foodTypeId, function ($query) use ($foodTypeId) {
                return $query->where('food_items.food_type_id', $foodTypeId);
            })
            ->groupBy('food_items.id', 'food_items.name', 'food_items.image', 'food_items.price');

        return DB::table(DB::raw("({$inStoreQuery->toSql()} UNION ALL {$onlineQuery->toSql()}) as combined"))
            ->mergeBindings($inStoreQuery)
            ->mergeBindings($onlineQuery)
            ->select(
                'id',
                'name',
                'image',
                'price',
                DB::raw('SUM(sold_in_store) as sold_in_store'),
                DB::raw('SUM(sold_online) as sold_online'),
                DB::raw('SUM(total_revenue) as total_revenue'),
                DB::raw('SUM(sold_in_store) + SUM(sold_online) as total_sold')
            )
            ->groupBy('id', 'name', 'image', 'price')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();
    }
}
