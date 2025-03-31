<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
  public function index(Request $request)
  {
    $query = User::where('role', 'user');

    // Thêm điều kiện tìm kiếm nếu có
    if ($request->has('search')) {
      $search = $request->input('search');
      $query->where(function ($q) use ($search) {
        $q->where('name', 'LIKE', "%{$search}%")
          ->orWhere('email', 'LIKE', "%{$search}%")
          ->orWhere('phone', 'LIKE', "%{$search}%");
      });
    }

    // Phân trang và xử lý dữ liệu
    $customers = $query
      ->with(['orders.orderDetails']) // Load danh sách đơn hàng và chi tiết đơn hàng
      ->withCount('orders') // Đếm số đơn hàng của user
      ->orderBy('created_at', 'desc')
      ->paginate(10)
      ->through(function ($user) {
        return [
          'id' => $user->id,
          'name' => $user->name,
          'email' => $user->email,
          'phone' => $user->phone,
          'total_orders' => $user->orders_count,
          'total_spent' => $user->orders->flatMap->orderDetails->sum(fn($detail) => $detail->quantity * $detail->price),
        ];
      });

    return view('customers.index', compact('customers'));
  }

  public function create()
  {
    return view('customers.form', [
      'mode' => 'create',
    ]);
  }

  public function store(Request $request)
  {
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
      'phone' => ['required', 'regex:/^0\d{9,10}$/'],
      'password' => ['required', Rules\Password::defaults(), 'confirmed',],
      'address' => 'nullable|string|max:65535',
      // 'role' => ['required', Rule::in(['user', 'staff', 'admin'])],
      // 'department_id' => 'required|exists:departments,id',
    ], [
      'name.required' => 'Tên nhân viên là bắt buộc.',
      'email.required' => 'Email là bắt buộc.',
      'email.email' => 'Email không đúng định dạng.',
      'email.unique' => 'Email đã tồn tại.',
      'phone.required' => 'Số điện thoại là bắt buộc.',
      'department_id.required' => 'Phòng ban là bắt buộc.',
      'department_id.exists' => 'Phòng ban không tồn tại.',
    ]);

    User::create(array_merge($request->all(), ['role' => 'user']));


    return redirect()->route('customers.index')->with('success', 'Khách hàng đã được thêm thành công.');
  }
  public function edit($id)
  {
    $customer = User::where('role', '=', 'user')->findOrFail($id);
    return view('customers.form', [
      'mode' => 'update',
      'customer' => $customer,
    ]);
  }
  public function update(Request $request, $id)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|max:255|unique:users,email,' . $id,
      'phone' => ['required', 'regex:/^0\d{9,10}$/'],
      'password' => ['nullable', Rules\Password::defaults(), 'confirmed',],
      // 'department_id' => 'required|integer',
    ]);

    $customer = User::findOrFail($id);
    $customer->update($request->except(['password', 'role']));

    // Update password only if provided
    if ($request->filled('password')) {
      $customer->update([
        'password' => Hash::make($request->password),
      ]);
    }

    return redirect()->route('customers.index')->with('success', 'Cập nhật khách hàng thành công!');
  }

  public function destroy($id)
  {
    $customer = User::findOrFail($id);
    try {
      $customer->delete();
    } catch (\Throwable $th) {
      return redirect()->route('customers.index')->with(['error' => 'Không thể xóa!']);
    }
    return redirect()->route('customers.index')->with('success', 'Khách hàng đã bị xóa!');
  }

}
