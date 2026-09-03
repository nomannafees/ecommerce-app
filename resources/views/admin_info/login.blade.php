<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - ShopNest</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

<!-- Background Image & Light Overlay -->
<div class="absolute inset-0 z-0">
    <img src="{{ asset('upload/favicon/WhatsApp Image 2026-08-12 at 4.59.48 PM.jpeg') }}"
         alt="Background"
         class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-white/20 backdrop-blur-[2px]"></div>
</div>

<!-- Main Wrapper Card -->
<div class="w-full max-w-md bg-white/95 backdrop-blur-xl rounded-3xl shadow-md border border-white/20 px-5 py-3 relative z-10 overflow-hidden">

    <!-- Top Decorative Gradient Glow -->
    <div class="absolute -top-24 -right-24 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Header Section -->
    <div class="text-center mb-5 relative z-10">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 mb-2.5 shadow-inner ring-4 ring-emerald-50">
            <i class="fa-solid fa-shield-halved text-xl"></i>
        </div>
        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Admin Portal</h2>
        <p class="text-xs text-gray-500 mt-1">Enter your administrative credentials to continue</p>
    </div>

    <!-- Login Form -->
    <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4 relative z-10">
    @csrf

    <!-- Email Field -->
        <div>
            <label for="email" class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-600">Email Address</label>
            <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                <i class="fa-regular fa-envelope text-sm"></i>
            </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                       placeholder="admin@shopnest.com"
                       class="bg-gray-50 border rounded-xl w-full pl-10 pr-4 py-3 text-sm text-gray-800 placeholder-gray-400 outline-none transition-all @error('email') border-red-500 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 @else border-gray-200 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 @enderror">
            </div>
            @error('email')
            <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password Field with Eye Icon -->
        <div>
            <label for="password" class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-600">Password</label>
            <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                <i class="fa-solid fa-lock text-sm"></i>
            </span>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       placeholder="••••••••"
                       class="bg-gray-50 border rounded-xl w-full pl-10 pr-10 py-3 text-sm text-gray-800 placeholder-gray-400 outline-none transition-all @error('password') border-red-500 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 @else border-gray-200 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 @enderror">
                <button type="button" onclick="togglePasswordVisibility('password', 'passwordIcon')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer">
                    <i id="passwordIcon" class="fa-regular fa-eye text-sm"></i>
                </button>
            </div>
            @error('password')
            <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
            @enderror
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center justify-between text-sm pt-0.5">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="remember" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 accent-emerald-600">
                <span class="text-gray-600 font-medium text-xs">Remember this device</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-semibold py-3 rounded-xl shadow-lg shadow-emerald-600/20 transition duration-300 cursor-pointer flex items-center justify-center gap-2">
            <i class="fa-solid fa-right-to-bracket text-sm"></i>
            <span class="text-sm tracking-wide">Access Dashboard</span>
        </button>
    </form>

    <!-- Footer Note -->
    <div class="mt-4 text-center">
        <p class="text-xs text-gray-400">
            &copy; {{ date('Y') }} ShopNest System. Secure Admin Access.
        </p>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>

</body>
</html>
