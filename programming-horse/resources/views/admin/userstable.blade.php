<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Users Table') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Edit User Form -->
                    @if (isset($editUser))
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">Edit User: {{ $editUser->user_name }}</h3>
                            <form method="POST" action="{{ route('users.update', $editUser->id) }}">
                                @csrf
                                @method('PUT')

                                <!-- User Name -->
                                <div class="mb-4">
                                    <label for="user_name" class="block text-sm font-medium text-gray-700">User Name</label>
                                    <input type="text" id="user_name" name="user_name" value="{{ $editUser->user_name }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           required>
                                    @error('user_name')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- User Role -->
                                <div class="mb-4">
                                    <label for="user_role" class="block text-sm font-medium text-gray-700">User Role</label>
                                    <select id="user_role" name="user_role"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            required>
                                        <option value="User" {{ $editUser->user_role === 'User' ? 'selected' : '' }}>User</option>
                                        <option value="Admin" {{ $editUser->user_role === 'Admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    @error('user_role')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="flex items-center justify-end">
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Update User
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <!-- Display Users Data in a Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">ID</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">User Name</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Email</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">User Role</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Style Sheet</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Avatar URL</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Email Verified At</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Created At</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Updated At</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $user->id }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $user->user_name }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $user->email }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $user->user_role }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $user->style_sheet }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $user->avatar_url }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $user->email_verified_at }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $user->created_at }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $user->updated_at }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            <!-- Edit Link -->
                                            <a href="{{ route('userstable', ['editUserId' => $user->id]) }}" class="text-blue-500 hover:underline">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }
            th, td {
                padding: 8px;
            }
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
</x-app-layout>

