<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    // Hiển thị danh sách nguyên liệu
    public function index(Request $request)
    {
        $query = Ingredient::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%$search%");
        }

        $sortBy = $request->input('sort_by', 'updated_at');
        $sortOrder = $request->input('sort_order', 'desc');

        if (in_array($sortBy, ['updated_at', 'quantity'])) {
            $query->orderBy($sortBy, $sortOrder);
        }
        $ingredients = $query->paginate(10)->withQueryString();
        return view('ingredients.index', compact('ingredients'));
    }

    // Form thêm nguyên liệu
    public function create()
    {
        return view('ingredients.form', [
            'mode' => 'create',
        ]);
    }

    // Xử lý thêm nguyên liệu
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
        ]);

        Ingredient::create([
            'name' => $request->input('name'),
            'quantity' => $request->input('quantity'),
            'unit' => $request->input('unit'),
        ]);

        return redirect()->route('ingredients.index')->with('success', 'Thêm nguyên liệu thành công!');
    }

    // Form chỉnh sửa nguyên liệu
    public function edit($id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $mode = 'update';
        return view('ingredients.form', compact('mode', 'ingredient'));
    }

    // Xử lý cập nhật nguyên liệu
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name,' . $id,
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
        ]);

        $ingredient = Ingredient::findOrFail($id);
        $ingredient->update($request->all());

        return redirect()->route('ingredients.index')->with('success', 'Cập nhật nguyên liệu thành công.');
    }

    // Xóa nguyên liệu
    public function destroy($id)
    {
        try {
            $ingredient = Ingredient::findOrFail($id);
            $ingredient->delete();

            return redirect()->route('ingredients.index')->with('success', 'Xóa nguyên liệu thành công!');
        } catch (\Exception $e) {
            return redirect()->route('ingredients.index')->with('error', 'Lỗi khi xóa nguyên liệu!');
        }
    }
}
