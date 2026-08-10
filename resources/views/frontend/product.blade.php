@extends('frontend.layouts.app')

@section('content')

    <!-- MAIN CONTAINER -->
    <div class="container mx-auto px-3 sm:px-6 md:px-7 py-6 sm:pb-10 sm:pt-6">

        <!-- TITLE -->
        <div class="text-center mb-2 sm:mb-4">
            <h2 class="text-2xl sm:text-4xl font-bold text-gray-900">
                All Products
            </h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-1 sm:mt-2 max-w-2xl mx-auto">
                Choose your favorite products and add them to your cart.
            </p>
        </div>

        <!-- GRID CONTAINER WITH ID -->
        <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 mb-8 gap-3">
            @include('frontend.partials.product-cards', ['products' => $products])
        </div>

        <!-- LOADING SPINNER -->
        <div id="loading-spinner" class="text-center py-6 hidden">
            <i class="fa-solid fa-spinner fa-spin text-2xl text-emerald-600"></i>
            <p class="text-xs text-gray-500 mt-1">Loading more products...</p>
        </div>

        <!-- NO MORE PRODUCTS BUTTON STYLE -->
        <div id="no-more-products" class="text-center mt-6 hidden">
            <span class="inline-flex items-center gap-2 bg-gray-700 text-white text-xs sm:text-sm font-medium px-5 py-2.5 rounded-md shadow-md cursor-default">
                <i class="fa-solid fa-circle-check text-emerald-400"></i> No More Products
            </span>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        let page = 1;
        let hasMorePages = {{ $products->hasMorePages() ? 'true' : 'false' }};
        let isLoading = false;

        $('main').scroll(function() {
            let $main = $(this);

            if($main.scrollTop() + $main.innerHeight() >= $main[0].scrollHeight - 300) {

                if (!hasMorePages) {
                    $('#no-more-products').removeClass('hidden');
                    return;
                }

                if(isLoading) return;

                isLoading = true;
                page++;
                $('#loading-spinner').removeClass('hidden');

                $.ajax({
                    url: "{{ route('frontendProduct') }}?page=" + page,
                    type: "GET",
                    success: function(response) {
                        $('#loading-spinner').addClass('hidden');

                        if($.trim(response) === "") {
                            hasMorePages = false;
                            $('#no-more-products').removeClass('hidden');
                        } else {
                            $('#product-grid').append(response);
                            isLoading = false;
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        $('#loading-spinner').addClass('hidden');
                        isLoading = false;
                    }
                });
            }
        });
    </script>
@endpush
