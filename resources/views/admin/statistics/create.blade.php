<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('New Statistics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="p-10 overflow-hidden bg-white shadow-sm sm:rounded-lg">

                <form method="POST" action="{{ route('admin.statistics.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name')" required autofocus
                                      autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="icon" :value="__('Icon')" />
                        <x-text-input id="icon" class="block w-full mt-1" type="file" name="icon" required autofocus autocomplete="icon" />
                        <x-input-error :messages="$errors->get('icon')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="goal" :value="__('Goal')" />
                        <x-text-input id="goal" class="block w-full mt-1" type="text" name="goal" :value="old('goal')" required autofocus
                                      autocomplete="goal" />
                        <x-input-error :messages="$errors->get('goal')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">

                        <button type="submit" class="px-6 py-4 font-bold text-white bg-indigo-700 rounded-full">
                            Add New Statistic
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
