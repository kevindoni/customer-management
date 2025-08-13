<div>
    @if ($editRoleModal)
    <flux:modal class="md:w-120" wire:model="editRoleModal" :dismissible="false">
        <div class="space-y-6">

            <div>
                <flux:heading size="lg">{{ trans('roles.titles.edit-role', ['role'=>$role->name]) }}</flux:heading>
                <flux:subheading>{{ trans('roles.subtitles.edit-role') }}</flux:subheading>
            </div>

            <x-customer-management.multi-select model="form" class="select-custom" placeholderValue="Select Permission"
                :options="$permissions" valueKey="name" displayName="name" :keys="$form" />

            <div class="form-check form-check-inline">
                <input wire:model.live="selectAll" class="form-check-input single-user-checkbox" type="checkbox"
                    id="all">
                <label class="form-check-label" for="all">Select All</label>
            </div>

            <div class="flex">
                <flux:spacer />
                <flux:button style="cursor:pointer" variant="primary" wire:click="save">Save changes</flux:button>
            </div>
        </div>

    </flux:modal>
    @endif
</div>
