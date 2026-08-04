@extends('layouts.app')

@section('content')

    <div class=" mx-auto p-6">

        <div class="bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden">

            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50/50">
                <h2 class="text-2xl font-bold text-gray-800">
                    Coupons Management
                </h2>

                <!-- Main Green Button -->
                <a href="{{ route('coupons.create') }}"
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition duration-200 shadow-xs active:scale-[0.98]">
                    <i class="fa-solid fa-folder-plus text-xs"></i>
                    <span>Add Coupon</span>
                </a>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('coupons.index') }}" class="p-6">

                <div class="flex flex-wrap items-center gap-3">

                    <!-- Modern Floating Search Input -->
                    <div class="relative w-full sm:w-80">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                            <i class="fa-solid fa-magnifying-glass text-sm"></i>
                        </span>

                        <input type="text"
                               name="search"
                               id="coupon_search"
                               value="{{ request('search') }}"
                               placeholder=" "
                               class="peer w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                        <label for="coupon_search"
                               class="absolute left-11 top-3 text-gray-400 text-sm pointer-events-none transition-all duration-200 z-10
                               peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-5.5 peer-focus:left-3 peer-focus:bg-white peer-focus:px-1.5 peer-focus:font-medium
                               peer-[:not(:placeholder-shown)]:-translate-y-5.5 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-1.5 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:font-medium">
                            Search coupon code...
                        </label>
                    </div>

                    <!-- Search Actions -->
                    <div class="flex items-center gap-2">
                        <!-- Search Button -->
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-medium text-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500/20 transition duration-200 shadow-xs cursor-pointer active:scale-[0.98]">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            <span>Search</span>
                        </button>

                        <!-- Clear Button -->
                        <a href="{{ route('coupons.index') }}"
                           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-medium text-sm text-white bg-red-500 hover:bg-red-600 transition duration-200 cursor-pointer active:scale-[0.98]">
                            <i class="fa-solid fa-xmark text-xs"></i>
                            <span>Clear</span>
                        </a>
                    </div>

                </div>

            </form>

            <!-- Table Container -->
            <div class="px-6 pb-6 overflow-x-auto">

                <!-- Table with Outer Border and Sharp Edges -->
                <table class="w-full text-left border-collapse border border-gray-200">

                    <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-700 text-xs uppercase font-semibold">
                        <th class="px-4 py-3.5 border-r border-gray-200">#</th>
                        <th class="px-4 py-3.5 border-r border-gray-200">Code</th>
                        <th class="px-4 py-3.5 border-r border-gray-200">Type</th>
                        <th class="px-4 py-3.5 border-r border-gray-200">Value</th>
                        <th class="px-4 py-3.5 border-r border-gray-200">Min Cart</th>
                        <th class="px-4 py-3.5 border-r border-gray-200">Expire Date</th>
                        <th class="px-4 py-3.5 border-r border-gray-200 text-center">Status</th>
                        <th class="px-4 py-3.5 text-center w-28">Action</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 text-sm text-gray-700">

                    @forelse($records as $key => $record)

                        <tr class="hover:bg-gray-50/50 transition-colors duration-150">

                            <!-- # -->
                            <td class="px-4 py-3.5 border-r border-gray-200 text-gray-500 font-medium">
                                {{ $records->firstItem() + $key }}
                            </td>

                            <!-- Code -->
                            <td class="px-4 py-3.5 border-r border-gray-200 font-semibold text-gray-800">
                                {{ $record->code ?? '-' }}
                            </td>

                            <!-- Type -->
                            <td class="px-4 py-3.5 border-r border-gray-200 font-medium capitalize text-gray-600">
                                {{ $record->type ?? '-' }}
                            </td>

                            <!-- Value -->
                            <td class="px-4 py-3.5 border-r border-gray-200 font-medium text-gray-800">
                                {{ $record->value ?? 0 }}
                            </td>

                            <!-- Min Cart -->
                            <td class="px-4 py-3.5 border-r border-gray-200 text-gray-600">
                                {{ $record->min_cart_amount ?? 0 }}
                            </td>

                            <!-- Expire -->
                            <td class="px-4 py-3.5 border-r border-gray-200 text-gray-600">
                                {{ $record->expire_date ?? '-' }}
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3.5 border-r border-gray-200 text-center">
                                @if($record->status)
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs px-3 py-1 rounded-full font-medium border border-emerald-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Active
                        </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 text-xs px-3 py-1 rounded-full font-medium border border-red-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            Inactive
                        </span>
                                @endif
                            </td>

                            <!-- Action Buttons -->
                            <td class="px-4 py-3.5">

                                <div class="flex items-center justify-center gap-2">

                                    <!-- Edit Button -->
                                    <a href="{{ route('coupons.edit', $record->id) }}"
                                       class="w-9 h-9 flex items-center justify-center bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl hover:bg-emerald-100 transition-all duration-200 shadow-xs cursor-pointer"
                                       title="Edit Coupon">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>

                                    <!-- Delete Button with SweetAlert -->
                                    <form action="{{ route('coupons.destroy', $record->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                                class="delete-btn w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-all duration-200 shadow-xs cursor-pointer"
                                                title="Delete Coupon">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-ticket text-3xl mb-2 block text-gray-300"></i>
                                <span>No Coupons Found</span>
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

                @if($records->hasPages())
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        {{ $records->links() }}
                    </div>
                @endif

            </div>

        </div>

    </div>

@endsection
