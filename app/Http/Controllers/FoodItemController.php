<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\FoodType;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Storage;

class FoodItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FoodItem::with('foodType');
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%$search%");
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        
        if (in_array($sortBy, ['created_at', 'price'])) {
            $query->orderBy($sortBy, $sortOrder);
        }
    
        $foodItems = $query->paginate(10)->withQueryString();

        return view('food_items.index', compact('foodItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $foodTypes = FoodType::all();
        $mode = 'create';
        return view('food_items.form', compact('mode', 'foodTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'image|mimes:jpg,jpeg,png|max:2048',
            'food_type_id' => 'required|exists:food_types,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $imagePath = '';
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('food_items', 'public');
        }

        FoodItem::create([
            'name' => $request->name,
            'image' => $imagePath,
            'food_type_id' => $request->food_type_id,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return redirect()->route('food-items.index')->with('success', 'Món ăn đã được thêm thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(FoodItem $foodItem)
    {
        $foodTypes = FoodType::get();
        $ingredients = Ingredient::all();
        return view('food_items.show', compact('foodItem', 'foodTypes', 'ingredients'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FoodItem $foodItem)
    {
        $foodTypes = FoodType::all();
        $mode = 'update';
        return view('food_items.form', compact('mode', 'foodItem', 'foodTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FoodItem $foodItem)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'food_type_id' => 'required|exists:food_types,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($foodItem->image);
            $imagePath = $request->file('image')->store('food_items', 'public');
        } else {
            $imagePath = $foodItem->image;
        }

        $foodItem->update([
            'name' => $request->name,
            'image' => $imagePath,
            'food_type_id' => $request->food_type_id,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return redirect()->route('food-items.index')->with('success', 'Món ăn đã được cập nhật!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FoodItem $foodItem)
    {
        try {
            $foodItem->delete();
            // Storage::disk('public')->delete($foodItem->image);
            return redirect()->route('food-items.index')->with('success', 'Món ăn đã được xóa!');
        } catch (\Exception $e) {
            return redirect()->route('food-items.index')->with('error', 'Không thể xóa món ăn!');
        }
    }
}
