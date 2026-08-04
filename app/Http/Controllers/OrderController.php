<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = Order::query();

        // 1. Search (Order Number, Customer Name, Phone)
        if ($request->filled('search')) {
            $search = $request->search;
            $orders->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // 2. Filter by Order Status
        if ($request->filled('status')) {
            $orders->where('status', $request->status);
        }

        // 3. Filter by Payment Status
        if ($request->filled('payment_status')) {
            $orders->where('payment_status', $request->payment_status);
        }

        // 4. Filter by Date Range
        $startDate = $request->input('date_from', $request->input('start_date'));
        $endDate   = $request->input('date_to', $request->input('end_date'));

        if ($startDate) {
            $orders->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $orders->whereDate('created_at', '<=', $endDate);
        }

        $orders = $orders->latest()->paginate(10)->withQueryString();

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // Relations ko load karna zaroori hai taake Country, State aur City show ho sakein
        $order->load(['country', 'state', 'city', 'items.product', 'items.variant.variantImage', 'items.product.mainVariantImage']);

        foreach ($order->items as $item) {
            $item->item_image = asset('upload/no-image.jpg');

            if ($item->variant && $item->variant->variantImage) {
                $item->item_image = asset('storage/' . $item->variant->variantImage->image_path);
            } elseif ($item->product && $item->product->mainVariantImage) {
                $item->item_image = asset('storage/' . $item->product->mainVariantImage->image_path);
            }
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Order status updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // Pehle Order items/relationships clear karein (agar Cascade delete set nahi hai DB level par)
        if (method_exists($order, 'items')) {
            $order->items()->delete();
        }

        $order->delete();

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order deleted successfully!');
    }
}
