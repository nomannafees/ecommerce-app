<footer class="bg-black text-white hidden md:block">

    <!-- Top Footer -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-10">

            <!-- About / Brand Logo & Title -->
            <div class="sm:col-span-2 lg:col-span-1">
                <!-- Dynamic Logo or Store Name -->
                <a href="{{ route('index') }}" class="inline-block mb-3 sm:mb-4">
                    @if(!empty($store->logo))
                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->store_name ?? 'Store Logo' }}" class="h-8 sm:h-10 w-auto object-contain">
                    @else
                        <h2 class="text-xl sm:text-2xl font-bold">{{ $store->store_name ?? 'ShopNest' }}</h2>
                    @endif
                </a>

                <p class="text-gray-400 text-xs sm:text-sm leading-relaxed">
                    {{ $store->about_us ?? $store->description ?? 'Discover premium products at the best prices. Shop with confidence and enjoy fast delivery, secure payments, and excellent customer support.' }}
                </p>

                <div class="flex gap-3 sm:gap-4 mt-4 sm:mt-5">
                    @if(!empty($store->facebook))
                        <a href="{{ $store->facebook }}" target="_blank" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-[#ff4d2d] transition text-xs sm:text-sm">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif

                    @if(!empty($store->instagram))
                        <a href="{{ $store->instagram }}" target="_blank" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-[#ff4d2d] transition text-xs sm:text-sm">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif

                    @if(!empty($store->twitter))
                        <a href="{{ $store->twitter }}" target="_blank" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-[#ff4d2d] transition text-xs sm:text-sm">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif

                    @if(!empty($store->linkedin))
                        <a href="{{ $store->linkedin }}" target="_blank" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-[#ff4d2d] transition text-xs sm:text-sm">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Quick Links</h3>

                <ul class="space-y-2 sm:space-y-3 text-gray-400 text-xs sm:text-sm">
                    <li><a href="{{ route('index') }}" class="hover:text-white transition">Home</a></li>
                    <li><a href="{{ route('frontendProduct') }}" class="hover:text-white transition">Products</a></li>
                    <li><a href="{{ route('categories') }}" class="hover:text-white transition">Categories</a></li>
{{--                    <li><a href="{{ route('index') }}" class="hover:text-white transition">About Us</a></li>--}}
                    <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact</a></li>
                </ul>
            </div>

            <!-- Categories -->
            <div>
                <h3 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Categories</h3>

                <ul class="space-y-2 sm:space-y-3 text-gray-400 text-xs sm:text-sm">

                    @foreach($categories->whereIn('slug', ['men-s-fashion', 'womens-fashion', 'mother-baby', 'home-lifestyle'])->values() as $mainCat)
                        <li>
                            <a href="{{ route('categoriesProduct', ['category' => $mainCat->slug]) }}"
                               class="hover:text-white transition block">
                                {{ $mainCat->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Contact Us</h3>

                <ul class="space-y-3 sm:space-y-4 text-gray-400 text-xs sm:text-sm">

                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-location-dot mt-0.5 sm:mt-1 flex-shrink-0"></i>
                        <span>{{ $store->address ?? 'Lahore, Pakistan' }}</span>
                    </li>

                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-phone flex-shrink-0"></i>
                        <span>{{ $store->phone ?? '+92 300 1234567' }}</span>
                    </li>

                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope flex-shrink-0"></i>
                        <span class="break-all">{{ $store->email ?? 'info@example.com' }}</span>
                    </li>

                </ul>
            </div>

        </div>

    </div>

    <!-- Bottom Footer -->
    <div class="border-t border-gray-800">

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-5">

            <div class="flex flex-col sm:flex-row justify-between items-center gap-3 text-center sm:text-left">

                <!-- Dynamic Store Name / Copyright -->
                <p class="text-xs sm:text-sm text-gray-400">
                    © {{ date('Y') }} {{ $store->store_name ?? 'ShopNest' }}. All Rights Reserved.
                </p>

                <div class="flex gap-4 sm:gap-5 text-xs sm:text-sm text-gray-400">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms & Conditions</a>
                </div>

            </div>

        </div>

    </div>

</footer>
