<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller {
  public function index() {
    $departments = Department::get();
    return view('department.index')->with('departments', $departments);
  }

  public function create() {
    return view('department.form', ['mode' => 'create']);
  }

  public function store(Request $request) {
    $validatedData = $request->validate([
      'name' => [
        'required',
        'string',
        'max:255',
        Rule::unique('departments', 'name')->ignore(-1, 'id'),
      ]
    ], [
      'name.required' => 'Tên phòng ban là bắt buộc.',
      'name.string' => 'Tên phòng ban phải là chuỗi ký tự.',
      'name.max' => 'Tên phòng ban không được vượt quá 255 ký tự.',
      'name.unique' => 'Tên phòng ban đã tồn tại.',
    ]);

    Department::create([
      'name' => $validatedData['name'],
    ]);

    return redirect()->route('department.index');
  }
  public function edit($id) {
    $department = Department::findOrFail($id); // Lấy thông tin phòng ban theo ID
    return view('department.form',
      [
        'mode' => 'update',
        'department' => $department,
      ]);
  }

  public function update(Request $request, $id) {
    $department = Department::findOrFail($id);
    $request->validate([
      'name' => [
        'required',
        'string',
        'max:255',
        Rule::unique('departments', 'name')->ignore($department->id, 'id'),
      ]
    ], [
      'name.required' => 'Tên phòng ban là bắt buộc.',
      'name.string' => 'Tên phòng ban phải là chuỗi ký tự.',
      'name.max' => 'Tên phòng ban không được vượt quá 255 ký tự.',
      'name.unique' => 'Tên phòng ban đã tồn tại.',
    ]);

    $department->update([
      'name' => $request->input('name'),
    ]);

    return redirect()->route('department.index')->with('success', 'Cập nhật thông tin phòng ban thành công!');
  }

  public function renderDelete($id) {
    $department = Department::findOrFail($id); // Lấy thông tin phòng ban theo ID
    return view('department.form',
      [
        'mode' => 'delete',
        'department' => $department,
      ]);
  }

  public function destroy(Request $request, $id) {
    $department = Department::findOrFail($id);
    try {
      $department->delete();
    } catch (\Throwable $th) {
      redirect()->route('department.index')->with('err', 'Không xóa được!');
    }
    return redirect()->route('department.index')->with('success', 'Cập nhật thông tin phòng ban thành công!');
  }

}
