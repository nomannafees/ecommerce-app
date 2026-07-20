@extends('layouts.app')

@section('content')
<div class="container">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 p-6 gap-4">

        <div class="bg-green-500 text-white p-6 shadow-lg rounded-xl h-35">
            <h2 class="text-xl font-bold">Categories</h2>
            <p class="mt-2 text-sm">Manage all product categories.</p>
        </div>

        <div class="bg-blue-500 text-white p-6 shadow-lg rounded-xl h-35">
            <h2 class="text-xl font-bold">Products</h2>
            <p class="mt-2 text-sm">Total available products in store.</p>
        </div>

        <div class="bg-orange-500 text-white p-6 shadow-lg rounded-xl h-35">
            <h2 class="text-xl font-bold">Coupons</h2>
            <p class="mt-2 text-sm">Manage discount coupons.</p>
        </div>

        <div class="bg-red-500 text-white p-6 shadow-lg rounded-xl h-35">
            <h2 class="text-xl font-bold">Wishlist</h2>
            <p class="mt-2 text-sm">Users' saved products.</p>
        </div>

    </div>
    @endsection