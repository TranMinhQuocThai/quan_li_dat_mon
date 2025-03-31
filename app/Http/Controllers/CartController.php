<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FoodItem;
use App\Models\Cart;
use App\Models\OnlineOrder;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->get();
        $total = $cart->sum(fn($item) => $item->price * $item->quantity);

        // Lấy danh sách ID món ăn trong giỏ hàng
        $foodIds = $cart->pluck('food_item_id');

        // Lấy danh sách món gợi ý cùng loại (trừ những món đã có trong giỏ hàng)
        $suggestedFoods = FoodItem::whereNotIn('id', $foodIds)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('cart.index', compact('cart', 'total', 'suggestedFoods'));
    }

    public function add(Request $request, $id)
    {
        $foodItem = FoodItem::findOrFail($id);
        $userId = Auth::id();

        $cartItem = Cart::where('user_id', $userId)->where('food_item_id', $id)->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'food_item_id' => $id,
                'price' => $foodItem->price,
                'quantity' => 1,
            ]);
        }

        if ($request->ajax()) {
            return response()->json(['success' => 'Món ăn đã được thêm vào giỏ hàng!']);
        }

        return redirect()->route('cart.index')->with('success', 'Món ăn đã được thêm vào giỏ hàng!');
    }

    public function update(Request $request, $id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->where('food_item_id', $id)->first();
        if ($cartItem) {
            $cartItem->quantity = $request->quantity;
            $cartItem->save();
        }
        return redirect()->route('cart.index');
    }

    public function remove($id)
    {
        Cart::where('user_id', Auth::id())->where('food_item_id', $id)->delete();
        return redirect()->route('cart.index');
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        return redirect()->route('cart.index');
    }

    public function checkout()
    {
        $cart = Cart::where('user_id', auth()->id())->get();

        $total = collect($cart)->sum(fn($item) => $item->price * $item->quantity);

        $orders = OnlineOrder::where('user_id', auth()->id())->latest()->get();

        return view('cart.checkout', compact('cart', 'total', 'orders'));
    }

}
