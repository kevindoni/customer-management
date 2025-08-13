<div>
    @if ($addAutoIsolirModal)
        <flux:modal class="md:w-120" wire:model="addAutoIsolirModal" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        <div class="flex gap-2">
                            @if ($selectAutoIsolir->id)
                                {{ trans('autoisolir.title.edit', ['autoisolir' => $selectAutoIsolir->name]) }}
                            @else
                                {{ trans('autoisolir.title.create') }}
                            @endif
                        </div>
                    </flux:heading>
                </div>

                <form wire:submit="{{ $selectAutoIsolir->id ? 'editAutoIsolir' : 'addAutoIsolir' }}"
                    class="md:flex flex-col gap-4">
                    <flux:input.group>
                        <flux:input.group.prefix class="w-1/2">{{ __('autoisolir.label.name') }}
                        </flux:input.group.prefix>
                        @if ($selectAutoIsolir->id)
                            <flux:input wire:model="input.name" type="text" name="name" disabled autocomplete="name"
                                placeholder="{{ __('autoisolir.placeholder.name') }}" />
                        @else
                            <flux:input wire:model="input.name" type="text" name="name" autocomplete="name"
                                placeholder="{{ __('autoisolir.placeholder.name') }}" />
                        @endif
                    </flux:input.group>

                    <flux:input.group>
                        <flux:input.group.prefix class="w-1/2">{{ trans('paket.label.mikrotik') }}
                        </flux:input.group.prefix>
                        <flux:input type="text" name="name" disabled value="{{ $selectAutoIsolir->mikrotik->name }}"/>
                        
                    </flux:input.group>

                    <flux:input.group>
                        <flux:input.group.prefix class="w-1/2">{{ trans('autoisolir.label.profile') }}
                        </flux:input.group.prefix>
                        <flux:select wire:model="input.selectedProfile" name="selectedProfile">
                            <flux:select.option value="">
                                -- {{ __('autoisolir.placeholder.select-profile') }} --
                            </flux:select.option>
                            @if ($selectedServer)
                                @foreach ($profiles as $profile)
                                    <flux:select.option value="{{ $profile['name'] }}">{{ $profile['name'] }}
                                    </flux:select.option>
                                @endforeach
                            @endif

                        </flux:select>

                        <flux:input.group.suffix>
                            <div wire:loading wire:target="input.selectedServer">
                                <flux:icon.loading variant="solid" class="text-green-500 dark:text-green-300" />
                            </div>
                        </flux:input.group.suffix>

                    </flux:input.group>

                    <flux:input.group>
                        <flux:input.group.prefix class="w-1/2">{{ trans('autoisolir.label.address_list_isolir') }}
                        </flux:input.group.prefix>
                        <flux:input wire:model="input.address_list_isolir" type="text" name="address_list_isolir"
                            autocomplete="address_list_isolir"
                            placeholder="{{ __('autoisolir.placeholder.address_list_isolir') }}" />
                    </flux:input.group>

                    <flux:input.group>
                        <flux:input.group.prefix class="w-1/2">{{ trans('autoisolir.label.auto-isolir-option') }}
                        </flux:input.group.prefix>
                        <flux:select wire:model.change="input.selectedAutoIsolirOption" name="selectedAutoIsolirOption">
                            <flux:select.option value="">
                                {{ __('autoisolir.placeholder.select-auto-isolir-option') }}
                            </flux:select.option>
                            <flux:select.option value="false">
                                {{ __('autoisolir.placeholder.select-auto-isolir-due-date') }}</flux:select.option>
                            <flux:select.option value="true">
                                {{ __('autoisolir.placeholder.select-auto-isolir-activation-date') }}
                            </flux:select.option>
                        </flux:select>
                    </flux:input.group>

                    @if ($input['selectedAutoIsolirOption'] == 'false')
                        <flux:input.group>
                            <flux:input.group.prefix class="w-1/2">{{ trans('autoisolir.label.due-date') }}
                            </flux:input.group.prefix>
                            <flux:input wire:model="input.due_date" type="text" name="due_date" autocomplete="due_date"
                                placeholder="{{ __('autoisolir.placeholder.due-date') }}" />
                        </flux:input.group>
                    @endif

                    <div class="flex items-center justify-end gap-2">
                        <flux:button wire:click="$set('addAutoIsolirModal', false)" variant="primary">
                            {{ trans('autoisolir.button.cancel') }}
                        </flux:button>

                        <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                            @if ($selectAutoIsolir->id)
                                {{ __('autoisolir.button.update') }}
                            @else
                                {{ __('autoisolir.button.save') }}
                            @endif
                        </flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
