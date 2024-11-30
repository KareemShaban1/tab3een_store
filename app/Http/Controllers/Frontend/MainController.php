<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class MainController extends Controller
{
    //
    public function index()
    {
        $products = Product::whereNotNull('application_category_id')
        ->get()->take(10);
        return view('frontend.pages.home.index',compact('products'));
        }
}
