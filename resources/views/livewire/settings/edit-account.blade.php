<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Update Account')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        
        <form wire:submit="updateEmail" class="mt-6 space-y-6">
            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>
            
            

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="email-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
        
        
        <div class="mt-8 flex-1 self-stretch max-md:pt-6">
            <flux:heading>{{ __('Update Password') }}</flux:heading>
            <flux:subheading>{{ __('Ensure your account is using a long, random password to stay secure') }}</flux:subheading>
    
            <div class="mt-5 w-full max-w-lg">
                <form wire:submit="updatePassword" class="mt-6 space-y-6">
                    <flux:input
                        wire:model="current_password"
                        :label="__('Current password')"
                        type="password"
                        autocomplete="current-password"
                    />
                    <flux:input
                        wire:model="password"
                        :label="__('New password')"
                        type="password"
                        autocomplete="new-password"
                    />
                    <flux:input
                        wire:model="password_confirmation"
                        :label="__('Confirm Password')"
                        type="password"
                        autocomplete="new-password"
                    />
        
                    <div class="flex items-center gap-4">
                        <div class="flex items-center justify-end">
                            <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                        </div>
        
                        <x-action-message class="me-3" on="password-updated">
                            {{ __('Saved.') }}
                        </x-action-message>
                    </div>
                </form>
            </div>
        </div>
        
    </x-settings.layout>
</section>
