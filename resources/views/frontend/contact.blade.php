@extends('frontend.layouts.app')

@section('content')

    <div class="container mx-auto px-3 sm:px-6 md:px-7 py-6 sm:py-8">

        {{-- PAGE HEADER --}}
        <div class="max-w-2xl mx-auto text-center mb-7 sm:mb-10">

            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold tracking-tight text-gray-900">
                Contact Us
            </h1>

            <p class="mt-2 text-sm sm:text-base text-gray-500">
                Have a question? Send us a message and our team will get back to you.
            </p>
        </div>


        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="max-w-4xl mx-auto mb-6 flex items-center gap-3
                    rounded-xl border border-emerald-200 bg-emerald-50
                    px-4 py-3 text-sm font-medium text-emerald-700">

                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-check"></i>
                </div>

                <span>{{ session('success') }}</span>
            </div>
        @endif


        {{-- MAIN CONTACT GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-6 items-stretch">

            {{-- LEFT CARD --}}
            <div class="bg-white rounded-2xl border border-gray-200
                    shadow-[0_8px_30px_rgba(0,0,0,0.04)]
                    overflow-hidden flex flex-col">

                {{-- Card Header --}}
                <div class="px-5 sm:px-6 pt-5 sm:pt-6 pb-4 border-b border-gray-100">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900">
                        Contact Information
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Reach us through any of the following options.
                    </p>
                </div>


                {{-- Contact Details --}}
                <div class="p-5 sm:p-6 space-y-4">

                    {{-- LOCATION --}}
                    <div class="group flex items-center gap-4 p-3.5 rounded-xl
                            border border-gray-100 hover:border-gray-200
                            hover:bg-gray-50 transition">

                        <div class="w-11 h-11 rounded-xl bg-red-50
                                flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-location-dot text-red-500"></i>
                        </div>

                        <div class="min-w-0">
                            <h4 class="text-sm font-semibold text-gray-900">
                                Location
                            </h4>

                            <p class="text-sm text-gray-500 mt-0.5">
                                Lodhran, Punjab, Pakistan
                            </p>
                        </div>
                    </div>


                    {{-- PHONE --}}
                    <div class="group flex items-center gap-4 p-3.5 rounded-xl
                            border border-gray-100 hover:border-gray-200
                            hover:bg-gray-50 transition">

                        <div class="w-11 h-11 rounded-xl bg-emerald-50
                                flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-phone text-emerald-600"></i>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-gray-900">
                                Phone
                            </h4>

                            <p class="text-sm text-gray-500 mt-0.5">
                                +92 300 1234567
                            </p>
                        </div>
                    </div>


                    {{-- EMAIL --}}
                    <div class="group flex items-center gap-4 p-3.5 rounded-xl
                            border border-gray-100 hover:border-gray-200
                            hover:bg-gray-50 transition">

                        <div class="w-11 h-11 rounded-xl bg-blue-50
                                flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-envelope text-blue-600"></i>
                        </div>

                        <div class="min-w-0">
                            <h4 class="text-sm font-semibold text-gray-900">
                                Email
                            </h4>

                            <p class="text-sm text-gray-500 mt-0.5 break-all">
                                info@example.com
                            </p>
                        </div>
                    </div>

                </div>


                {{-- MAP --}}
                <div class="px-5 sm:px-6 pb-5 sm:pb-6 mt-auto">
                    <div class="overflow-hidden rounded-xl border border-gray-200">
                        <iframe
                                src="https://www.google.com/maps?q=Lodhran,Pakistan&output=embed"
                                width="100%"
                                height="210"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                class="w-full h-[210px] sm:h-[230px]">
                        </iframe>
                    </div>
                </div>

            </div>


            {{-- RIGHT CARD --}}
            <div class="bg-white rounded-2xl border border-gray-200
                    shadow-[0_8px_30px_rgba(0,0,0,0.04)]
                    overflow-hidden flex flex-col">

                {{-- Card Header --}}
                <div class="px-5 sm:px-6 pt-5 sm:pt-6 pb-4 border-b border-gray-100">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900">
                        Send Message
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Fill in the form and we'll respond as soon as possible.
                    </p>
                </div>


                <form action="{{ route('contact-us.store') }}"
                      method="POST"
                      class="p-5 sm:p-6 flex flex-col flex-1">

                    @csrf


                    {{-- NAME --}}
                    <div class="mb-4">
                        <label for="name"
                               class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Name
                        </label>

                        <div class="relative">
                            <i class="fa-regular fa-user absolute left-4 top-1/2
                                  -translate-y-1/2 text-gray-400 text-sm"></i>

                            <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    value="{{ old('name') }}"
                                    placeholder="Enter your name"
                                    required
                                    class="w-full h-11 rounded-xl border border-gray-200
                                   bg-gray-50/50 pl-11 pr-4 text-sm text-gray-800
                                   placeholder:text-gray-400
                                   focus:bg-white focus:outline-none
                                   focus:border-gray-400 focus:ring-4
                                   focus:ring-gray-100 transition">
                        </div>

                        @error('name')
                        <span class="text-red-500 text-xs mt-1 block">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>


                    {{-- EMAIL --}}
                    <div class="mb-4">
                        <label for="email"
                               class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Email
                        </label>

                        <div class="relative">
                            <i class="fa-regular fa-envelope absolute left-4 top-1/2
                                  -translate-y-1/2 text-gray-400 text-sm"></i>

                            <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    value="{{ old('email') }}"
                                    placeholder="Enter your email"
                                    required
                                    class="w-full h-11 rounded-xl border border-gray-200
                                   bg-gray-50/50 pl-11 pr-4 text-sm text-gray-800
                                   placeholder:text-gray-400
                                   focus:bg-white focus:outline-none
                                   focus:border-gray-400 focus:ring-4
                                   focus:ring-gray-100 transition">
                        </div>

                        @error('email')
                        <span class="text-red-500 text-xs mt-1 block">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>


                    {{-- SUBJECT --}}
                    <div class="mb-4">
                        <label for="subject"
                               class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Subject
                        </label>

                        <div class="relative">
                            <i class="fa-regular fa-message absolute left-4 top-1/2
                                  -translate-y-1/2 text-gray-400 text-sm"></i>

                            <input
                                    type="text"
                                    name="subject"
                                    id="subject"
                                    value="{{ old('subject') }}"
                                    placeholder="Message subject"
                                    class="w-full h-11 rounded-xl border border-gray-200
                                   bg-gray-50/50 pl-11 pr-4 text-sm text-gray-800
                                   placeholder:text-gray-400
                                   focus:bg-white focus:outline-none
                                   focus:border-gray-400 focus:ring-4
                                   focus:ring-gray-100 transition">
                        </div>

                        @error('subject')
                        <span class="text-red-500 text-xs mt-1 block">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>


                    {{-- MESSAGE --}}
                    <div class="mb-5">
                        <label for="message"
                               class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Message
                        </label>

                        <textarea
                                name="message"
                                id="message"
                                rows="5"
                                placeholder="Write your message..."
                                required
                                class="w-full min-h-[125px] resize-none rounded-xl
                               border border-gray-200 bg-gray-50/50
                               px-4 py-3 text-sm text-gray-800
                               placeholder:text-gray-400
                               focus:bg-white focus:outline-none
                               focus:border-gray-400 focus:ring-4
                               focus:ring-gray-100 transition">{{ old('message') }}</textarea>

                        @error('message')
                        <span class="text-red-500 text-xs mt-1 block">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>


                    {{-- BUTTON --}}
                    <button
                            type="submit"
                            class="mt-auto w-full h-12 rounded-xl
                           bg-gray-900 text-white
                           hover:bg-black
                           active:scale-[0.99]
                           transition-all duration-200
                           font-semibold text-sm
                           flex items-center justify-center gap-2
                           cursor-pointer">

                        <span>Send Message</span>

                        <i class="fa-solid fa-arrow-right text-xs"></i>

                    </button>

                </form>

            </div>

        </div>

    </div>

@endsection