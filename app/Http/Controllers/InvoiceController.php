<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller {
    public function index() {
        $invoices = Invoice::get();
        return view('invoice.index')->with('invoices', $invoices);
    }
    public function create() {
        return view('invoice.create');
    }
    public function store(Request $request) {
        // Validation cho các trường của invoice
        $validatedData = $request->validate([
            'order_id' => 'required|integer|exists:orders,id', // order_id phải tồn tại trong bảng orders
            'invoice_date' => 'required|date',                // Phải là ngày hợp lệ
            'total_amount' => 'required|numeric|min:0',       // Tổng tiền phải là số >= 0
            'status' => 'required|in:paid,unpaid',            // Chỉ nhận "paid" hoặc "unpaid"
        ], [
            'order_id.required' => 'Mã đơn hàng là bắt buộc.',
            'order_id.integer' => 'Mã đơn hàng phải là số nguyên.',
            'order_id.exists' => 'Mã đơn hàng không tồn tại.',
            'invoice_date.required' => 'Ngày hóa đơn là bắt buộc.',
            'invoice_date.date' => 'Ngày hóa đơn phải là ngày hợp lệ.',
            'total_amount.required' => 'Tổng tiền là bắt buộc.',
            'total_amount.numeric' => 'Tổng tiền phải là số.',
            'total_amount.min' => 'Tổng tiền không được nhỏ hơn 0.',
            'status.required' => 'Trạng thái hóa đơn là bắt buộc.',
            'status.in' => 'Trạng thái hóa đơn chỉ được là "paid" hoặc "unpaid".',
        ]);

        // Sử dụng model để thêm dữ liệu
        Invoice::create([
            'order_id' => $validatedData['order_id'],
            'invoice_date' => $validatedData['invoice_date'],
            'total_amount' => $validatedData['total_amount'],
            'status' => $validatedData['status'],
        ]);

        return redirect()->route('invoice.index')->with('success', 'Hóa đơn đã được thêm thành công.');
    }

    // Hiển thị form chỉnh sửa hóa đơn
    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $orders = Order::all();
        return view('invoice.edit', compact('invoice', 'orders'));
    }

    // Cập nhật hóa đơn
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'invoice_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $invoice = Invoice::findOrFail($id);
        $invoice->update($validatedData);

        return redirect()->route('invoice.index')->with('success', 'Hóa đơn đã được cập nhật.');
    }

    // Xóa hóa đơn
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('invoice.index')->with('success', 'Hóa đơn đã được xóa.');
    }
}



