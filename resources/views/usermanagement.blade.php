<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 p-4 text-green-700 bg-green-100 border border-green-300 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required
                                autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- User Group -->
<div class="mt-4">
    <x-input-label for="user_group" :value="__('User Group')" />
    
    <select id="user_group" name="user_group" required
        class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
               focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600
               rounded-md shadow-sm">
        <option value="u">User</option>
        <option value="s">Staff</option>
        <option value="a">Admin</option>
    </select>

    <x-input-error :messages="$errors->get('user_group')" class="mt-2" />
</div>


                        <!-- PIN (for User only) -->
                        <div class="mt-4" id="pin-field" style="display: none;">
                            <x-input-label for="pin" :value="__('PIN')" />
                            <x-text-input id="pin" class="block mt-1 w-full" type="text" name="pin" pattern="\d{4}"
                                maxlength="4" minlength="4" />
                            <x-input-error :messages="$errors->get('pin')" class="mt-2" />
                        </div>

                        <!-- Password (for Staff/Admin) -->
                        <div class="mt-4" id="password-field" style="display: none;">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Create User') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const userGroupSelect = document.getElementById('user_group');
                            const pinField = document.getElementById('pin-field');
                            const passwordField = document.getElementById('password-field');

                            function toggleFields() {
                                const group = userGroupSelect.value;

                                if (group === 'u') {
                                    pinField.style.display = 'block';
                                    passwordField.style.display = 'none';
                                } else {
                                    pinField.style.display = 'none';
                                    passwordField.style.display = 'block';
                                }
                            }

                            userGroupSelect.addEventListener('change', toggleFields);
                            toggleFields(); // Run on page load
                        });
                    </script>

                </div>
            </div>
        </div>
</x-app-layout>