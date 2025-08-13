<section class="w-full">
    @include('partials.show-mikrotik-heading')

    <x-layouts.mikrotik-view title="{{ $mikrotik->name }}" :mikrotik="$mikrotik" :heading="__('mikrotik.title.dashboard')" :subheading="__('mikrotik.title.dashboard-description')">

        @if (!$online)
            <div class="flex justify-center items-center">
                <span class="font-medium py-8 text-gray-400 text-xl">
                    {{ trans('mikrotik.offline') }}
                </span>
            </div>
        @else

        <div class="flex flex-col">
            <!-- Stats Row Starts Here -->
            <div class="flex flex-1 flex-col md:flex-row lg:flex-row mx-2">
                <div class="shadow-lg bg-blue-500 border-l-8 hover:bg-blue-600 border-blue-700 mb-2 p-2 md:w-1/4 mx-2">
                    <div class="p-4 flex flex-col">
                        <a href="#" class="no-underline text-white text-2xl">
                            {{ count($allUserSecrets) }}
                        </a>
                        <a href="#" class="no-underline text-white text-lg">
                            {{ trans('mikrotik.label.user-secrets') }}
                        </a>
                    </div>
                </div>

                <div class="shadow bg-green-500 border-l-8 hover:bg-green-600 border-green-700 mb-2 p-2 md:w-1/4 mx-2">
                    <div class="p-4 flex flex-col">
                        <a href="#" class="no-underline text-white text-2xl">
                            {{ count($activeSecrets) }}
                        </a>
                        <a href="#" class="no-underline text-white text-lg">
                            {{ trans('mikrotik.label.active-secrets') }}
                        </a>
                    </div>
                </div>

                <div class="shadow bg-red-500 border-l-8 hover:bg-red-600 border-red-700 mb-2 p-2 md:w-1/4 mx-2">
                    <div class="p-4 flex flex-col">
                        <a href="#" class="no-underline text-white text-2xl">
                            {{ count($allUserSecrets) - count($activeSecrets) }}
                        </a>
                        <a href="#" class="no-underline text-white text-lg">
                            {{ trans('mikrotik.label.offline-secrets') }}
                        </a>
                    </div>
                </div>

                <div class="shadow bg-gray-500 border-l-8 hover:bg-gray-600 border-gray-700 mb-2 p-2 md:w-1/4 mx-2">
                    <div class="p-4 flex flex-col">
                        <a href="#" class="no-underline text-white text-2xl">
                            {{ count($profiles) }}
                        </a>
                        <a href="#" class="no-underline text-white text-lg">
                            {{ trans('mikrotik.label.profiles') }}
                        </a>
                    </div>
                </div>
            </div>




            <div class="mt-2 rounded p-4 border bg-white mx-2 ">
                <flux:badge>{{ $mikrotik->name }}</flux:badge>
                <div class="md:grid md:grid-cols-2 md:gap-4 mt-2">
                    <div class="border border-r-1 border-t-0 border-l-0 border-b-0">
                        <livewire:admin.mikrotiks.component.server-resource :mikrotik="$mikrotik"
                            key="{{ now() }}" />
                    </div>
                    <div>
                        <livewire:admin.mikrotiks.component.traffic-monitoring :mikrotik="$mikrotik"
                            key="{{ now() }}" />
                    </div>
                </div>
            </div>
        </div>
        @endif
    </x-layouts.mikrotik-view>
</section>
