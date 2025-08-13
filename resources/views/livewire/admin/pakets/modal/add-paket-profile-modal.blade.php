<div>
    @if ($addPaketProfileModal)
        <flux:modal class="md:w-120" wire:model="addPaketProfileModal" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        @if ($paketProfileSelect->id)
                            {{ trans('paket.edit-paket-profile', ['profile' => $paketProfileSelect->profile_name]) }}
                        @else
                            {{ trans('paket.add-paket-profile') }}
                        @endif
                    </flux:heading>
                    <flux:subheading>
                        @if ($paketProfileSelect->id)
                            {{ trans('paket.edit-paket-profile', ['profile' => $paketProfileSelect->profile_name]) }}
                        @else
                            {{ trans('paket.add-paket-profile') }}
                        @endif
                    </flux:subheading>
                </div>
                <flux:error name="status_error" />
                <form wire:submit="{{ $paketProfileSelect->slug ? 'updatePaketProfile' : 'addPaketProfile' }}">
                    <div class="flex flex-col gap-6">
                        <flux:input wire:model="input.profile_name" :label="__('paket.label.name')" type="text"
                            name="profile_name" autofocus autocomplete="profile_name"
                            placeholder="{{ __('paket.helper.profile-form.name') }}" />
                            <p class="ms-auto mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ trans('paket.helper.profile-form.name') }}
                            </p>
                        <div class="md:flex flex-row gap-2">
                            <div>
                                <flux:input wire:model="input.max_limit"
                                    :label="__('paket.label.profile-form.max-limit')" type="text" name="max_limit"
                                    autofocus autocomplete="max_limit" placeholder="0/0" />
                                <p class="ms-auto mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ trans('paket.helper.profile-form.max-limit') }}
                                </p>
                            </div>
                            <div>
                                <flux:input wire:model="input.burst_limit"
                                    :label="__('paket.label.profile-form.burst-limit')" type="text" name="burst_limit"
                                    autofocus autocomplete="burst_limit" placeholder="0/0" />
                                <p class="ms-auto mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ trans('paket.helper.profile-form.burst-limit') }}
                                </p>
                            </div>
                            <div>
                                <flux:input wire:model="input.burst_threshold"
                                    :label="__('paket.label.profile-form.burst-threshold')" type="text" name="burst_threshold"
                                    autofocus autocomplete="burst_threshold" placeholder="0/0" />
                                <p class="ms-auto mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ trans('paket.helper.profile-form.burst-threshold') }}
                                </p>
                            </div>

                        </div>

                        <div class="md:flex flex-row gap-2">
                            <div>
                                <flux:input wire:model="input.burst_time"
                                    :label="__('paket.label.profile-form.burst-time')" type="text" name="burst_time"
                                    autofocus autocomplete="burst_time" placeholder="0/0" />
                                <p class="ms-auto mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ trans('paket.helper.profile-form.burst-time') }}
                                </p>
                            </div>

                            <div>
                                <flux:input wire:model="input.priority"
                                    :label="__('paket.label.profile-form.priority')" type="text" name="priority"
                                    autofocus autocomplete="priority" placeholder="0/0" />
                                <p class="ms-auto mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ trans('paket.helper.profile-form.priority') }}
                                </p>
                            </div>

                            <div>
                                <flux:input wire:model="input.limit_at"
                                    :label="__('paket.label.profile-form.limit-at')" type="text" name="limit_at"
                                    autofocus autocomplete="limit_at" placeholder="0/0" />
                                <p class="ms-auto mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ trans('paket.helper.profile-form.limit-at') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-2">
                            <flux:button wire:click="$set('addPaketProfileModal', false)" variant="primary">
                                {{ trans('paket.button.cancel') }}
                            </flux:button>
                            <flux:button type="submit" variant="primary">
                                {{ __('paket.button.save') }}
                            </flux:button>
                        </div>
                    </div>
                </form>
        </flux:modal>
    @endif
</div>
