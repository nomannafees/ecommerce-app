@extends('layouts.app')

@section('content')

    <div class="mx-auto p-6">

        <div class="bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden">

            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50/50">
                <h2 class="text-2xl font-bold text-gray-800">
                    Reviews Management
                </h2>

                <a href="{{ route('reviews.create') }}"
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl transition-all duration-200 shadow-xs text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 active:scale-[0.98]">
                    <i class="fa-solid fa-folder-plus text-sm"></i>
                    <span>Add Review</span>
                </a>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mx-6 mt-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mx-6 mt-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
        @endif

        <!-- Filter / Search Form -->
            <form method="GET" action="{{ route('reviews.index') }}" class="p-6">

                <div class="flex flex-wrap items-center gap-3">

                    <!-- Search Input with Floating Border Style -->
                    <div class="relative w-full sm:w-80">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                            <i class="fa-solid fa-magnifying-glass text-sm"></i>
                        </span>

                        <input
                            type="text"
                            name="search"
                            id="review_search"
                            value="{{ request('search') }}"
                            placeholder=" "
                            class="peer w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                        <label for="review_search"
                               class="absolute left-11 top-3 text-gray-400 text-sm pointer-events-none transition-all duration-200 z-10
                               peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-5.5 peer-focus:left-3 peer-focus:bg-white peer-focus:px-1.5 peer-focus:font-medium
                               peer-[:not(:placeholder-shown)]:-translate-y-5.5 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-1.5 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:font-medium">
                            Search User or Product...
                        </label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2">
                        <!-- Search Button -->
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-medium text-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500/20 transition duration-200 shadow-xs cursor-pointer active:scale-[0.98]">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            <span>Search</span>
                        </button>

                        <!-- Clear Button -->
                        <a href="{{ route('reviews.index') }}"
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
                        <th class="px-4 py-3.5 border-r border-gray-200 w-12">#</th>
                        <th class="px-4 py-3.5 border-r border-gray-200">Images</th>
                        <th class="px-4 py-3.5 border-r border-gray-200">User</th>
                        <th class="px-4 py-3.5 border-r border-gray-200">Product</th>
                        <th class="px-4 py-3.5 border-r border-gray-200">Rating</th>
                        <th class="px-4 py-3.5 border-r border-gray-200">Comment</th>
                        <th class="px-4 py-3.5 border-r border-gray-200">Status</th>
                        <th class="px-4 py-3.5 border-r border-gray-200">Created At</th>
                        <th class="px-4 py-3.5 text-center w-36">Action</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 text-sm text-gray-700">

                    @forelse($reviews as $key => $record)

                        <tr class="hover:bg-gray-50/50 transition-colors duration-150">

                            <td class="px-4 py-3.5 border-r border-gray-200 text-gray-500 font-medium">
                                {{ $reviews->firstItem() + $key }}
                            </td>

                            <!-- REVIEW IMAGES -->
                            <td class="px-4 py-3.5 border-r border-gray-200">
                                @if($record->images && $record->images->isNotEmpty())
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        @foreach($record->images as $img)
                                            <img src="{{ asset('storage/' . $img->image_path) }}"
                                                 alt="Review Image"
                                                 class="w-9 h-9 object-cover rounded-lg border border-gray-200 shadow-xs hover:scale-105 transition-transform duration-200">
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 font-medium">No Images</span>
                                @endif
                            </td>

                            <td class="px-4 py-3.5 border-r border-gray-200 font-semibold text-gray-800">
                                {{ $record->user->name ?? '-' }}
                            </td>

                            <td class="px-4 py-3.5 border-r border-gray-200">
                                {{ $record->product->name ?? 'Product Deleted' }}
                            </td>

                            <td class="px-4 py-3.5 border-r border-gray-200 whitespace-nowrap">
                                <div class="flex items-center gap-1 text-amber-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $record->rating)
                                            <i class="fa-solid fa-star text-xs"></i>
                                        @else
                                            <i class="fa-regular fa-star text-xs text-gray-300"></i>
                                        @endif
                                    @endfor
                                    <span class="text-xs text-gray-500 ml-1 font-semibold">({{ $record->rating }})</span>
                                </div>
                            </td>

                            <td class="px-4 py-3.5 border-r border-gray-200 text-xs text-gray-600 max-w-xs">
                                {{ Str::limit($record->comment ?? '-', 40) }}
                            </td>

                            <td class="px-4 py-3.5 border-r border-gray-200 whitespace-nowrap">
                                @if($record->is_approved)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Approved
                        </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                            Hidden
                        </span>
                                @endif
                            </td>

                            <td class="px-4 py-3.5 border-r border-gray-200 text-gray-500 whitespace-nowrap">
                                {{ $record->created_at?->format('Y-m-d') ?? '-' }}
                            </td>

                            <td class="px-4 py-3.5">

                                <div class="flex items-center justify-center gap-2">

                                    <!-- Status Toggle Form -->
                                    <form action="{{ route('reviews.update', $record->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="user_id" value="{{ $record->user_id }}">
                                        <input type="hidden" name="product_id" value="{{ $record->product_id }}">
                                        <input type="hidden" name="order_id" value="{{ $record->order_id }}">
                                        <input type="hidden" name="rating" value="{{ $record->rating }}">
                                        <input type="hidden" name="comment" value="{{ $record->comment }}">
                                        <input type="hidden" name="is_approved" value="{{ $record->is_approved ? 0 : 1 }}">

                                        @if($record->is_approved)
                                            <button type="submit"
                                                    class="w-9 h-9 flex items-center justify-center bg-amber-50 text-amber-600 border border-amber-100 rounded-xl hover:bg-amber-100 transition-all duration-200 shadow-xs cursor-pointer"
                                                    title="Hide Review">
                                                <i class="fa-solid fa-eye-slash text-xs"></i>
                                            </button>
                                        @else
                                            <button type="submit"
                                                    class="w-9 h-9 flex items-center justify-center bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl hover:bg-emerald-100 transition-all duration-200 shadow-xs cursor-pointer"
                                                    title="Approve Review">
                                                <i class="fa-solid fa-check text-xs"></i>
                                            </button>
                                        @endif
                                    </form>

                                    <!-- Edit Button -->
                                    <a href="{{ route('reviews.edit', $record->id) }}"
                                       class="w-9 h-9 flex items-center justify-center bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl hover:bg-emerald-100 transition-all duration-200 shadow-xs cursor-pointer"
                                       title="Edit Review">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>

                                    <!-- SweetAlert Delete Form -->
                                    <form action="{{ route('reviews.destroy', $record->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="delete-btn w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-all duration-200 shadow-xs cursor-pointer"
                                                title="Delete Review">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty

                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-star-half-stroke text-3xl mb-2 block text-gray-300"></i>
                                <span>No Review Records Found</span>
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

                @if($reviews->hasPages())
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        {{ $reviews->links() }}
                    </div>
                @endif

            </div>

        </div>

    </div>

@endsection
