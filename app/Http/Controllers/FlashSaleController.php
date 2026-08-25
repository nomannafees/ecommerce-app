<?php

namespace App\Http\Controllers;

use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flashSales = FlashSale::with('product')->latest()->paginate(10);
        return view('flash_sales.index', compact('flashSales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('flash_sales.create_edit', compact('products'));
    }

    /**
     * Store or Update a flash sale (Upsert logic).
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id'          => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:1|max:99',
            'start_time'          => 'required|date',
            // Agar same date allow karni ho toh 'after_or_equal' use karein
            'end_time'            => 'required|date|after_or_equal:start_time',
        ]);

        // Check karein agar is product ki pehle se flash sale bani hai toh update karein, warna create karein
        FlashSale::updateOrCreate(
            ['product_id' => $request->product_id], // Search condition
            [
                'discount_percentage' => $request->discount_percentage,
                'start_time'          => $request->start_time,
                'end_time'            => $request->end_time,
            ]
        );

        return redirect()->back()->with('success', 'Flash Sale saved successfully!');
    }

    /**
     * Fetch existing flash sale data via AJAX for a specific product.
     */
    public function getFlashSaleData($productId)
    {
        $flashSale = FlashSale::where('product_id', $productId)->first();

        if ($flashSale) {
            return response()->json([
                'status' => true,
                'data'   => [
                    'discount_percentage' => $flashSale->discount_percentage,
                    // Sirf Y-m-d format rakhein kyunke ab input date type ka hai
                    'start_time'          => date('Y-m-d', strtotime($flashSale->start_time)),
                    'end_time'            => date('Y-m-d', strtotime($flashSale->end_time)),
                ]
            ]);
        }

        return response()->json(['status' => false]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $flashSale = FlashSale::findOrFail($id);
        $products = Product::all();
        return view('flash_sales.create_edit', compact('flashSale', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id'          => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:1|max:99',
            'start_time'          => 'required|date',
            'end_time'            => 'required|date|after_or_equal:start_time',
        ]);

        $flashSale = FlashSale::findOrFail($id);

        $flashSale->update([
            'product_id'          => $request->product_id,
            'discount_percentage' => $request->discount_percentage,
            'start_time'          => $request->start_time,
            'end_time'            => $request->end_time,
        ]);

        return redirect()->route('flash-sales.index')->with('success', 'Flash Sale updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $flashSale = FlashSale::findOrFail($id);
        $flashSale->delete();

        return redirect()->back()->with('success', 'Flash Sale deleted successfully!');
    }
}