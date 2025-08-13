<div class="p-3">
    @php
    $tableType = 'normal';
    $tableItems = $sortedPermissionsRolesUsers;
    @endphp

    <div
        class="bg-white dark:bg-white/10 border border-zinc-200 border-b-zinc-300/80 dark:border-white/10 rounded shadow">
        <div class="border-b border-zinc-200 border-b-zinc-300/80 dark:border-white/10 p-3">
            <div class="flex justify-between text-zinc-800 dark:text-white">
                <div class="flex justify-start text-sm ms-2 py-3">
                    <flux:badge>
                        {!! trans('roles.titles.permissions-table') !!}
                    </flux:badge>
                </div>
                <div class="flex justify-between">

                </div>
            </div>

            @include('livewire.admin.roles.tables.permission-items-table', [
            'tabletype' => $tableType,
            'items' => $tableItems,
            ])

        </div>
    </div>
</div>

<livewire:admin.roles.modal.edit-permission />
