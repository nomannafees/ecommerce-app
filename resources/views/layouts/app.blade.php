<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('upload/favicon/images.jpg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Select2 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        table thead tr th {
            font-size: 14px !important;
        }

        table thead tr {
            background-color: #e5e7eb4d !important;
        }
    </style>

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

        let backdrop = document.getElementById("sidebarBackdrop");
        if (backdrop) backdrop.classList.remove("hidden");
    }

    function CloseNav() {
        document.getElementById("mySidenav").classList.add("-translate-x-64");
        document.getElementById("mySidenav").classList.remove("translate-x-0");

        let backdrop = document.getElementById("sidebarBackdrop");
        if (backdrop) backdrop.classList.add("hidden");
    }

    function toggleDropdown() {
        document.getElementById("myDropdown").classList.toggle("hidden");
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const imageInput = document.getElementById('main_image');

        if (imageInput) {

            imageInput.addEventListener('change', function (e) {

                const file = e.target.files[0];

                if (file) {

                    const reader = new FileReader();

                    reader.onload = function (event) {

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

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Global Delete Confirmation Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('click', function (e) {
            // Delete button ya uske parent par click detect karein
            const deleteBtn = e.target.closest('.delete-btn');

            if (deleteBtn) {
                e.preventDefault();
                const form = deleteBtn.closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Are you sure you want to delete this record?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#059669', // Emerald-600 (Project theme)
                    cancelButtonColor: '#ef4444',  // Red-500
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'px-4 py-2 rounded-lg text-sm font-medium',
                        cancelButton: 'px-4 py-2 rounded-lg text-sm font-medium'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    });
</script>


<!-- jQuery (Select2 ko jQuery chahiye hoti hai) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Category Select2
        $('#category_id').select2({
            placeholder: "Select Category *",
            allowClear: false,
            width: '100%'
        });

        // Brand Select2
        $('#brand_id').select2({
            placeholder: "Select Brand",
            allowClear: false,
            width: '100%'
        });

        $('#product_type').select2({
            placeholder: "Select Product Type *",
            allowClear: false,
            width: '100%'
        });

        $(document).ready(function() {
            $('#parent_id').select2({
                placeholder: "Select Parent Category",
                allowClear: false,
                width: '100%'
            });
        });
    });
</script>


</body>

</body>

</html>
