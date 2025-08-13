<div class="mb-5">
    <div class="ms-2 mb-1 text-zinc-800 dark:text-white text-xs">
        {!! trans_choice('roles.permissions-table.caption', $items->count(), ['count' => $items->count()]) !!}
    </div>
    <x-tables.table class="table-auto text-xs">
        <x-slot name="header">
            <x-tables.theader>
                <x-tables.header>{!! trans('roles.permissions-table.id') !!}</x-tables.header>
                <x-tables.header>{!! trans('roles.permissions-table.name') !!}</x-tables.header>
                <x-tables.header> {!! trans('roles.permissions-table.roles') !!}</x-tables.header>
                <x-tables.header>{!! trans('roles.permissions-table.createdAt') !!}</x-tables.header>
                <x-tables.header>{!! trans('roles.permissions-table.updatedAt') !!}</x-tables.header>
                <x-tables.header>{!! trans('roles.permissions-table.actions') !!}</x-tables.header>
            </x-tables.theader>
        </x-slot>
        <x-slot name="body">
            @forelse ($items as $item)
            <x-tables.row>
                <x-tables.cell>{{ $item['permission']->id }}</x-tables.cell>
                <x-tables.cell>{{ $item['permission']->name }}</x-tables.cell>
                <x-tables.cell>
                    @if ($item['roles']->count() > 0)
                    @foreach ($item['roles'] as $itemUserKey => $subItem)
                    <flux:badge size="sm" variant="pill" color="emerald">
                        {{ $subItem->name }}
                    </flux:badge>
                    @endforeach
                    @else
                    <flux:badge size="sm" variant="pill" color="red">
                        {!! trans('roles.cards.none-count') !!}
                    </flux:badge>
                    @endif
                </x-tables.cell>
                <x-tables.cell>{{ $item['permission']->created_at->format(trans('roles.date-format')) }}</x-tables.cell>
                <x-tables.cell>{{ $item['permission']->updated_at->format(trans('roles.date-format')) }}</x-tables.cell>
                <x-tables.cell class="text-center">
                    <flux:button icon="pencil" size="xs" variant="primary" style="cursor:pointer"
                        wire:click="$dispatch('show-edit-permission-modal',{permission: '{{ $item['permission']->id }}'})"
                        style="cursor:pointer" />
                </x-tables.cell>
            </x-tables.row>
            @empty
            <x-tables.row>
                <x-tables.cell colspan=7>
                    <div class="flex justify-center items-center">
                        <span class="font-medium py-8 dark:text-white text-xl">
                            {{ trans('roles.permission-notfound') }}
                        </span>
                    </div>
                </x-tables.cell>
            </x-tables.row>
            @endforelse
        </x-slot>
    </x-tables.table>
</div>
