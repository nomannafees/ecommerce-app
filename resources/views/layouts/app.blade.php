<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link  rel="icon" type="image/png" href="{{ asset('upload/frontent/favicon2.jpg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>



<body class="bg-gray-100 min-h-screen overflow-hidden">

    <div id="app">

        <!-- Apply Dashboard Code -->
        <div class="flex bg-gray-100 h-screen ">


            @if(!request()->is('login') && !request()->is('register'))

            <!-- Side bar -->
            @include('layouts.sidebar')

            @endif

            <!-- Main content -->
            <main class="flex-1 h-screen overflow-y-scroll">


                @if(!request()->is('login') && !request()->is('register'))
                @include('layouts.header')
                @endif


                @yield('content')


            </main>

        </div>


    </div>


    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        function OpenNav() {
            document.getElementById("mySidenav").classList.add("translate-x-0");
            document.getElementById("mySidenav").classList.remove("-translate-x-64");
        }

        function CloseNav() {
            document.getElementById("mySidenav").classList.add("-translate-x-64");
            document.getElementById("mySidenav").classList.remove("translate-x-0");
        }

        function toggleDropdown() {
            document.getElementById("myDropdown").classList.toggle("hidden");
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const imageInput = document.getElementById('main_image');

            if (imageInput) {

                imageInput.addEventListener('change', function(e) {

                    const file = e.target.files[0];

                    if (file) {

                        const reader = new FileReader();

                        reader.onload = function(event) {

                            const previewImage = document.getElementById('preview-image');
                            const previewText = document.getElementById('preview-text');

                            previewImage.src = event.target.result;
                            previewImage.classList.remove('hidden');

                            if (previewText) {
                                previewText.classList.add('hidden');
                            }
                        };

                        reader.readAsDataURL(file);
                    }

                });

            }

        });
    </script>

</body>

</html>
