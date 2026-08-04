@extends('frontend.layouts.app')

@section('content')

    <!-- MAIN CONTAINER WITH MAX-W-7XL -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        <!-- HEADER -->
        <div class="text-center mt-6 sm:mt-12 mb-6 sm:mb-10 px-4">
            <h2 class="text-2xl sm:text-4xl font-bold text-gray-900">
                Contact Us
            </h2>

            <p class="text-xs sm:text-sm text-gray-500 mt-2 sm:mt-3 max-w-xl mx-auto">
                We'd love to hear from you. Send us a message.
            </p>
        </div>

        <!-- CONTACT SECTION -->
        <div class="mb-12 sm:mb-16">

            <!-- Success Message Alert -->
            @if(session('success'))
                <div class="mb-6 p-3 sm:p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-medium flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 mb-15 md:mb-5">

                <!-- LEFT CARD (Contact Info & Map) -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm sm:shadow p-5 sm:p-8 border border-gray-100 sm:border-none">

                    <h3 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6 text-gray-900">
                        Contact Information
                    </h3>

                    <!-- Location -->
                    <div class="flex items-start gap-3 sm:gap-4 mb-4 sm:mb-6">
                        <i class="fa-solid fa-location-dot text-red-500 text-lg sm:text-xl mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-sm sm:text-base text-gray-800">Location</h4>
                            <p class="text-xs sm:text-sm text-gray-500">
                                Lodhran, Punjab, Pakistan
                            </p>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="flex items-start gap-3 sm:gap-4 mb-4 sm:mb-6">
                        <i class="fa-solid fa-phone text-green-500 text-lg sm:text-xl mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-sm sm:text-base text-gray-800">Phone</h4>
                            <p class="text-xs sm:text-sm text-gray-500">
                                +92 300 1234567
                            </p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start gap-3 sm:gap-4 mb-4 sm:mb-6">
                        <i class="fa-solid fa-envelope text-blue-500 text-lg sm:text-xl mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-sm sm:text-base text-gray-800">Email</h4>
                            <p class="text-xs sm:text-sm text-gray-500 break-all">
                                info@example.com
                            </p>
                        </div>
                    </div>

                    <!-- MAP -->
                    <div class="mt-6 sm:mt-8">
                        <iframe
                            src="https://www.google.com/maps?q=Lodhran,Pakistan&output=embed"
                            width="100%"
                            height="280"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            class="rounded-lg sm:rounded-xl sm:h-[330px]">
                        </iframe>
                    </div>

                </div>

                <!-- RIGHT CARD (Contact Form) -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm sm:shadow p-5 sm:p-8 border border-gray-100 sm:border-none">

                    <h3 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6 text-gray-900">
                        Send Message
                    </h3>

                    <!-- FORM WITH ROUTE & CSRF -->
                    <form action="{{ route('contact-us.store') }}" method="POST">
                    @csrf

                    <!-- NAME -->
                        <div class="relative mb-4 sm:mb-5">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder=" " required
                                   class="peer w-full border border-gray-200 rounded-xl px-4 pt-5 pb-2 sm:pt-6 sm:pb-2 text-xs sm:text-sm
                                   focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">

                            <label for="name"
                                   class="absolute left-3 top-2.5 sm:top-2 text-gray-400 text-xs sm:text-base transition-all duration-200
                                   peer-placeholder-shown:top-3.5
                                   peer-placeholder-shown:text-xs sm:peer-placeholder-shown:text-base
                                   peer-placeholder-shown:text-gray-400
                                   peer-focus:top-[-10px]
                                   peer-focus:text-xs sm:peer-focus:text-sm
                                   peer-focus:text-emerald-600
                                   bg-white px-2">
                                Name
                            </label>
                            @error('name')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- EMAIL -->
                        <div class="relative mb-4 sm:mb-5">
                            <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder=" " required
                                   class="peer w-full border border-gray-200 rounded-xl px-4 pt-5 pb-2 sm:pt-6 sm:pb-2 text-xs sm:text-sm
                                   focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">

                            <label for="email"
                                   class="absolute left-3 top-2.5 sm:top-2 text-gray-400 text-xs sm:text-base transition-all duration-200
                                   peer-placeholder-shown:top-3.5
                                   peer-placeholder-shown:text-xs sm:peer-placeholder-shown:text-base
                                   peer-placeholder-shown:text-gray-400
                                   peer-focus:top-[-10px]
                                   peer-focus:text-xs sm:peer-focus:text-sm
                                   peer-focus:text-emerald-600
                                   bg-white px-2">
                                Email
                            </label>
                            @error('email')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- SUBJECT -->
                        <div class="relative mb-4 sm:mb-5">
                            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" placeholder=" "
                                   class="peer w-full border border-gray-200 rounded-xl px-4 pt-5 pb-2 sm:pt-6 sm:pb-2 text-xs sm:text-sm
                                   focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">

                            <label for="subject"
                                   class="absolute left-3 top-2.5 sm:top-2 text-gray-400 text-xs sm:text-base transition-all duration-200
                                   peer-placeholder-shown:top-3.5
                                   peer-placeholder-shown:text-xs sm:peer-placeholder-shown:text-base
                                   peer-placeholder-shown:text-gray-400
                                   peer-focus:top-[-10px]
                                   peer-focus:text-xs sm:peer-focus:text-sm
                                   peer-focus:text-emerald-600
                                   bg-white px-2">
                                Subject
                            </label>
                            @error('subject')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- MESSAGE -->
                        <div class="relative mb-5 sm:mb-6">
                            <textarea name="message" id="message" rows="4" placeholder=" " required
                                      class="peer w-full border border-gray-200 rounded-xl px-4 pt-5 pb-2 sm:pt-6 sm:pb-2 text-xs sm:text-sm
                                      focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('message') }}</textarea>

                            <label for="message"
                                   class="absolute left-3 top-2.5 sm:top-2 text-gray-400 text-xs sm:text-base transition-all duration-200
                                   peer-placeholder-shown:top-3.5
                                   peer-placeholder-shown:text-xs sm:peer-placeholder-shown:text-base
                                   peer-placeholder-shown:text-gray-400
                                   peer-focus:top-[-10px]
                                   peer-focus:text-xs sm:peer-focus:text-sm
                                   peer-focus:text-emerald-600
                                   bg-white px-2">
                                Message
                            </label>
                            @error('message')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- BUTTON -->
                        <button type="submit"
                                class="w-full bg-emerald-600 text-white py-2.5 sm:py-3 rounded-xl hover:bg-emerald-700 transition font-semibold text-sm sm:text-base cursor-pointer">
                            Send Message
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection
