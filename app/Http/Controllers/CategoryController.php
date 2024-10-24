<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('stok.bahan');
    }

    public function add_category(Request $request)
    {
        // Validate the request
        $request->validate([
            'name_category' => 'required',
        ]);

        // Check if the category already exists in the database
        $existing_category = Category::where('name_category', $request->name_category)->first();

        if ($existing_category) {
            // If category exists, flash a message to the session
            return back()->with('error', 'Category already exists');
        } else {
            // If category doesn't exist, create a new category
            Category::create([
                'name_category' => $request->name_category,
            ]);

            // Flash a success message to the session
            return back()->with('success', 'Category added successfully');
        }
    }
}
