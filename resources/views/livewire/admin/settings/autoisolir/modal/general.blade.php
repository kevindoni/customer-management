<div>
    @if ($generalAutoIsolirModal)
        <flux:modal class="md:w-120" wire:model="generalAutoIsolirModal" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        <div class="flex gap-2">
                            {{ trans('autoisolir.title.general') }}
                        </div>
                    </flux:heading>
                </div>

                <form wire:submit="edit_general" class="md:flex flex-col gap-6">
                    <flux:input.group>
                        <flux:input.group.prefix class="w-1/2">{{ trans('autoisolir.label.auto-isolir-driver') }}
                        </flux:input.group.prefix>
                        <flux:select wire:model.change="input.auto_isolir_driver" name="auto_isolir_driver">
                            <flux:select.option value="mikrotik">Mikrotik</flux:select.option>
                            <flux:select.option value="cronjob">Cron Job</flux:select.option>
                        </flux:select>
                    </flux:input.group>

                    @if ($input['auto_isolir_driver'] === 'mikrotik')
                        <div class="flex flex-col">
                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ trans('autoisolir.label.comment-payment') }}
                                </flux:input.group.prefix>
                                <flux:input wire:model="input.comment_payment" type="text" name="comment_payment"
                                    autocomplete="comment_payment"
                                    placeholder="{{ __('autoisolir.placeholder.comment-payment') }}" />

                            </flux:input.group>
                            <flux:error name="comment_payment" />
                        </div>

                        <div class="flex flex-col">
                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">
                                    {{ trans('autoisolir.label.comment-unpayment') }}
                                </flux:input.group.prefix>
                                <flux:input wire:model="input.comment_unpayment" type="text" name="comment_unpayment"
                                    autocomplete="comment_unpayment"
                                    placeholder="{{ __('autoisolir.placeholder.comment-unpayment') }}" />

                            </flux:input.group>
                            <flux:error name="comment_unpayment" />
                        </div>
                    @endif

                    <div class="flex items-center justify-end gap-2">
                        <flux:button wire:click="$set('generalAutoIsolirModal', false)" variant="primary">
                            {{ trans('autoisolir.button.cancel') }}
                        </flux:button>

                        <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                            {{ __('autoisolir.button.update') }}
                        </flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
