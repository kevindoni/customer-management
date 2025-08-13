<div>
    <form wire:submit="update_env" class="max-w-md mt-3 space-y-4">
        <div class="grid grid-cols-4 lg:gap-2 gap-1">
            <div class="place-content-center">
                <flux:label class="font-semibold">{{ __('websystem.label.app-env') }}</flux:label>
            </div>
            <div class="co col-span-3">
                <flux:select wire:model="input.app_env">
                    <flux:select.option value="local">Local</flux:select.option>
                    <flux:select.option value="production">Production</flux:select.option>
                </flux:select>
            </div>
        </div>

        <div class="grid grid-cols-4 lg:gap-2 gap-1">
            <div class="place-content-center">
                <flux:label class="font-semibold">{{ __('websystem.label.app-debug') }}</flux:label>
            </div>
            <div class="co col-span-3">
                <flux:select wire:model="input.app_debug">
                    <flux:select.option value="true">True</flux:select.option>
                    <flux:select.option value="false">False</flux:select.option>
                </flux:select>
            </div>
        </div>

        <div class="grid grid-cols-4 lg:gap-2 gap-1">
            <div class="place-content-center">
                <flux:label class="font-semibold">{{ __('websystem.label.session-driver') }}</flux:label>
            </div>
            <div class="co col-span-3">
                <flux:select wire:model="input.session_driver">
                    <flux:select.option value="database">Database</flux:select.option>
                    <flux:select.option value="file">File</flux:select.option>
                </flux:select>
            </div>
        </div>

        <div class="grid grid-cols-4 lg:gap-2 gap-1">
            <div class="place-content-center">
                <flux:label class="font-semibold">{{ __('websystem.label.cache-store') }}</flux:label>
            </div>
            <div class="co col-span-3">
                <flux:select wire:model="input.cache_store">
                    <flux:select.option value="database">Database</flux:select.option>
                    <flux:select.option value="file">File</flux:select.option>
                </flux:select>
            </div>
        </div>

        <div class="grid grid-cols-4 lg:gap-2 gap-1">
            <div class="place-content-center">
                <flux:label class="font-semibold">{{ __('websystem.label.queue-connection') }}</flux:label>
            </div>
            <div class="co col-span-3">
                <flux:select wire:model="input.queue_connection">
                    <flux:select.option value="database">Database</flux:select.option>
                    <flux:select.option value="sync">Sync</flux:select.option>
                </flux:select>
            </div>
        </div>

        <div class="grid grid-cols-4 lg:gap-2 gap-1">
            <div class="place-content-center">
                <flux:label class="font-semibold">{{ __('websystem.label.app-timezone') }}</flux:label>
            </div>
            <div class="co col-span-3">
                <flux:input wire:model="input.app_timezone" type="text" name="app_timezone"
                    autocomplete="app_timezone" placeholder="ex: Asia/Jakarta" />
            </div>
        </div>

        <div class="grid grid-cols-4 lg:gap-2 gap-1">
            <div class="place-content-center">
                <flux:label class="font-semibold">{{ __('websystem.label.isolir-driver') }}</flux:label>
            </div>
            <div class="co col-span-3">
                <flux:select wire:model="input.isolir_driver" name="isolir_driver">
                    <flux:select.option value="mikrotik">Mikrotik</flux:select.option>
                    <flux:select.option value="server">Server</flux:select.option>
                </flux:select>
            </div>
        </div>

        <div class="grid grid-cols-4 lg:gap-2 gap-1">
            <div class="place-content-center">
                <flux:label class="font-semibold">{{ __('websystem.label.subscription-type') }}</flux:label>
            </div>
            <div class="co col-span-3">
                <flux:select wire:model="input.subscription_type" name="subscription_type">
                    <flux:select.option value="prabayar">Pra Bayar</flux:select.option>
                    <flux:select.option value="pascabayar">Pasca Bayar</flux:select.option>
                </flux:select>
                <span class="text-gray-500">{{ __('Pascabayar is still in trial.') }}</span>
            </div>
        </div>


        <div class="flex items-center justify-end gap-2 mt-6">
            <flux:button wire:click="link_storage" variant="primary" iconTrailing="folder">
                {{ __('websystem.button.link-storage') }}
            </flux:button>
            <flux:button wire:click="optimize" variant="primary" iconTrailing="rocket-launch">
                {{ __('websystem.button.optimize') }}
            </flux:button>
            <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                {{ __('customer.button.save') }}
            </flux:button>
        </div>

    </form>

</div>
