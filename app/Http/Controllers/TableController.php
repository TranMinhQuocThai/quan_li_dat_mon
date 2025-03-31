<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TableController extends Controller {
  public function index() {
    $tables = Table::paginate(10);
    return view('tables.index')->with('tables', $tables);
  }

  public function create() {
    return view('tables.form', [
      'mode' => 'create',
    ]);
  }

  public function store(Request $request) {
    // Validation cho các trường 'name' và 'seats'
    $validatedData = $request->validate([
      'name' => 'required|string|max:255',
      'seats' => 'required|integer|min:1|max:100',
    ], [
      'name.required' => 'Tên là bắt buộc.',
      'name.string' => 'Tên phải là chuỗi ký tự.',
      'name.max' => 'Tên không được vượt quá 255 ký tự.',
      'seats.required' => 'Số ghế là bắt buộc.',
      'seats.integer' => 'Số ghế phải là một số nguyên.',
      'seats.min' => 'Số ghế phải ít nhất là 1.',
      'seats.max' => 'Số ghế không được vượt quá 100.',
    ]);

    Table::create([
      'name' => $validatedData['name'],
      'seats' => $validatedData['seats'],
    ]);

    return redirect()->route('tables.index')->with('success', 'Đặt bàn đã được thêm thành công.');
  }

  public function edit(Table $table) {
    return view('tables.form', [
      'mode' => 'update',
      'table' => $table,
    ]);
  }

  public function update(Request $request, Table $table) {
    $request->validate([
      'name' => 'required|string|max:255',
      'seats' => 'required|integer|min:1|max:100',
    ], [
      'name.required' => 'Tên là bắt buộc.',
      'name.string' => 'Tên phải là chuỗi ký tự.',
      'name.max' => 'Tên không được vượt quá 255 ký tự.',
      'seats.required' => 'Số ghế là bắt buộc.',
      'seats.integer' => 'Số ghế phải là một số nguyên.',
      'seats.min' => 'Số ghế phải ít nhất là 1.',
      'seats.max' => 'Số ghế không được vượt quá 100.',
    ]);

    $table->update([
      'name' => $request->input('name'),
      'seats' => $request->input('seats'),
    ]);

    return redirect()->route('tables.index')->with('success', 'Cập nhật thông tin thành công!');
  }

  public function renderDelete($id) {
    $table = Table::findOrFail($id);
    return view('tables.form', [
      'mode' => 'delete',
      'table' => $table,
    ]);
  }

  public function destroy(Table $table) {
    try {
      $table->delete();
    } catch (\Exception $e) {
      return redirect()->route('tables.index')->with(['err' => 'Không thể xóa']);
    }
    return redirect()->route('tables.index')->with('success', 'Xóa thông tin thành công!');
  }

}
