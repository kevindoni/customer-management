<div>
    @if ($editBootMessageModal)
    <flux:modal class="md:w-160" wire:model="editBootMessageModal" :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{ trans('whatsapp-gateway.heading.edit-device') }}
                </flux:heading>
                <flux:subheading>{{ trans('whatsapp-gateway.heading.subtitle-edit-device') }}</flux:subheading>
            </div>
            

            <form wire:submit="updateMessage">
                <div class="flex flex-col gap-6">
                    <div class="flex flex-row gap-4">
                        <flux:input wire:model="input.name" :label="__('whatsapp-gateway.label.name')" type="text"
                            name="name" autocomplete="name" placeholder="{{ __('whatsapp-gateway.helper.name') }}" />
                            
                            
                        <flux:field>
                            <flux:select wire:model.change="input.parent" name="parent" :label="__('whatsapp-gateway.label.parent')">
                                <flux:select.option value="">
                                    {{ trans('whatsapp-gateway.ph.select-parent') }}
                                </flux:select.option>
                                @foreach (\App\Models\WhatsappGateway\WhatsappBootMessage::where('disabled', false)->orderBy('name', 'asc')->get() as $parent)
                                    <flux:select.option value="{{ $parent->id }}">{{ $parent->name }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="parent" />
                        </flux:field>
                    </div>
                    
                    <div class="flex flex-row gap-4">
                        <flux:input wire:model="input.command_number" :label="__('whatsapp-gateway.label.command-number')" type="text"
                            name="command_number" autocomplete="command_number" placeholder="{{ __('whatsapp-gateway.helper.command-number') }}" />
                            
                        <flux:input wire:model="input.action_message" :label="__('whatsapp-gateway.label.action-message')" type="text"
                            name="action_message" autocomplete="action_message" placeholder="{{ __('whatsapp-gateway.helper.action-message') }}" />
                            
                       
                    </div>  
                    
                    <div class="flex flex-row gap-4">
                            
                        <flux:field variant="inline">
                            <flux:checkbox wire:model="input.checkbox_hidden_message" />
                            <flux:label>
                                <flux:badge color="lime">
                                    {{ trans('whatsapp-gateway.label.hidden-message') }}
                                </flux:badge>
                            </flux:label>
                        </flux:field>
                        
                        <flux:field variant="inline">
                            <flux:checkbox wire:model="input.checkbox_end_message" />
                            <flux:label>
                                <flux:badge color="lime">
                                    {{ trans('whatsapp-gateway.label.end-message') }}
                                </flux:badge>
                            </flux:label>
                        </flux:field>
                       
                    </div>
                    
                     <flux:textarea wire:model="input.message" :label="__('whatsapp-gateway.label.message')"
                        type="text" name="message" autocomplete="message"
                        placeholder="{{ __('whatsapp-gateway.helper.wa-message') }}" />

                    <flux:textarea wire:model="input.descriptione" :label="__('whatsapp-gateway.label.description')"
                        type="text" name="description" autocomplete="description"
                        placeholder="{{ __('whatsapp-gateway.helper.description') }}" />
                    
                     
                        
                    

                    <div class="flex items-center justify-end">
                        <flux:button wire:click="$set('editBootMessageModal', false)" variant="primary" class="me-2"
                            style="cursor:pointer">
                            {{ __('device.button.cancel') }}
                        </flux:button>
                        <flux:button type="submit" variant="primary" style="cursor:pointer">
                            {{ __('device.button.update') }}
                        </flux:button>
                    </div>

                </div>

            </form>
        </div>
    </flux:modal>
    @endif
</div>
