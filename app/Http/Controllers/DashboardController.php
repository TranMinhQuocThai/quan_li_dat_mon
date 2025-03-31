<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\FoodType;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function overview()
    {
        $userCount = User::where('role', '=', 'user')->count();
        $tableCount = Table::count();
        $foodTypeCount = FoodType::count();
        $foodItemCount = FoodItem::count();

        return view('dash.overview', compact('userCount', 'tableCount', 'foodTypeCount', 'foodItemCount'));
    }
}
