@extends('frontend.layouts.app')

@section('content')

<div class="min-h-[80vh] flex items-center justify-center bg-slate-50/50 px-4 py-16">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-8 md:p-10 text-center transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)]">

        <!-- Success Icon with Pulse Effect -->
        <div class="relative w-20 h-20 mx-auto mb-8 flex items-center justify-center">
            <div class="absolute inset-0 rounded-full bg-emerald-50 animate-ping opacity-75"></div>
            <div class="relative w-20 h-20 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-10 h-10 text-emerald-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2.5"
                        d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <!-- Typography Enhancements -->
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-3">
            Thank You!
        </h1>

        <p class="text-base font-medium text-gray-800 mb-2">
            Your order has been placed successfully.
        </p>

        <p class="text-sm leading-relaxed text-gray-500 max-w-sm mx-auto mb-8">
            We have received your order and our team will contact you soon regarding delivery details.
        </p>

        <!-- Premium Cash on Delivery Badge -->
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 mb-8 flex items-start gap-3 text-left">
            <div class="p-2 bg-emerald-50 text-emerald-700 rounded-lg shrink-0 mt-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">
                    Cash on Delivery Selected
                </p>
                <p class="text-xs text-gray-500 mt-0.5 leading-normal">
                    Please keep the payment ready at the time of delivery.
                </p>
            </div>
        </div>

        <!-- Modern Primary Button -->
        <a href="{{ route('index') }}"
            class="inline-flex items-center justify-center w-full sm:w-auto px-8 py-3.5 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 shadow-sm hover:shadow-md transition-all duration-200 tracking-wide">
            Continue Shopping
        </a>

    </div>

</div>

@endsection