<div class="mb-5">
    <div class="ms-2 mb-1 text-zinc-800 dark:text-white text-xs">
        {!! trans_choice('roles.roles-table.caption', $items->count(), [
        'count' => $items->count(),
        ]) !!}
    </div>
    <x-tables.table class="table-auto text-xs">
        <x-slot name="header">
            <x-tables.theader>
                <x-tables.header>{!! trans('roles.roles-table.id') !!}</x-tables.header>
                <x-tables.header> {!! trans('roles.roles-table.name') !!}</x-tables.header>
                <x-tables.header>{!! trans('roles.roles-table.permissons') !!}</x-tables.header>
                <x-tables.header>{!! trans('roles.roles-table.createdAt') !!}</x-tables.header>
                <x-tables.header>{!! trans('roles.roles-table.updatedAt') !!}</x-tables.header>
                <x-tables.header>{!! trans('roles.roles-table.actions') !!}</x-tables.header>
            </x-tables.theader>
        </x-slot>
        <x-slot name="body">
            @forelse ($items as $item)
            <x-tables.row>
                <x-tables.cell>{{ $item['role']->id }}</x-tables.cell>
                <x-tables.cell>{{ $item['role']->name }}</x-tables.cell>
                <x-tables.cell>
                    @if ($item['permissions']->count() > 0)
                    @foreach ($item['permissions'] as $itemPermKey => $itemPerm)
                    <flux:badge size="sm" variant="pill" color="amber">
                        {{ $itemPerm->name }}
                    </flux:badge>
                    @endforeach
                    @else
                    <flux:badge size="sm" variant="pill" color="red">
                        {!! trans('roles.cards.none-count') !!}
                    </flux:badge>
                    @endif
                </x-tables.cell>
                <x-tables.cell>{{ $item['role']->created_at->format(trans('roles.date-format')) }}</x-tables.cell>
                <x-tables.cell>{{ $item['role']->updated_at->format(trans('roles.date-format')) }}</x-tables.cell>

                <x-tables.cell class="text-center">
                    <flux:button icon="pencil" size="xs" variant="primary" style="cursor:pointer"
                        wire:click="$dispatch('show-edit-role-modal',{role: '{{ $item['role']->id }}'})"
                        style="cursor:pointer" />
                </x-tables.cell>
            </x-tables.row>
            @empty
            <x-tables.row>
                <x-tables.cell colspan=7>
                    <div class="flex justify-center items-center">
                        <span class="font-medium py-8 text-zinc-800 dark:text-white text-xl">
                            {{ trans('roles.role-notfound') }}
                        </span>
                    </div>
                </x-tables.cell>
            </x-tables.row>
            @endforelse
        </x-slot>

    </x-tables.table>
</div>
