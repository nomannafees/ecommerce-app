<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $reviews = Review::with(['user', 'product', 'order', 'images'])
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('review.index', compact('reviews'));
    }

    public function create()
    {
        $users = User::all();
        $products = Product::all();
        $orders = Order::all();

        return view('review.create-edit', compact('users', 'products', 'orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'     => 'required|exists:users,id',
            'product_id'  => 'required|exists:products,id',
            'order_id'    => 'required|exists:orders,id',
            'rating'      => 'required|integer|min:1|max:5',
            'comment'     => 'nullable|string|max:1000',
            'images'      => 'nullable|array',
            'images.*'    => 'image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'is_approved' => 'nullable|boolean',
        ]);

        // Order status check
        $order = Order::where('id', $request->order_id)
            ->where('status', 'delivered')
            ->first();

        if (!$order) {
            return back()->withInput()->with('error', 'Review is only allowed for delivered orders.');
        }

        // Duplicate review check
        $alreadyReviewed = Review::where('user_id', $request->user_id)
            ->where('order_id', $request->order_id)
            ->where('product_id', $request->product_id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->withInput()->with('error', 'A review for this product in this order already exists.');
        }

        DB::transaction(function () use ($validated, $request) {
            $review = Review::create([
                'user_id'     => $validated['user_id'],
                'product_id'  => $validated['product_id'],
                'order_id'    => $validated['order_id'],
                'rating'      => $validated['rating'],
                'comment'     => $validated['comment'] ?? null,
                'is_approved' => $request->has('is_approved') ? $request->is_approved : true,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    if ($file->isValid()) {
                        $path = str_replace('\\', '/', $file->store('reviews', 'public'));

                        ReviewImage::create([
                            'review_id'  => $review->id,
                            'image_path' => $path,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('reviews.index')->with('success', 'Review created successfully!');
    }

    public function show(Review $review)
    {
        $review->load(['user', 'product', 'order', 'images']);

        return view('review.show', compact('review'));
    }

    public function edit(Review $review)
    {
        $users = User::all();
        $products = Product::all();
        $orders = Order::all();

        $review->load('images');

        return view('review.create-edit', compact('review', 'users', 'products', 'orders'));
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'user_id'     => 'required|exists:users,id',
            'product_id'  => 'required|exists:products,id',
            'order_id'    => 'required|exists:orders,id',
            'rating'      => 'required|integer|min:1|max:5',
            'comment'     => 'nullable|string|max:1000',
            'images'      => 'nullable|array',
            'images.*'    => 'image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'is_approved' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($validated, $request, $review) {
            $review->update([
                'user_id'     => $validated['user_id'],
                'product_id'  => $validated['product_id'],
                'order_id'    => $validated['order_id'],
                'rating'      => $validated['rating'],
                'comment'     => $validated['comment'] ?? null,
                'is_approved' => $request->has('is_approved') ? $request->is_approved : $review->is_approved,
            ]);

            if ($request->hasFile('images')) {
                foreach ($review->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage->image_path);
                    $oldImage->delete();
                }

                foreach ($request->file('images') as $file) {
                    if ($file->isValid()) {
                        $path = str_replace('\\', '/', $file->store('reviews', 'public'));

                        ReviewImage::create([
                            'review_id'  => $review->id,
                            'image_path' => $path,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('reviews.index')->with('success', 'Review updated successfully!');
    }

    public function destroy(Review $review)
    {
        DB::transaction(function () use ($review) {
            // Delete associated images from public storage
            foreach ($review->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Remove review images table records
            $review->images()->delete();

            // Delete review record
            $review->delete();
        });

        return redirect()->route('reviews.index')->with('success', 'Review deleted successfully!');
    }
}
