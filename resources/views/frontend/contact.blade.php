@extends('frontend.layouts.app')

@section('content')

<!-- HEADER -->
<div class="text-center mt-12 mb-10">
    <h2 class="text-4xl font-bold text-gray-900">
        Contact Us
    </h2>

    <p class="text-gray-500 mt-3">
        We'd love to hear from you. Send us a message.
    </p>
</div>

<!-- CONTACT SECTION -->
<div class="max-w-7xl mx-auto px-6 mb-16">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- LEFT CARD -->
        <div class="bg-white rounded-2xl shadow-lg p-8">

            <h3 class="text-2xl font-bold mb-6">
                Contact Information
            </h3>

            <!-- Location -->
            <div class="flex items-start gap-4 mb-6">
                <i class="fa-solid fa-location-dot text-red-500 text-xl mt-1"></i>
                <div>
                    <h4 class="font-semibold">Location</h4>
                    <p class="text-gray-500">
                        Lodhran, Punjab, Pakistan
                    </p>
                </div>
            </div>

            <!-- Phone -->
            <div class="flex items-start gap-4 mb-6">
                <i class="fa-solid fa-phone text-green-500 text-xl mt-1"></i>
                <div>
                    <h4 class="font-semibold">Phone</h4>
                    <p class="text-gray-500">
                        +92 300 1234567
                    </p>
                </div>
            </div>

            <!-- Email -->
            <div class="flex items-start gap-4 mb-6">
                <i class="fa-solid fa-envelope text-blue-500 text-xl mt-1"></i>
                <div>
                    <h4 class="font-semibold">Email</h4>
                    <p class="text-gray-500">
                        info@example.com
                    </p>
                </div>
            </div>

            <!-- MAP -->
            <div class="mt-8">
                <iframe
                    src="https://www.google.com/maps?q=Lodhran,Pakistan&output=embed"
                    width="100%"
                    height="330"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    class="rounded-xl">
                </iframe>
            </div>

        </div>

        <!-- RIGHT CARD -->
        <div class="bg-white rounded-2xl shadow-lg p-8">

            <h3 class="text-2xl font-bold mb-6">
                Send Message
            </h3>

            <form action="#" >

                <!-- NAME -->
                <div class="relative mb-5">
                    <input type="text" name="name" id="name" placeholder=" "
                        class="peer w-full border border-gray-200 rounded-xl px-4 pt-6 pb-2
            focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">

                    <label for="name"
                        class="absolute left-3 top-2 text-gray-400 text-base transition-all duration-200
            peer-placeholder-shown:top-3.5 
            peer-placeholder-shown:text-base 
            peer-placeholder-shown:text-gray-400
            peer-focus:top-[-10px] 
            peer-focus:text-sm 
            peer-focus:text-emerald-600
            bg-white px-2">
                        Name
                    </label>
                </div>

                <!-- EMAIL -->
                <div class="relative mb-5">
                    <input type="email" name="email" id="email" placeholder=" "
                        class="peer w-full border border-gray-200 rounded-xl px-4 pt-6 pb-2
            focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">

                    <label for="email"
                        class="absolute left-3 top-2 text-gray-400 text-base transition-all duration-200
            peer-placeholder-shown:top-3.5 
            peer-placeholder-shown:text-base 
            peer-placeholder-shown:text-gray-400
            peer-focus:top-[-10px] 
            peer-focus:text-sm 
            peer-focus:text-emerald-600
            bg-white px-2">
                        Email
                    </label>
                </div>

                <!-- SUBJECT -->
                <div class="relative mb-5">
                    <input type="text" name="subject" id="subject" placeholder=" "
                        class="peer w-full border border-gray-200 rounded-xl px-4 pt-6 pb-2
            focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">

                    <label for="subject"
                        class="absolute left-3 top-2 text-gray-400 text-base transition-all duration-200
            peer-placeholder-shown:top-3.5 
            peer-placeholder-shown:text-base 
            peer-placeholder-shown:text-gray-400
            peer-focus:top-[-10px] 
            peer-focus:text-sm 
            peer-focus:text-emerald-600
            bg-white px-2">
                        Subject
                    </label>
                </div>

                <!-- MESSAGE -->
                <div class="relative mb-6">
                    <textarea name="message" id="message" rows="5" placeholder=" "
                        class="peer w-full border border-gray-200 rounded-xl px-4 pt-6 pb-2
            focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>

                    <label for="message"
                        class="absolute left-3 top-2 text-gray-400 text-base transition-all duration-200
            peer-placeholder-shown:top-3.5 
            peer-placeholder-shown:text-base 
            peer-placeholder-shown:text-gray-400
            peer-focus:top-[-10px] 
            peer-focus:text-sm 
            peer-focus:text-emerald-600
            bg-white px-2">
                        Message
                    </label>
                </div>

                <!-- BUTTON -->
                <button type="submit"
                    class="w-full bg-emerald-600 text-white py-3 rounded-xl hover:bg-emerald-700 transition">
                    Send Message
                </button>

            </form>

        </div>

    </div>

</div>

@endsection