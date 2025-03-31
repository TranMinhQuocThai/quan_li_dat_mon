<?php

namespace App\Http\Controllers;

use App\Models\FoodType;
use Illuminate\Http\Request;

class FoodTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $foodTypes = FoodType::paginate(10);
        return view('food_types.index', compact('foodTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('food_types.form', [
            'mode' => 'create',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|unique:food_types|max:255',
            ],
            [
                'name.required' => 'Tên loại món ăn là bắt buộc.',
                'name.string' => 'Tên loại món ăn phải là một chuỗi ký tự.',
                'name.unique' => 'Tên loại món ăn đã tồn tại.',
                'name.max' => 'Tên loại món ăn không được vượt quá 255 ký tự.',
            ]
        );

        FoodType::create($request->only('name'));

        return redirect()->route('food-types.index')->with('success', 'Food type created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(FoodType $foodType)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FoodType $foodType)
    {
        return view('food_types.form', [
            'mode' => 'update',
            'foodType' => $foodType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FoodType $foodType)
    {
        $request->validate(
            [
                'name' => 'required|string|unique:food_types,name,' . $foodType->id . '|max:255',
            ],
            [
                'name.required' => 'Tên loại món ăn là bắt buộc.',
                'name.string' => 'Tên loại món ăn phải là một chuỗi ký tự.',
                'name.unique' => 'Tên loại món ăn đã tồn tại.',
                'name.max' => 'Tên loại món ăn không được vượt quá 255 ký tự.',
            ]
        );


        $foodType->update($request->only('name'));

        return redirect()->route('food-types.index')->with('success', 'Food type updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FoodType $foodType)
    {
        try {
            $foodType->delete();
            return redirect()->route('food-types.index')->with('success', 'Loại món ăn đã được xóa thành công!');
        } catch (\Throwable $e) {
            return redirect()->route('food-types.index')->with('error', 'Không thể xóa loại món ăn này vì đang được sử dụng!');
        }
    }
}
