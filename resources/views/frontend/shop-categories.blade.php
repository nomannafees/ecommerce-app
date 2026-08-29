@extends('frontend.layouts.app')

@section('content')

    <div class="min-h-screen bg-slate-50/50 pb-13">

        <!-- TOP HEADER: Modern Sticky Header -->
        <div class="bg-white/95 backdrop-blur-md sticky top-0 z-30 border-b border-slate-100 shadow-xs">
            <div class="flex items-center justify-between px-4 py-3.5 max-w-2xl mx-auto">
                <div class="flex items-center gap-3">
                    <a href="{{ route('index') }}" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 hover:bg-slate-200 hover:text-black transition">
                        <i class="fa-solid fa-arrow-left text-sm"></i>
                    </a>
                    <h1 class="text-base font-bold text-slate-900 tracking-tight">Shop by Category</h1>
                </div>

                <!-- Cart Icon with Animated Badge -->
                <a href="{{ route('cart') }}" class="relative flex items-center justify-center w-10 h-10 bg-slate-100 rounded-full hover:bg-slate-200 transition">
                    <i class="fa-solid fa-cart-shopping text-sm text-slate-800"></i>
                    <span class="cart-count absolute -top-1 -right-1 bg-emerald-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full shadow-xs {{ ($cartCount ?? 0) > 0 ? '' : 'hidden' }}">
                        {{ $cartCount ?? 0 }}
                    </span>
                </a>
            </div>
        </div>

        <!-- CATEGORIES ACCORDION LIST CONTAINER -->
        <div class="px-3 sm:px-4 py-3 space-y-3 max-w-2xl mx-auto">

            @foreach($categories->where('parent_id', 0) as $mainCat)
                @php
                    $subCategories = $mainCat->children ?? $categories->where('parent_id', $mainCat->id);
                @endphp

                <div class="bg-white rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition-all duration-300 overflow-hidden"
                     x-data="{ open: false, openSub: null }">

                    <!-- MAIN CATEGORY ROW -->
                    <div class="flex items-center justify-between px-4 py-3.5 cursor-pointer select-none group"
                         @click="open = !open">

                        <a href="{{ route('categoriesProduct', ['category' => $mainCat->slug]) }}"
                           @click.stop
                           class="flex items-center gap-3.5 flex-1">
                            @if($mainCat->icon)
                                <div class="w-11 h-11 rounded-xl bg-slate-50 border border-slate-100 p-1.5 flex items-center justify-center shrink-0 group-hover:border-emerald-200 transition">
                                    <img src="{{ asset('storage/category-icon/' . $mainCat->icon) }}"
                                         class="w-full h-full object-contain">
                                </div>
                            @else
                                <div class="w-11 h-11 flex items-center justify-center bg-emerald-50 text-emerald-600 rounded-xl shrink-0">
                                    <i class="fa-solid fa-layer-group text-sm"></i>
                                </div>
                            @endif
                            <div>
                                <span class="text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition block">{{ $mainCat->name }}</span>
                                <span class="text-[11px] text-slate-400 font-medium">{{ $subCategories->count() }} Subcategories</span>
                            </div>
                        </a>

                        <!-- Dropdown Toggle Arrow Button -->
                        @if($subCategories->count() > 0)
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 bg-slate-50 group-hover:bg-emerald-50 group-hover:text-emerald-600 transition">
                                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300"
                                   :class="open ? 'rotate-180' : ''"></i>
                            </div>
                        @endif

                    </div>

                    <!-- SUB CATEGORIES (Level 2) -->
                    @if($subCategories->count() > 0)
                        <div x-show="open" x-collapse x-cloak class="border-t border-slate-100 bg-slate-50/70 divide-y divide-slate-100/80">
                            @foreach($subCategories as $subCat)
                                @php
                                    $subSlugPath = $mainCat->slug . '/' . $subCat->slug;
                                    $childCategories = $subCat->children ?? $categories->where('parent_id', $subCat->id);
                                @endphp

                                <div>
                                    <div class="flex items-center justify-between pl-12 pr-4 py-3 cursor-pointer select-none hover:bg-slate-100/60 transition"
                                         @click="openSub = (openSub === {{ $subCat->id }}) ? null : {{ $subCat->id }}">

                                        <a href="{{ route('categoriesProduct', ['category' => $subSlugPath]) }}"
                                           @click.stop
                                           class="flex-1 text-[13px] font-semibold text-slate-700 hover:text-emerald-600">
                                            {{ $subCat->name }}
                                        </a>

                                        @if($childCategories->count() > 0)
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-slate-400 transition-transform duration-200">
                                                <i class="fa-solid fa-chevron-down text-[10px]"
                                                   :class="openSub === {{ $subCat->id }} ? 'rotate-180 text-emerald-600' : ''"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- CHILD CATEGORIES (Level 3) -->
                                    @if($childCategories->count() > 0)
                                        <div x-show="openSub === {{ $subCat->id }}" x-collapse x-cloak class="bg-white pl-4 divide-y divide-slate-50">
                                            @foreach($childCategories as $childCat)
                                                @php
                                                    $childSlugPath = $subSlugPath . '/' . $childCat->slug;
                                                @endphp
                                                <a href="{{ route('categories', ['category' => $childSlugPath]) }}"
                                                   class="flex items-center pl-8 pr-4 py-2.5 text-[12px] font-medium text-slate-600 hover:text-emerald-600 hover:bg-emerald-50/30 transition">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300 mr-2.5"></span>
                                                    {{ $childCat->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            @endforeach

            @if($categories->where('parent_id', 0)->count() == 0)
                <div class="text-center py-20 text-slate-400">
                    <i class="fa-solid fa-box-open text-4xl mb-3 block text-slate-300"></i>
                    <span class="text-sm font-medium">No categories found</span>
                </div>
            @endif

        </div>

    </div>

@endsection