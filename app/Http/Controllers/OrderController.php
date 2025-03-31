<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'table'])->latest();
        $paid = $request->has('paid') ? urldecode(request('paid')) : null;
        if ($paid) {
            $paid = $paid == 'true';
            $query->where('paid', $paid);
        }
        $orders = $query->paginate(10)->appends($request->query());
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', '=', 'user')->get();
        $tables = Table::all();
        $mode = 'create';
        return view('orders.form', compact('mode', 'users', 'tables'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'table_id' => 'required|exists:tables,id',
            'discount' => 'numeric|min:0|max:100',
        ]);
        $tables = Table::where('id', $request->table_id)->first();
        if ($tables->status == 'có khách') {
            return redirect()->route('orders.index')->with('error', 'Bàn đã có khách.');
        }
        Order::create([
            'user_id' => $request->user_id,
            'table_id' => $request->table_id,
            'discount' => $request->discount,
        ]);
        $tables->update([
            'status' => 'có khách',
        ]);
        return redirect()->route('orders.index')->with('success', 'Tạo đơn thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $users = User::where('role', '=', 'user')->get();
        $tables = Table::all();
        $foodItems = FoodItem::all();
        return view('orders.show', compact('order', 'users', 'tables', 'foodItems'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $users = User::where('role', '=', 'user')->get();
        $tables = Table::all();
        $mode = 'update';
        return view('orders.form', compact('mode', 'order', 'users', 'tables'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'table_id' => 'required|exists:tables,id',
            // 'status' => 'required|in:đang ăn,đã ăn,đã thanh toán',
            'discount' => 'numeric|min:0',
        ]);
        $allServed = $order->orderDetails()->where('status', '!=', 'đã ra')->doesntExist();
        $paid = $request->has('paid');
        if (!$allServed && $paid) {
            return redirect()->back()
                ->with('error', "Không thể thanh toán, có món chưa được phục vụ.");
        }
        $order->update([
            'paid' => $paid,
            // 'status' => $request->input('status'),
            ...$request->all(),
        ]);
        if ($paid) {
            Table::where('id', $order->table_id)->update([
                'status' => 'trống',
            ]);
        }

        return redirect()->back()->with('success', 'Cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            $order->delete();
            Table::where('id', $order->table_id)->update([
                'status' => 'trống',
            ]);
            return redirect()->route('orders.index')->with('success', 'Đã xóa.');
        } catch (\Exception $e) {
            return redirect()->route('orders.index')->with('error', 'Lỗi xóa đơn.');
        }
    }

    public function updatePaid(Request $request, Order $order)
    {
        $allServed = $order->orderDetails()->where('status', '!=', 'đã ra')->doesntExist();
        $paid = $request->has('paid');
        if (!$allServed && $paid) {
            return redirect()->route('orders.index')->with('error', 'Không thể thanh toán, có món chưa được phục vụ.');
        }
        $order->update(['paid' => $paid]);
        if ($paid) {
            Table::where('id', $order->table_id)->update([
                'status' => 'trống',
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Cập nhật thanh toán thành công.');
    }

    public function addOrderDetail(Request $request, Order $order)
    {
        $request->validate([
            'food_item_id' => 'required|exists:food_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($order->paid) {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Khách đã thanh toán, không thể thêm');
        }

        $insufficientIngredients = [];
        $foodItem = FoodItem::with('ingredients')->findOrFail($request->food_item_id);
        foreach ($foodItem->ingredients as $ingredient) {
            $requiredQuantity = $ingredient->pivot->quantity * $request->quantity; // Adjust for order quantity
            if ($ingredient->quantity < $requiredQuantity) {
                $insufficientIngredients[] = [
                    'name' => $ingredient->name,
                    'in_stock' => $ingredient->quantity,
                    'required' => $requiredQuantity,
                    'shortage' => $requiredQuantity - $ingredient->quantity,
                ];
                // return redirect()->route('orders.show', $order->id)
                //     ->with('error', "Không đủ nguyên liệu: {$ingredient->name}");
            } else {
                $ingredient->decrement('quantity', $requiredQuantity);
            }
        }

        if (!empty($insufficientIngredients)) {
            $message = "Không đủ nguyên liệu:<br>";
            foreach ($insufficientIngredients as $item) {
                $message .= " {$item['name']}: còn {$item['in_stock']}, cần {$item['required']}, thiếu {$item['shortage']}<br>";
            }
        
            return redirect()->route('orders.show', $order->id)->with('error', $message);
        }

        OrderDetail::create([
            'order_id' => $order->id,
            'food_item_id' => $request->food_item_id,
            'quantity' => $request->quantity,
            'price' => $foodItem->price,
        ]);

        return redirect()->route('orders.show', $order->id)->with('success', 'Món ăn đã được thêm.');
    }


    public function updateOrderDetailStatus(Request $request, OrderDetail $orderDetail)
    {
        $request->validate([
            'status' => 'required|in:chuẩn bị,đã nấu,đã ra,đã hủy',
        ]);

        $orderDetail->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Cập nhật thành công');
    }

    public function removeOrderDetail(OrderDetail $orderDetail)
    {
        if ($orderDetail->status != 'chuẩn bị') {
            return redirect()->back()->with('error', 'Không thể xóa vì món ' . $orderDetail->status);
        }
        $foodItem = $orderDetail->foodItem;
        foreach ($foodItem->ingredients as $ingredient) {
            $usedQuantity = $ingredient->pivot->quantity;
            $ingredient->increment('quantity', $usedQuantity * $orderDetail->quantity);
        }
        $orderDetail->delete();

        return redirect()->back()->with('success', 'Món đã được xóa.');
    }
}
