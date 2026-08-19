@extends('frontend.layouts.app')

@section('content')

    <!-- MAIN CONTAINER -->
    <div class="container mx-auto px-3 sm:px-6 md:px-7 py-6 sm:pb-10 sm:pt-6">

        <!-- TITLE -->
        <div class="text-center mb-2 sm:mb-4">
            <h1 class="text-2xl  font-bold text-gray-900">
                All Products
            </h1>
            <p class="text-[16px] text-gray-500 max-w-2xl mx-auto">
                Choose your favorite products and add them to your cart.
            </p>
        </div>

        <!-- GRID CONTAINER WITH ID -->
        <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 mb-8 gap-3">
            @include('frontend.partials.product-cards', ['products' => $products])
        </div>

        <!-- NO MORE PRODUCTS BUTTON STYLE -->
        <div id="no-more-products" class="text-center -mt-6  mb-10 sm:mb-3 lg:mb-0 md:mt-1 hidden">
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

                // Shimmer Effect HTML (Scroll karte waqt grid mein append hoga)
                let shimmerHtml = `
                    @for($i = 0; $i < 6; $i++)
                <div class="product-shimmer bg-white rounded-md sm:rounded-lg shadow-xs border border-gray-200 overflow-hidden flex flex-col h-full w-full animate-pulse">
                    <div class="bg-gray-200 h-50 xs:h-44 sm:h-60 2xl:h-57 md:h-52 lg:h-55 w-full"></div>
                    <div class="px-2 py-2 flex-grow flex flex-col justify-between gap-2">
                        <div>
                            <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                            <div class="h-3 bg-gray-200 rounded w-full"></div>
                        </div>
                        <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="h-5 bg-gray-200 rounded w-1/3"></div>
                            <div class="h-5 bg-gray-200 rounded w-1/4"></div>
                        </div>
                    </div>
                </div>
@endfor
                `;

                $('#product-grid').append(shimmerHtml);

                $.ajax({
                    url: "{{ route('frontendProduct') }}?page=" + page,
                    type: "GET",
                    success: function(response) {
                        // Response aate hi shimmer cards ko remove kar dein
                        $('.product-shimmer').remove();

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
                        // Error aane par bhi shimmer cards remove kar dein
                        $('.product-shimmer').remove();
                        isLoading = false;
                    }
                });
            }
        });
    </script>
@endpush
