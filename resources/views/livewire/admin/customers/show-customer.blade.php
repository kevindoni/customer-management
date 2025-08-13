<section class="w-full">
    @include('partials.show-customer-heading')

    <x-layouts.admin-customer.layout :heading="__('customer.customer-pakets')" :subheading="__('customer.pakets-description')" :user="$user">
        @php
            $deletedPakets = $user->customer_pakets()->onlyTrashed()->whereHas('paket');
        @endphp

        <div class="flex flex-col md:flex-row justify-end gap-2">
            @if ($deletedPakets->count())

                <flux:button size="sm" :href="route('deletedCustomers.paket.management')" wire:navigate
                    style="cursor: pointer;" variant="danger" iconTrailing="trash">
                    {{ __('customer.button.deleted-pakets', ['count' => $deletedPakets->count()]) }}
                </flux:button>

            @endif

            <flux:button size="sm" variant="primary" icon="plus-circle" style="cursor: pointer;"
                wire:click="$dispatch('add-customer-paket-modal',{user: '{{ $user->username }}'})" title="{{ trans('customer.button.create') }}">
                {{ __('customer.button.add-paket') }}
            </flux:button>

        </div>

        <div wire:loading.class="opacity-50">
            @forelse ($user->customer_pakets as $key => $customer_paket)
                <div
                    class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-2">
                    <flux:button.group>
                        <x-customer-paket.status-and-action :customer_paket="$customer_paket" :user="$user" size="sm" />
                    </flux:button.group>
                    <flux:separator variant="subtle" class="my-2" />

                    <div class="grid md:grid-cols-2 md:gap-6 gap-2">
                        <!-- Left Side-->
                        <div class="space-y-2">
                            <div class="grid md:grid-cols-3">
                                <flux:subheading>
                                    {{ trans('customer.table.paket') }}
                                </flux:subheading>
                                <div class="col-span-2">
                                    <livewire:admin.customers.component.change-paket :customerPaket="$customer_paket" field="paket_id"
                                        dispatch="change-customer-paket" key="{{ now() }}" />
                                </div>
                            </div>

                            <div class="grid md:grid-cols-3">
                                <flux:subheading>
                                    {{ trans('customer.table.internet-service') }}
                                </flux:subheading>

                                <div class="col-span-2">
                                    @if ($customer_paket->internet_service->value == 'ppp')
                                        <flux:button size="xs" variant="primary" iconTrailing="pencil"
                                            class="cursor-pointer  w-full"
                                            wire:click="$dispatch('edit-customer-ppp-paket-modal',{customerPppPaket: '{{ $customer_paket->customer_ppp_paket->slug }}'})"
                                            title="{{ trans('customer.button.edit-ppp-paket') }}">
                                            {{ $customer_paket->internet_service->name }}
                                            <span class="hidden sm:inline"> -
                                                {{ $customer_paket->paket->mikrotik->name }}
                                            </span>
                                        </flux:button>
                                    @elseif ($customer_paket->internet_service->value == 'ip_static')
                                        <flux:button size="xs" variant="primary" iconTrailing="pencil"
                                            class="cursor-pointer  w-full"
                                            wire:click="$dispatch('edit-customer-static-paket-modal',{customerStaticPaket: '{{ $customer_paket->customer_static_paket->slug }}'})"
                                            title="{{ trans('customer.button.edit-ipstatic-paket') }}">
                                            {{ $customer_paket->internet_service->name }}
                                            <span class="hidden sm:inline"> -
                                                {{ $customer_paket->paket->mikrotik->name }}
                                            </span>
                                        </flux:button>
                                    @endif
                                </div>
                            </div>

                            @if ($customer_paket->internet_service->value == 'ppp')
                                <div class="grid md:grid-cols-3">
                                    <flux:subheading>
                                        {{ trans('customer.label.ppp-username') }}
                                    </flux:subheading>

                                    <div class="col-span-2">
                                        <flux:input type="text" icon="user-circle" size="sm"
                                            value="{{ $customer_paket->customer_ppp_paket->username }}" readonly />
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-3">
                                    <flux:subheading>
                                        {{ trans('customer.label.ppp-password') }}
                                    </flux:subheading>

                                    <div class="col-span-2">
                                        <flux:input type="password" icon="key" size="sm"
                                            value="{{ $customer_paket->customer_ppp_paket->password_ppp }}" readonly
                                            viewable />
                                    </div>
                                </div>
                            @elseif ($customer_paket->internet_service->value == 'ip_static')
                                <div class="grid md:grid-cols-3">
                                    <flux:subheading>
                                        {{ trans('customer.label.ip-address') }}
                                    </flux:subheading>

                                    <div class="col-span-2">
                                        <flux:input type="text" size="sm"
                                            value="{{ $customer_paket->customer_static_paket->ip_address }}" readonly />
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-3">
                                    <flux:subheading size="lg">
                                        {{ trans('customer.label.mac-address') }}
                                    </flux:subheading>

                                    <div class="col-span-2">
                                        <flux:input type="text" size="sm"
                                            value="{{ $customer_paket->customer_static_paket->mac_address }}" readonly />
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-3">
                                    <flux:subheading>
                                        {{ trans('customer.label.simpleque-name') }}
                                    </flux:subheading>

                                    <div class="col-span-2">
                                        <flux:text>
                                            {{ $customer_paket->customer_static_paket->simpleque_name }}
                                        </flux:text>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Right Side-->
                        <div class="space-y-2">

                            <div class="grid md:grid-cols-3">
                                <flux:subheading>
                                    {{ trans('customer.label.subscription-status') }}
                                </flux:subheading>

                                <div class="col-span-2">
                                    <flux:badge size="sm" class="w-full"
                                        color="{{ $customer_paket->status === 'active' ? 'green' : 'red' }}"
                                        icon="{{ $customer_paket->status === 'active' ? 'check' : 'x-circle' }}">
                                        {{ Str::apa($customer_paket->status) }} - {{ Str::apa($customer_paket->renewal_period) }}
                                    </flux:badge>
                                </div>
                            </div>

                            <!--Edit Activation-->
                            @if (
                                $customer_paket->status === 'active' ||
                                    $customer_paket->status === 'cancelled' ||
                                    $customer_paket->status === 'expired')

                                <div class="grid md:grid-cols-3">
                                    <flux:subheading>
                                        {{ trans('customer.table.activation-date') }}
                                    </flux:subheading>

                                    <div class="col-span-2">
                                        <flux:button size="xs" iconTrailing="pencil" class="cursor-pointer w-full"
                                            wire:click="$dispatch('edit-activation-customer-paket-modal',{customerPaket: '{{ $customer_paket->slug }}'})">
                                            {{ Carbon\Carbon::parse($customer_paket->activation_date)->format('d M Y') }}
                                        </flux:button>
                                    </div>
                                </div>
                            @endif

                            <div class="grid md:grid-cols-3">
                                <flux:subheading>
                                    {{ trans('customer.label.next-bill') }}
                                </flux:subheading>
                                <div class="col-span-2">
                                    <flux:button size="xs" iconTrailing="pencil" class="cursor-pointer w-full" disabled
                                        wire:click="">
                                        {{ Carbon\Carbon::parse($customer_paket->next_billed_at)->format('d M Y') }}
                                    </flux:button>
                                </div>
                            </div>


                            <div class="grid md:grid-cols-3">
                                <flux:subheading>
                                    {{ trans('customer.label.start-date') }}
                                </flux:subheading>
                                <div class="col-span-2">
                                    <flux:button size="xs" iconTrailing="pencil" class="cursor-pointer  w-full" disabled
                                        wire:click="">
                                        {{ Carbon\Carbon::parse($customer_paket->start_date)->format('d M Y') }}
                                    </flux:button>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-3">
                                <flux:subheading>
                                    {{ trans('customer.label.expired-date') }}
                                </flux:subheading>

                                <div class="col-span-2">
                                    <flux:button size="xs" iconTrailing="pencil" class="cursor-pointer w-full" disabled
                                        wire:click="">
                                        {{ Carbon\Carbon::parse($customer_paket->expired_date)->format('d M Y') }}
                                    </flux:button>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-3">
                                <flux:subheading>
                                    {{ trans('customer.table.bill') }}
                                </flux:subheading>

                                <div class="col-span-2 w-full">
                                    <flux:text>
                                        @if (
                                            $customer_paket->status === 'active' ||
                                                $customer_paket->status === 'cancelled' ||
                                                ($customer_paket->status === 'expired' && !$customer_paket->disabled))
                                            <span class="hidden sm:inline">
                                                @moneyIDR($customer_paket->price - $customer_paket->discount)
                                            </span>
                                            <span class="sm:hidden">
                                                @money($customer_paket->price - $customer_paket->discount)
                                            </span>
                                        @else
                                            <span class="hidden sm:inline">
                                                @moneyIDR(0)
                                            </span>
                                            <span class="sm:hidden">
                                                @money(0)
                                            </span>
                                        @endif
                                    </flux:text>
                                </div>
                            </div>
                        </div>

                    </div>
                    <flux:separator variant="subtle" class="my-2" />
                    <flux:button size="xs" variant="danger" icon="trash" class="cursor-pointer"
                        wire:click="$dispatch('delete-customer-paket-modal',{customerPaket: '{{ $customer_paket->slug }}'})"
                        title="{{ trans('customer.button.delete-paket') }}">
                        {{ trans('customer.button.delete-paket') }}
                    </flux:button>
                </div>
            @empty
                <div
                    class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-2">
                    <div class="flex justify-center items-center">
                        <span class="font-medium py-8 text-gray-400 text-xl">
                            {{ trans('customer.paket-notfound') }}
                        </span>
                    </div>
                </div>
            @endforelse
        </div>

    </x-layouts.admin-customer.layout>
    <livewire:admin.customers.modal.customer-paket.add-customer-paket-modal />
    <livewire:admin.customers.modal.customer-paket.activation-paket />
    <livewire:admin.customers.modal.customer-paket.edit-activation />
    <livewire:admin.customers.modal.customer-paket.edit-customer-paket-address-modal />
    <livewire:admin.customers.modal.customer-paket.disable-customer-paket />
    <livewire:admin.customers.modal.customer-paket.disable-wa-notification-installation-address />
    <livewire:admin.customers.modal.customer-paket.edit-customer-ppp-paket-modal />
    <livewire:admin.customers.modal.customer-paket.edit-customer-static-paket-modal />
    <livewire:admin.customers.modal.customer-paket.delete-customer-paket />
    <livewire:admin.customers.modal.customer-paket.show-customer-ppp-paket />
    <livewire:admin.customers.modal.edit-customer-modal />
</section>
