<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OnlineOrderItem;
use App\Models\OnlineOrder;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Thêm dòng này để debug

class OnlineOrderController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function index()
    {
        $status = request('status');

        // Lấy danh sách đơn hàng, tính tổng tiền và kèm theo danh sách món ăn
        $onlineOrders = OnlineOrder::when($status, function ($query, $status) {
            return $query->where('status', $status);
        })
            ->with(['user', 'items.food']) // Lấy danh sách món ăn kèm theo thông tin từ bảng foods
            ->withSum('items', 'price') // Tính tổng tiền từ bảng items
            ->latest()
            ->paginate(10);

        return view('online_orders.index', compact('onlineOrders'));
    }




    public function getOrderItems($id)
    {
        $items = OnlineOrderItem::where('order_id', $id)->get(['name', 'quantity', 'price']);

        return response()->json($items);
    }



    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'address' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $userId = Auth::id();

            // Kiểm tra giỏ hàng
            $cartItems = Cart::where('user_id', $userId)->get();
            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Giỏ hàng trống, không thể đặt hàng!');
            }

            // Debug: Kiểm tra dữ liệu đầu vào
            Log::info('User đặt hàng:', ['user_id' => $userId, 'cartItems' => $cartItems]);

            // Tạo đơn hàng mới
            $order = OnlineOrder::create([
                'user_id' => $userId,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => 'chờ xác nhận',
                'paid' => false,
            ]);

            Log::info('Đơn hàng đã tạo:', ['order_id' => $order->id]);

            // Thêm món ăn vào bảng `online_orders_items`
            foreach ($cartItems as $item) {
                OnlineOrderItem::create([
                    'order_id' => $order->id,
                    'food_item_id' => $item->food_item_id, // Giả sử cột `food_id` tồn tại trong bảng carts
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }

            Log::info('Thêm món ăn thành công vào đơn hàng.');

            // Xóa giỏ hàng sau khi đặt hàng
            Cart::where('user_id', $userId)->delete();
            Log::info('Xóa giỏ hàng thành công.');

            DB::commit();
            return redirect()->route('cart.processCheckout')->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi đặt hàng: ' . $e->getMessage());
            return back()->with('error', 'Đã xảy ra lỗi khi đặt hàng!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = OnlineOrder::with('items.food')->findOrFail($id);
        return view('online_orders.show', compact('order'));
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'reason' => 'required_if:status,không nhận|string|nullable',
        ], [
            'reason.required_if' => 'Vui lòng nhập lý do khi từ chối đơn hàng!',
        ]);

        $order = OnlineOrder::findOrFail($id);
        $order->status = $request->status;
        $order->reason = $request->status === 'không nhận' ? $request->reason : null;
        $order->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function cancel($id)
    {
        $order = OnlineOrder::find($id);

        if (!$order) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại.');
        }

        // Ensure the authenticated user owns this order
        if (auth()->id() !== $order->user_id) {
            return redirect()->back()->with('error', 'Bạn không có quyền hủy đơn hàng này.');
        }

        // Check if the order is in a cancelable status
        if ($order->status !== 'chờ xác nhận') {
            return redirect()->back()->with('error', 'Chỉ có thể hủy đơn hàng khi đang chờ xác nhận.');
        }

        $order->status = 'đã hủy';
        $order->save();

        return redirect()->back()->with('success', 'Đơn hàng đã bị hủy thành công.');
    }

}
