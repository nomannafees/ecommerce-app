<div id="mySidenav"
     class="bg-white w-64 h-screen overflow-y-auto shadow-md fixed lg:static transition-transform duration-300 -translate-x-64 lg:translate-x-0 z-12">

    <!-- Header -->
    <div class="flex justify-between items-center bg-gray-100 p-4 sticky top-0 z-10">
        <h3 class="text-2xl font-semibold">Logo</h3>
        <button class="lg:hidden" onclick="CloseNav()">
            <i class="fa fa-times"></i>
        </button>
    </div>

    <!-- Menu -->
    <div class="p-4 space-y-3">

        <a href="{{ route('home') }}"
           class="flex items-center gap-3 p-2 hover:bg-gray-100 rounded-lg transition">
            <i class="fa-solid fa-gauge text-blue-500"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('categorie.index') }}"
           class="flex items-center gap-3 p-2 hover:bg-gray-100 rounded-lg transition">
            <i class="fa-solid fa-tags text-blue-500"></i>
            <span>Category</span>
        </a>

        <!-- 👇 FIXED: Brand ko Category ke neeche sahi jagah par add kar diya hai -->
        <a href="{{ route('brands.index') }}"
           class="flex items-center gap-3 p-2 hover:bg-gray-100 rounded-lg transition">
            <i class="fa-solid fa-copyright text-cyan-500"></i>
            <span>Brands</span>
        </a>

        <a href="{{ route('products.index') }}"
           class="flex items-center gap-3 p-2 hover:bg-gray-100 rounded-lg transition">
            <i class="fa-solid fa-box text-purple-500"></i>
            <span>Products</span>
        </a>

        <a href="{{ route('variants.index') }}"
           class="flex items-center gap-3 p-2 hover:bg-gray-100 rounded-lg transition">
            <i class="fa-solid fa-sliders text-orange-500"></i>
            <span>Variants</span>
        </a>

        <a href="{{ route('coupons.index') }}"
           class="flex items-center gap-3 p-2 hover:bg-gray-100 rounded-lg transition">
            <i class="fa-solid fa-ticket text-pink-500"></i>
            <span>Coupon</span>
        </a>

        <a href="{{ route('wishlists.index') }}"
           class="flex items-center gap-3 p-2 hover:bg-gray-100 rounded-lg transition">
            <i class="fa-solid fa-heart text-red-500"></i>
            <span>Wishlist</span>
        </a>

        <a href="{{ route('carts.index') }}"
           class="flex items-center gap-3 p-2 hover:bg-gray-100 rounded-lg transition">
            <i class="fa-solid fa-cart-shopping text-green-500"></i>
            <span>Carts</span>
        </a>

        <a href="{{ route('orders.index') }}"
           class="flex items-center gap-3 p-2 hover:bg-gray-100 rounded-lg transition">
            <i class="fa-solid fa-box text-blue-500"></i>
            <span>Orders</span>
        </a>

        <a href="{{ route('sliders.index') }}"
           class="flex items-center gap-3 p-2 hover:bg-gray-100 rounded-lg transition">
            <i class="fa-solid fa-images text-indigo-500"></i>
            <span>Slider</span>
        </a>

    </div>

</div>
