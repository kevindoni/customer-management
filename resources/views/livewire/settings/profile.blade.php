<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your profile information')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <div class="flex flex-col gap-4">
            <flux:field>
            <flux:input wire:model="input.first_name" :label="__('customer.ph.first-name')" type="text" name="first_name"
                autocomplete="first_name" />
            </flux:field>
            <flux:input wire:model="input.last_name" :label="__('customer.ph.last-name')" type="text"
                autocomplete="last_name" name="last_name"/>
            
          
            <flux:field>
                <flux:input wire:model="input.nin" :label="__('customer.label.nin')" type="text" name="nin"
                    autocomplete="nin" />
            </flux:field>
           
            <!--Gender-->
            <flux:field>
                <flux:select wire:model="input.gender" :label="__('customer.label.gender')" name="gender">
                    <flux:select.option value="">{{ trans('customer.ph.select-gender') }}</flux:select.option>
                    <flux:select.option value="male">{{ trans('customer.ph.male') }}</flux:select.option>
                    <flux:select.option value="female">{{ trans('customer.ph.female') }}</flux:select.option>

                </flux:select>
            </flux:field>

            <!--DOB-->
            <flux:field>
                <flux:input type="date" max="2009-12-31" label="{{ __('customer.label.dob') }}" name="dob" wire:model="input.dob" />

                

            </flux:field>
            
            <flux:textarea wire:model="input.bio" :label="__('customer.label.bio')" type="text"
                autocomplete="bio" />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
            </div>
        </form>


    </x-settings.layout>
</section>
