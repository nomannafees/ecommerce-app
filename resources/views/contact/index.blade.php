@extends('layouts.app')

@section('content')

    <div class="rounded-2 mx-auto p-6">

        <!-- Success Message Alert -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-3 shadow-xs">
                <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
    @endif

    <!-- Card Wrapper -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

            <!-- Card Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50/50">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        Contact Messages
                    </h2>
                    <p class="text-xs text-gray-500 mt-1">
                        Manage user inquiries and form submissions
                    </p>
                </div>
            </div>

            <!-- Search Form -->
            <div class="p-6 border-b border-gray-100">
                <form action="{{ route('contacts.index') }}" method="GET">

                    <div class="grid grid-cols-1 md:grid-cols-[280px_auto_auto] gap-3 items-center">

                        <!-- Search Input -->
                        <div class="relative w-72">
                            <!-- Search Icon -->
                            <span
                                class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-500 transition-colors duration-200 pointer-events-none">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>

                            <!-- Input Field -->
                            <input
                                type="text"
                                name="search"
                                id="search"
                                value="{{ request('search') }}"
                                placeholder=" "
                                class="peer w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg bg-gray-50/50 text-gray-700 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                            <!-- Floating Label -->
                            <label
                                for="search"
                                class="absolute left-10 top-2 text-gray-400 text-sm pointer-events-none transition-all duration-200
        peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-4 peer-focus:left-3 peer-focus:bg-white peer-focus:px-1
        peer-[:not(:placeholder-shown)]:-translate-y-4 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-1 peer-[:not(:placeholder-shown)]:text-xs">
                                Search Messages...
                            </label>
                        </div>

                        <div>
                            <!-- Search Button -->
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg transition duration-200 shadow-sm cursor-pointer text-sm font-medium">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                <span>Search</span>
                            </button>

                            <!-- Clear Button -->
                            <a href="{{ route('contacts.index') }}"
                               class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg text-center transition duration-200 shadow-sm text-sm font-medium">
                                <i class="fa-solid fa-times text-xs"></i>
                                <span>Clear</span>
                            </a>
                        </div>

                    </div>

                </form>
            </div>

            <!-- Table Container -->
            <div class="px-6 pb-6 pt-4 overflow-x-auto">

                <!-- Table with Outer Border and Sharp Edges -->
                <table class="w-full text-left border-collapse border border-gray-200">

                    <!-- Table Head -->
                    <thead>
                    <tr class="bg-gray-200/30 border-b border-gray-300 text-gray-800 text-xs uppercase font-bold">
                        <th class="px-4 py-3.5 border-r border-gray-300 w-12">#</th>
                        <th class="px-4 py-3.5 border-r border-gray-300">Name</th>
                        <th class="px-4 py-3.5 border-r border-gray-300">Email</th>
                        <th class="px-4 py-3.5 border-r border-gray-300">Subject</th>
                        <th class="px-4 py-3.5 border-r border-gray-300">Message</th>
                        <th class="px-4 py-3.5 border-r border-gray-300">Date</th>
                        <th class="px-4 py-3.5 text-center w-28">Action</th>
                    </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody class="divide-y divide-gray-200 text-sm text-gray-700">

                    @if(isset($contacts) && count($contacts) > 0)

                        @foreach($contacts as $key => $contact)

                            <tr class="hover:bg-gray-50/50 transition-colors duration-150">

                                <!-- ID / Counter -->
                                <td class="px-4 py-3.5 border-r border-gray-200 text-gray-500 font-medium">
                                    {{ method_exists($contacts, 'firstItem') ? $contacts->firstItem() + $key : $key + 1 }}
                                </td>

                                <!-- Sender Name -->
                                <td class="px-4 py-3.5 border-r border-gray-200 font-semibold text-gray-800 whitespace-nowrap">
                                    {{ $contact->name }}
                                </td>

                                <!-- Email -->
                                <td class="px-4 py-3.5 border-r border-gray-200 text-gray-600 whitespace-nowrap">
                                    <a href="mailto:{{ $contact->email }}" class="text-emerald-600 hover:underline">
                                        {{ $contact->email }}
                                    </a>
                                </td>

                                <!-- Subject -->
                                <td class="px-4 py-3.5 border-r border-gray-200">
                                    @if($contact->subject)
                                        <span class="font-medium text-gray-800 truncate block max-w-xs">
                                            {{ $contact->subject }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 italic text-xs">
                                            No Subject
                                        </span>
                                    @endif
                                </td>

                                <!-- Message Body -->
                                <td class="px-4 py-3.5 border-r border-gray-200 text-gray-600">
                                    <span class="block max-w-xs truncate" title="{{ $contact->message }}">
                                        {{ \Illuminate\Support\Str::limit($contact->message, 40, '...') }}
                                    </span>
                                </td>

                                <!-- Date -->
                                <td class="px-4 py-3.5 border-r border-gray-200 text-gray-600 whitespace-nowrap">
                                    {{ $contact->created_at ? $contact->created_at->format('d M Y') : '-' }}
                                </td>

                                <!-- Action Buttons -->
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center justify-center gap-2">

                                        <!-- Delete Form -->
                                        <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button"
                                                    class="delete-btn w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-all duration-200 shadow-xs cursor-pointer"
                                                    title="Delete Message">
                                                <i class="fa-solid fa-trash text-xs pointer-events-none"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>

                        @endforeach

                    @else

                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-envelope-open text-3xl mb-2 block text-gray-300"></i>
                                <span>No Contact Messages Found</span>
                            </td>
                        </tr>

                    @endif

                    </tbody>

                </table>

                <!-- Pagination -->
                @if(method_exists($contacts, 'hasPages') && $contacts->hasPages())
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        {{ $contacts->links() }}
                    </div>
                @endif

            </div>

        </div>

    </div>

@endsection
