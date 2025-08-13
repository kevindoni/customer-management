<div class="w-full p-3">
    <!--Graph Card-->

    <div
        class="bg-white dark:bg-white/10 shadow-xs border border-zinc-200 border-b-zinc-300/80 dark:border-white/10 rounded text-zinc-800 dark:text-white">
        <div class="border-b border-zinc-200 border-b-zinc-300/80 dark:border-white/10 p-3">
            <div class="flex justify-between">
                <div class="flex justify-start">
                    {!! trans('roles.titles.roles-card') !!}
                </div>
                <div class="flex justify-between">
                    <flux:badge size="sm" variant="pill" icon-trailing="shield-exclamation">
                        {!! trans_choice('roles.cards.roles-count', count($items), [
                        'count' => count($items),
                        ]) !!}
                    </flux:badge>
                </div>
            </div>
        </div>
        <div class="p-3">
            <ul class="list-group list-group-flush">
                @if (count($items) != 0)
                <div
                    class="w-full bg-white dark:bg-white/10 shadow-xs border border-zinc-200 border-b-zinc-300/80 dark:border-white/10 rounded text-zinc-800 dark:text-white">
                    @foreach ($items as $itemKey => $item)
                    <li>
                        <x-accordin>
                            <div class="flex justify-start">
                                <strong class="text-blue-500 dark:text-gray-300">{{ $item['role']->name }}</strong>
                            </div>
                            <div class="justify-between hidden md:flex">
                                <flux:badge class="me-2" icon-trailing="user-circle" size="sm" variant="pill">
                                    {!! trans_choice('roles.cards.users-count', count($item['users']), ['count' =>
                                    count($item['users'])]) !!}
                                </flux:badge>

                                <flux:badge size="sm" variant="pill" icon-trailing="shield-exclamation">
                                    {!! trans_choice('roles.cards.permissions-count', count($item['permissions']), [
                                    'count' => count($item['permissions']),
                                    ]) !!}
                                </flux:badge>
                            </div>

                            <x-slot name="content">
                                <div
                                    class="w-full divide-y divide-neutral-300 overflow-hidden rounded-md border border-neutral-300 bg-neutral-50/40 dark:divide-neutral-700 dark:border-neutral-700 dark:bg-neutral-900/50 text-zinc-800 dark:text-white">
                                    <x-accordin>
                                        <div class="text-xs">
                                            {!! trans('roles.cards.role-card.table-users-caption', ['role' =>
                                            $item['role']->name]) !!}
                                        </div>
                                        <x-slot name="content">
                                            <x-tables.table class="table-auto text-xs">
                                                <x-slot name="header">
                                                    <x-tables.theader>
                                                        <x-tables.header class="w-px">{!!
                                                            trans('roles.cards.role-card.user-id') !!}
                                                        </x-tables.header>
                                                        <x-tables.header>{!!
                                                            trans('roles.cards.role-card.user-fullname') !!}
                                                        </x-tables.header>
                                                        <x-tables.header>{!!
                                                            trans('roles.cards.role-card.user-email')
                                                            !!}</x-tables.header>
                                                    </x-tables.theader>
                                                </x-slot>
                                                <x-slot name="body">
                                                    @forelse ($item['users'] as $itemUserKey => $itemUser)
                                                    <x-tables.row>
                                                        <x-tables.cell class="text-center">
                                                            {{ $itemUser->id }}
                                                        </x-tables.cell>
                                                        <x-tables.cell>
                                                            {{ $itemUser->full_name }}
                                                        </x-tables.cell>
                                                        <x-tables.cell>
                                                            {{ $itemUser->email }}
                                                        </x-tables.cell>
                                                    </x-tables.row>
                                                    @empty
                                                    <x-tables.row>
                                                        <x-tables.cell colspan=3>
                                                            <div class="flex justify-center items-center">
                                                                <span
                                                                    class="font-medium py-8 text-zinc-800 dark:text-white text-xl">
                                                                    {{ trans('roles.user-notfound') }}
                                                                </span>
                                                            </div>
                                                        </x-tables.cell>
                                                    </x-tables.row>
                                                    @endforelse
                                                </x-slot>
                                            </x-tables.table>
                                        </x-slot>
                                    </x-accordin>

                                    <x-accordin>
                                        <div class="text-xs">
                                            {!! trans('roles.cards.role-card.table-permissions-caption', [
                                            'role' => $item['role']->name,
                                            ]) !!}
                                        </div>
                                        <x-slot name="content">
                                            <x-tables.table class="table-auto text-xs">
                                                <x-slot name="header">
                                                    <x-tables.theader>
                                                        <x-tables.header class="w-px">{!!
                                                            trans('roles.cards.role-card.permissions-id') !!}
                                                        </x-tables.header>
                                                        <x-tables.header>{!!
                                                            trans('roles.cards.role-card.permissions-name') !!}
                                                        </x-tables.header>
                                                    </x-tables.theader>
                                                </x-slot>
                                                <x-slot name="body">
                                                    @forelse ($item['permissions'] as $itemUserKey => $itemUser)
                                                    <x-tables.row>
                                                        <x-tables.cell class="text-center">{{ $itemUser->id }}
                                                        </x-tables.cell>
                                                        <x-tables.cell>{{ $itemUser->name }}</x-tables.cell>
                                                    </x-tables.row>
                                                    @empty
                                                    <x-tables.row>
                                                        <x-tables.cell colspan=2>
                                                            <div class="flex justify-center items-center">
                                                                <span
                                                                    class="font-medium py-8 text-zinc-800 dark:text-white text-xl">
                                                                    {{ trans('roles.user-notfound') }}
                                                                </span>
                                                            </div>
                                                        </x-tables.cell>
                                                    </x-tables.row>
                                                    @endforelse
                                                </x-slot>
                                            </x-tables.table>
                                        </x-slot>
                                    </x-accordin>
                                </div>


                            </x-slot>
                        </x-accordin>
                    </li>
                    @endforeach
                </div>
                @else
                <li class="list-group-item">
                    {!! trans('roles.cards.none-count') !!}
                </li>
                @endif
            </ul>

        </div>
    </div>
    <!--/Graph Card-->
</div>
