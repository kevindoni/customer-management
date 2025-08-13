<div>
    @if ($addPaketModal)
        <flux:modal class="md:w-120" wire:model="addPaketModal" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        @if ($paketSelect->id)
                            {{ trans('paket.edit-paket', ['paket' => $paketSelect->name]) }}
                        @else
                            {{ trans('paket.add-paket') }}
                        @endif
                    </flux:heading>
                    <flux:subheading>
                        @if ($paketSelect->id)
                            {{ trans('paket.helper.title') }}
                        @else
                            {{ trans('paket.helper.title') }}
                        @endif
                    </flux:subheading>
                </div>
                <flux:error name="status_error" />
                <form wire:submit="{{ $paketSelect->id ? 'updatePaket' : 'addPaket' }}">

                    <div class="{{ $currentStep != 1 ? 'hidden' : '' }} flex flex-col gap-4">
                        <flux:input wire:model="input.name" :label="__('paket.label.name')" type="text" name="name"
                            autofocus autocomplete="name" placeholder="{{ __('paket.helper.name') }}" />

                        <flux:input wire:model="input.price" :label="__('paket.label.price')" type="text"
                            name="price" autofocus autocomplete="price"
                            placeholder="{{ __('paket.helper.price') }}" />

                        <div>
                        <flux:input wire:model="input.trial_days" :label="__('paket.label.trial-days')" type="text" name="trial_days"
                            autofocus autocomplete="trial_days" placeholder="{{ __('paket.helper.trial-days') }}" />
                            <flux:subheading>{{ __('paket.helper.trial-days') }}</flux:subheading>

                        </div>

                        <flux:textarea wire:model="input.description" :label="__('paket.label.description')"
                            type="text" name="description" autofocus autocomplete="description"
                            placeholder="{{ __('paket.ph.write-description') }}" />

                        <div class="flex items-center justify-end gap-2">
                            <flux:button wire:click='addFirstStepSubmit' variant="primary" iconTrailing="arrow-right">
                                {{ __('Next') }}
                            </flux:button>
                        </div>
                    </div>

                    <div class="{{ $currentStep != 2 ? 'hidden' : '' }} flex flex-col gap-4">
                        @if ($paketSelect->id)
                            <flux:field>
                                <flux:select wire:model="input.selectedServer" :label="trans('paket.label.mikrotik')">
                                    <flux:select.option value="{{ $paketSelect->mikrotik->id }}">{{ $paketSelect->mikrotik->name }}
                                    </flux:select.option>
                                </flux:select>
                                <flux:error name="selectedServer" />
                            </flux:field>
                        @else
                            <flux:field>
                                <flux:select wire:model="input.selectedServer" :label="trans('paket.label.mikrotik')">
                                    <flux:select.option value=""> {{ trans('paket.ph.select-mikrotik') }}
                                    </flux:select.option>
                                    @foreach (\App\Models\Servers\Mikrotik::where('disabled', false)->orderBy('name', 'asc')->get() as $mikrotik)
                                        <flux:select.option value="{{ $mikrotik->id }}">{{ $mikrotik->name }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="selectedServer" />
                            </flux:field>
                        @endif

                        <flux:field>
                            <flux:select wire:model="input.selectedProfile" :label="trans('paket.label.profile')">
                                <flux:select.option value="">{{ trans('paket.ph.select-profile') }}
                                </flux:select.option>
                                @foreach (\App\Models\Pakets\PaketProfile::where('disabled', false)->orderBy('profile_name', 'ASC')->get() as $profile)
                                    <flux:select.option value="{{ $profile->id }}">{{ $profile->profile_name }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="selectedProfile" />
                        </flux:field>

                        <flux:input wire:model="input.current_password" :label="__('mikrotik.label.user-password')"
                            type="password" name="current_password" autofocus autocomplete="current_password"
                            placeholder="{{ __('mikrotik.helper.user-password') }}" />

                        <div class="flex items-center justify-end gap-2">
                            <flux:button wire:click="$set('addPaketModal', false)" variant="primary">
                                {{ trans('paket.button.cancel') }}
                            </flux:button>
                            <flux:button wire:click="back(1)" variant="primary" icon="arrow-left">
                                {{ __('Back') }}
                            </flux:button>
                            <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                                {{ __('paket.button.save') }}
                            </flux:button>
                        </div>
                    </div>

                </form>
            </div>
        </flux:modal>
    @endif
</div>
