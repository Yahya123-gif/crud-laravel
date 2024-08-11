<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(4);
        return view('product.index', compact('products'));
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'qty' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        Product::create($data);

        return redirect()->route('product.index')->with('success', 'Product created successfully!');
    }


    public function destroy($id){
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('product.index')->with('success', 'Product deleted successfully!');
    }
    public function edit($id)
    {
        $product = Product::findOrFail($id); 
        return view('product.edit', compact('product'));
    }
    public function update(Request $request, Product $product)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            // Add other fields and validation rules as needed
        ]);
    
        // Update the product with validated data
        $product->update($validatedData);
    
        // Redirect or return a response
        return redirect()->route('product.index')->with('success', 'Product updated successfully!');
    }


    public function downloadPDF()
{
    $products = Product::all(); // Fetch your product data

    $pdf = Pdf::loadView('product.pdf', compact('products'));

    return $pdf->download('product.pdf');
}
}