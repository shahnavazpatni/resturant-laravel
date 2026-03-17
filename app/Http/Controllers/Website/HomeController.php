<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Slider;
use App\Category;
use App\Item;

class HomeController extends Controller
{
    public function index(){
        $sliders = Slider::where('status', 1)->get();
        $categories = Category::all();
        $items = Item::all();
        return view('welcome', compact('sliders', 'categories', 'items'));
    }
}
