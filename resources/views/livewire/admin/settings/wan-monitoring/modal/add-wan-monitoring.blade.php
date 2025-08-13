<div>
    @if ($addWanMonitoringModal)
        <flux:modal class="md:w-120" wire:model="addWanMonitoringModal" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        <div class="flex gap-2">
                            @if ($mikrotikMonitoring->id)
                                {{ trans('websystem.mikrotik-monitoring.label.edit-wan-monitoring', ['mikrotik' => $mikrotikMonitoring->mikrotik->name]) }}
                            @else
                                {{ trans('websystem.mikrotik-monitoring.label.add-wan-monitoring') }}
                            @endif
                        </div>
                    </flux:heading>
                </div>

                <form wire:submit="{{ $mikrotikMonitoring->id ? 'updateMikrotikMonitoring' : 'addMikrotikMonitoring' }}">
                   <div class="flex flex-col gap-4">
                    <flux:field>
                        <flux:input.group>
                            <flux:input.group.prefix class="w-1/2">
                                {{ trans('websystem.mikrotik-monitoring.label.server') }}
                            </flux:input.group.prefix>
                            <flux:select wire:model.change="input.selectedServer" name="selectedServer"
                                isDisable="{{ $mikrotikMonitoring->mikrotik_id ? true : false }}">
                                @if ($mikrotikMonitoring->mikrotik_id)
                                    <flux:select.option value="{{ $mikrotikMonitoring->mikrotik_id }}" selected>
                                        {{ $mikrotikMonitoring->mikrotik->name }}
                                    </flux:select.option>
                                @else
                                    <flux:select.option value="">
                                        {{ trans('websystem.placeholder.select-server') }}
                                    </flux:select.option>
                                    @foreach (\App\Models\Servers\Mikrotik::where('disabled', false)->orderBy('name', 'asc')->get() as $mikrotik)
                                        <flux:select.option value="{{ $mikrotik->id }}">{{ $mikrotik->name }}
                                        </flux:select.option>
                                    @endforeach
                                @endif
                            </flux:select>
                        </flux:input.group>
                        <flux:error name="selectedServer" />
                    </flux:field>

                    <flux:field>
                        <flux:input.group>
                            <flux:input.group.prefix class="w-1/2">
                                {{ trans('websystem.mikrotik-monitoring.label.interface') }}</flux:input.group.prefix>
                            <flux:select wire:model.change="input.interface" name="interface">
                                <flux:select.option value="">
                                    {{ trans('websystem.placeholder.select-interface') }}
                                </flux:select.option>
                                @if ($mikrotik_interfaces)
                                    @foreach ($mikrotik_interfaces as $interface)
                                        <flux:select.option value="{{ $interface['name'] }}">
                                            {{ $interface['name'] }}
                                        </flux:select.option>
                                    @endforeach
                                @endif
                            </flux:select>
                        </flux:input.group>
                        <flux:error name="interface" />
                    </flux:field>


                    <!--Upload-->
                    <div>
                        <flux:text>{{ trans('websystem.mikrotik-monitoring.label.upload') }}</flux:text>
                        <div class="flex md:flex-row flex-col gap-2">
                            <div>
                                <flux:field>
                                    <flux:input.group>
                                        <flux:input.group.prefix class="w-1/2">
                                            {{ trans('websystem.mikrotik-monitoring.label.min') }}
                                        </flux:input.group.prefix>
                                        <flux:input wire:model="input.min_upload" type="text" name="min_upload"
                                            autocomplete="min_upload"
                                            placeholder="{{ trans('websystem.mikrotik-monitoring.label.min') }}" />
                                    </flux:input.group>
                                    <flux:error name="min_upload" />
                                </flux:field>
                                <p class="ms-auto mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ trans('websystem.mikrotik-monitoring.helper.min-upload') }}
                                </p>
                            </div>

                            <div>
                                <flux:field>
                                    <flux:input.group>
                                        <flux:input.group.prefix class="w-1/2">
                                            {{ trans('websystem.mikrotik-monitoring.label.max') }}
                                        </flux:input.group.prefix>
                                        <flux:input wire:model="input.max_upload" type="text" name="max_upload"
                                            autocomplete="max_upload"
                                            placeholder="{{ trans('websystem.mikrotik-monitoring.label.max') }}" />
                                    </flux:input.group>
                                    <flux:error name="max_upload" />
                                </flux:field>
                                <p class="ms-auto mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ trans('websystem.mikrotik-monitoring.helper.max-upload') }}
                                </p>
                            </div>
                        </div>
                    </div>

                     <!--Dwonload-->
                    <div>
                        <flux:text>{{ trans('websystem.mikrotik-monitoring.label.download') }}</flux:text>
                        <div class="flex md:flex-row flex-col gap-2">
                            <div>
                                <flux:field>
                                    <flux:input.group>
                                        <flux:input.group.prefix class="w-1/2">
                                            {{ trans('websystem.mikrotik-monitoring.label.min') }}
                                        </flux:input.group.prefix>
                                        <flux:input wire:model="input.min_download" type="text" name="min_download"
                                            autocomplete="min_download"
                                            placeholder=" {{ trans('websystem.mikrotik-monitoring.label.min') }}" />
                                    </flux:input.group>
                                    <flux:error name="min_download" />
                                </flux:field>
                                <p class="ms-auto mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ trans('websystem.mikrotik-monitoring.helper.min-download') }}
                                </p>
                            </div>

                            <div>
                                <flux:field>
                                    <flux:input.group>
                                        <flux:input.group.prefix class="w-1/2">
                                            {{ trans('websystem.mikrotik-monitoring.label.max') }}
                                        </flux:input.group.prefix>
                                        <flux:input wire:model="input.max_download" type="text" name="max_download"
                                            autocomplete="max_download"
                                            placeholder="{{ trans('websystem.mikrotik-monitoring.label.max') }}" />
                                    </flux:input.group>
                                    <flux:error name="max_download" />
                                </flux:field>
                                <p class="ms-auto mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ trans('websystem.mikrotik-monitoring.helper.max-download') }}
                                </p>
                            </div>
                        </div>

                    </div>
                    <div class="flex items-center justify-end gap-2">
                        <flux:button wire:click="$set('addWanMonitoringModal', false)" variant="primary">
                            {{ trans('autoisolir.button.cancel') }}
                        </flux:button>

                        <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                            @if ($mikrotikMonitoring->id)
                                {{ __('mikrotik.button.update') }}
                            @else
                                <x-icon.plus-circle class="inline h-4 w-4 sm:w-5 sm:h-5 dark:text-gray-200" />
                                {{ __('websystem.button.add') }}
                            @endif
                        </flux:button>
                    </div>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
