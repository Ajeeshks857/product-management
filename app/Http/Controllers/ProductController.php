<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.product._product_list');
    }
    public function create(Request $request)
    {
        return view('admin.product._create');
    }
    public function edit(Request $request, $id)
    {
        return view('admin.product._edit',compact('id'));
    }
}
