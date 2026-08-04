@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header Section -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Admin Profile</h1>
                <p class="text-xs text-gray-500 mt-1">Manage your personal information, payment methods, and account security.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <!-- LEFT SIDE: Main Profile Information (Bara Card) -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm">

                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Personal Information</h2>
                        <p class="text-xs text-gray-500">Update your profile details and account information</p>
                    </div>
                </div>

                <!-- Profile Card Success Alert -->
                @if(session('info_success') || session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ session('info_success') ?? session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('admin-info.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Avatar Upload Section -->
                    <div class="flex items-center gap-6 mb-8 bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                        <div class="relative w-20 h-20 shrink-0">

                            <!-- Image Preview (Jab Image Exist Kare) -->
                            <img id="avatar-preview"
                                 src="{{ (isset($adminInfo) && $adminInfo->image) ? asset('storage/' . $adminInfo->image) : '#' }}"
                                 class="{{ (isset($adminInfo) && $adminInfo->image) ? '' : 'hidden' }} w-20 h-20 rounded-2xl object-cover border-2 border-emerald-500 shadow-sm">

                            <!-- Icon Fallback (Jab Image Na Ho ya Delete Ho Jaye) -->
                            <div id="avatar-icon-placeholder"
                                 class="{{ (isset($adminInfo) && $adminInfo->image) ? 'hidden' : '' }} w-20 h-20 rounded-2xl bg-emerald-50 border-2 border-dashed border-emerald-300 flex items-center justify-center text-emerald-600 shadow-sm">
                                <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>

                            <!-- Upload Camera Icon (Bottom Right) -->
                            <label for="admin-image" class="absolute -bottom-2 -right-2 bg-emerald-600 hover:bg-emerald-700 text-white p-2 rounded-xl cursor-pointer shadow-md transition-all z-10">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h0.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </label>
                            <input type="file" id="admin-image" name="image" class="hidden" accept="image/*" onchange="previewImage(event)">

                            <!-- Delete Cut Icon (Top Right) -->
                            <button type="button"
                                    id="remove-image-btn"
                                    onclick="removeProfileImage()"
                                    class="{{ (isset($adminInfo) && $adminInfo->image) ? '' : 'hidden' }} absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white p-1.5 rounded-full shadow-md transition-all cursor-pointer z-10">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div>
                            <h4 class="text-xs font-semibold text-gray-800">Profile Picture</h4>
                            <p class="text-[11px] text-gray-400 mt-0.5">JPG, PNG, or WEBP. Max size 2MB.</p>
                            @error('image')
                            <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Input Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/30 text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                            @error('name')
                            <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Email Address</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-100 text-xs text-gray-500 cursor-not-allowed">
                        </div>

                        <!-- JazzCash Number -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">JazzCash Number</label>
                            <input type="text" name="jazzcash_no" value="{{ old('jazzcash_no', $adminInfo->jazzcash_no ?? '') }}" placeholder="03001234567"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/30 text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                            @error('jazzcash_no')
                            <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- EasyPaisa Number -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">EasyPaisa Number</label>
                            <input type="text" name="easypaisa_no" value="{{ old('easypaisa_no', $adminInfo->easypaisa_no ?? '') }}" placeholder="03121234567"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/30 text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                            @error('easypaisa_no')
                            <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Address</label>
                        <textarea name="address" rows="3" placeholder="Enter complete office/home address..."
                                  class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/30 text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all resize-none">{{ old('address', $adminInfo->address ?? '') }}</textarea>
                        @error('address')
                        <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-6 py-3 rounded-xl shadow-md shadow-emerald-600/20 transition-all inline-flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Save Information</span>
                    </button>
                </form>
            </div>

            <!-- RIGHT SIDE: Password Update Security (Chota Card) -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">

                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Change Password</h2>
                        <p class="text-xs text-gray-500">Keep your account secure</p>
                    </div>
                </div>

                <!-- Password Card Success Alert -->
                @if(session('password_success'))
                    <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
                        {{ session('password_success') }}
                    </div>
                @endif

            <!-- Password Specific Errors Alert Box -->
                @if($errors->has('current_password') || $errors->has('password'))
                    <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs space-y-1">
                        @foreach($errors->get('current_password') as $error)
                            <p class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0"></span>
                                <span>{{ $error }}</span>
                            </p>
                        @endforeach
                        @foreach($errors->get('password') as $error)
                            <p class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0"></span>
                                <span>{{ $error }}</span>
                            </p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('admin-info.update', $adminInfo->id ?? $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4 mb-6">
                        <!-- Current Password -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Current Password</label>
                            <input type="password" name="current_password" required placeholder="••••••••"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/30 text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                        </div>

                        <!-- New Password -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">New Password</label>
                            <input type="password" name="password" required placeholder="••••••••"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/30 text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                        </div>

                        <!-- Confirm New Password -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Confirm New Password</label>
                            <input type="password" name="password_confirmation" required placeholder="••••••••"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/30 text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-gray-900 hover:bg-black text-white text-xs font-semibold py-3 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <span>Update Password</span>
                    </button>
                </form>
            </div>

        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('avatar-preview');
                const placeholder = document.getElementById('avatar-icon-placeholder');

                output.src = reader.result;
                output.classList.remove('hidden');
                placeholder.classList.add('hidden');
                document.getElementById('remove-image-btn').classList.remove('hidden');
            };
            if(event.target.files && event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        function removeProfileImage() {
            const adminInfoId = "{{ $adminInfo->id ?? 0 }}";

            // Agar DB me record/image exist karti hai toh backend API hit kar ke reload karein
            if (adminInfoId != "0") {
                fetch(`/admin/admin-info/${adminInfoId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Database se delete hone ke baad page reload ho jayega
                            window.location.reload();
                        } else {
                            console.error(data.message || 'Error deleting image.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                // Agar sirf temporary input clear karni hai (un-saved image)
                resetAvatarUI();
            }
        }

        function resetAvatarUI() {
            document.getElementById('admin-image').value = "";
            document.getElementById('avatar-preview').classList.add('hidden');
            document.getElementById('avatar-icon-placeholder').classList.remove('hidden');
            document.getElementById('remove-image-btn').classList.add('hidden');
        }
    </script>
@endsection
