<div>
    @if ($disableWaNotificationModal)
        <flux:modal class="md:w-120" wire:model="disableWaNotificationModal" :dismissible="false"
            @close="$dispatch('close-modal')">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('customer.paket.wa-notification', ['customer' => $userAddress->user->full_name]) }}
                    </flux:heading>

                </div>
                <form wire:submit="disableWaNotification">
                    <div class="flex flex-col gap-4">
                        <flux:checkbox wire:model.live="input.checkbox_wa_notification"
                            label="{{ trans('user.label.send-wa-notification') }}" />

                        <div class="flex items-center justify-end gap-2">
                            <flux:button wire:click="$set('disableWaNotificationModal', false)" variant="primary"
                                class="me-2" style="cursor:pointer">
                                {{ __('whatsapp-gateway.button.cancel') }}
                            </flux:button>
                            <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                                {{ __('customer.button.update') }}
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
