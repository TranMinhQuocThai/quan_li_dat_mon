<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Foodingredient;
use App\Models\FoodItem;
use App\Models\Ingredient;

class FoodIngredientController extends Controller
{
    public function index()
    {
        $foodIngredients = FoodIngredient::with(['food', 'ingredient'])->get();

        $groupedFoodIngredients = $foodIngredients->groupBy(function ($item) {
            return $item->food->name;
        });

        return view('food_ingredients.index', compact('groupedFoodIngredients'));
    }

    public function create()
    {
        $foods = FoodItem::all();
        $ingredients = Ingredient::all();
        return view('food_ingredients.create', compact('foods', 'ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'food_item_id' => 'required|exists:food_items,id',
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity' => 'required|integer|min:0.001',
        ]);

        $exists = FoodIngredient::where('food_item_id', $request->food_item_id)
            ->where('ingredient_id', $request->ingredient_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Nguyên liệu này đã tồn tại trong món ăn!');
        }

        FoodIngredient::create($request->all());
        return redirect()->back()->with('success', 'Thêm nguyên liệu cho món ăn thành công!');
    }

    public function edit($id)
    {
        $foodIngredient = FoodIngredient::findOrFail($id);
        $foods = FoodItem::all();
        $ingredients = Ingredient::all();
        return view('food_ingredients.edit', compact('foodIngredient', 'foods', 'ingredients'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'food_item_id' => 'required|exists:food_items,id',
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity' => 'required|integer|min:0.001',
        ]);

        $foodIngredient = FoodIngredient::findOrFail($id);
        $foodIngredient->update($request->all());

        return redirect()->back()->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $foodIngredient = FoodIngredient::findOrFail($id);
        $foodIngredient->delete();
        return redirect()->back()->with('success', 'Xóa thành công!');
    }
}
