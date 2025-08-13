<div class="w-full p-3">
    <!--Graph Card-->
    <div
        class="bg-white dark:bg-white/10 shadow-xs border border-zinc-200 border-b-zinc-300/80 dark:border-white/10 rounded text-zinc-800 dark:text-white">
        <div class="border-b border-zinc-200 border-b-zinc-300/80 dark:border-white/10 p-3">
            <div class="flex justify-between">
                <div class="flex justify-start">
                    {!! trans('roles.titles.permissions-card') !!}
                </div>
                <div class="flex justify-between">
                    <flux:badge size="sm" variant="pill" icon-trailing="shield-exclamation">
                        {!! trans_choice('roles.cards.permissions-count', count($items), [
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
                                <strong class="text-blue-500">{{ $item['permission']->name }}</strong>
                            </div>
                            <div class="hidden md:flex justify-between">
                                <flux:badge class="me-2" icon-trailing="user-circle" size="sm" variant="pill">
                                    {!! trans_choice('roles.cards.users-count', count($item['users']), [
                                    'count' => count($item['users']),
                                    ]) !!}
                                </flux:badge>

                                <flux:badge size="sm" variant="pill" icon-trailing="shield-exclamation">
                                    {!! trans_choice('roles.cards.roles-count', count($item['roles']), [
                                    'count' => count($item['roles']),
                                    ]) !!}
                                </flux:badge>
                            </div>
                            <x-slot name="content">
                                <div
                                    class="w-full divide-y divide-neutral-300 overflow-hidden rounded-md border border-neutral-300 bg-neutral-50/40 dark:divide-neutral-700 dark:border-neutral-700 dark:bg-neutral-900/50 text-zinc-800 dark:text-white">
                                    <x-accordin>
                                        <div class="mb-2">
                                            <div class="text-xs">
                                                {!!
                                                trans('roles.cards.permissions-card.permissions-table-roles-caption', [
                                                'permission' => $item['permission']->name,
                                                ]) !!}
                                            </div>
                                            <x-slot name="content">
                                                <x-tables.table class="table-auto text-xs">
                                                    <x-slot name="header">
                                                        <x-tables.theader>
                                                            <x-tables.header class="w-px">{!!
                                                                trans('roles.cards.permissions-card.role-id') !!}
                                                            </x-tables.header>
                                                            <x-tables.header>{!!
                                                                trans('roles.cards.permissions-card.role-name') !!}
                                                            </x-tables.header>
                                                        </x-tables.theader>
                                                    </x-slot>
                                                    <x-slot name="body">
                                                        @forelse ($item['roles'] as $itemUserKey => $itemRole)
                                                        <x-tables.row>
                                                            <x-tables.cell class="w-px">
                                                                {{ $itemRole->id }}
                                                            </x-tables.cell>
                                                            <x-tables.cell>
                                                                {{ $itemRole->name }}
                                                            </x-tables.cell>
                                                        </x-tables.row>
                                                        @empty
                                                        <x-tables.row>
                                                            <x-tables.cell colspan=7>
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
                                        </div>
                                    </x-accordin>

                                    <x-accordin>
                                        <div class="mb-2">
                                            <div class="text-xs">
                                                {!!
                                                trans('roles.cards.permissions-card.permissions-table-users-caption', [
                                                'permission' => $item['permission']->name,
                                                ]) !!}
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
                                                                trans('roles.cards.role-card.user-email') !!}
                                                            </x-tables.header>
                                                        </x-tables.theader>
                                                    </x-slot>
                                                    <x-slot name="body">
                                                        @forelse ($item['users'] as $itemUserKey => $itemRole)
                                                        <x-tables.row>
                                                            <x-tables.cell>
                                                                {{ $itemRole->id }}
                                                            </x-tables.cell>
                                                            <x-tables.cell>
                                                                {{ $itemRole->full_name }}
                                                            </x-tables.cell>
                                                            <x-tables.cell>
                                                                {{ $itemRole->email }}
                                                            </x-tables.cell>
                                                        </x-tables.row>
                                                        @empty
                                                        <x-tables.row>
                                                            <x-tables.cell colspan=7>
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
                                        </div>
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
