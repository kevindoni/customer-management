<div>
    @if ($editSubscriptionModal)
        <flux:modal class="md:w-160" wire:model="editSubscriptionModal" :dismissible="false">
            @if ($products || $order)
                @if ($currentStep == 1)
                    <div class="space-y-6">
                        <div>
                            <flux:heading size="lg">
                                @if ($subscription)
                                    {{ trans('whatsapp-gateway.heading.upgrade-subscription') }}
                                @else
                                    {{ trans('whatsapp-gateway.heading.add-subscription') }}
                                @endif

                            </flux:heading>
                            <flux:subheading>
                                @if ($subscription)
                                    {{ trans('whatsapp-gateway.heading.subtitle-edit-subscription') }}
                                @else
                                    {{ trans('whatsapp-gateway.heading.subtitle-add-subscription') }}
                                @endif
                            </flux:subheading>
                        </div>

                        <form wire:submit="{{ $subscription ? 'updateSubscription' : 'addSubscription' }}">
                            <div class="flex flex-col gap-6">
                                <div>
                                    <flux:field>
                                        <flux:select wire:model.change="input.product" name="product"
                                            :label="__('whatsapp-gateway.label.product')">
                                            <flux:select.option value="">
                                                {{ trans('whatsapp-gateway.ph.select-product') }}
                                            </flux:select.option>
                                            @foreach ($products as $product)
                                                <flux:select.option value="{{ $product['id'] }}">{{ $product['name'] }}
                                                </flux:select.option>
                                            @endforeach
                                        </flux:select>
                                        <flux:text>{{ $descriptionProduct }}</flux:text>
                                    </flux:field>
                                </div>

                                <div>
                                    <flux:field>
                                        <div class="flex gap-2">
                                            <flux:label>{{ __('whatsapp-gateway.label.renewal-period') }} </flux:label>
                                            <div wire:loading wire:target="input.product">
                                                <flux:icon.loading />
                                            </div>
                                        </div>
                                        <flux:select wire:model.change="input.renewal_period" name="renewal_period">
                                            <flux:select.option value="">
                                                {{ trans('whatsapp-gateway.ph.select-renewal-period') }}
                                            </flux:select.option>
                                            @if ($subscriptionPlans)
                                                @foreach ($subscriptionPlans as $subscriptionPlan)
                                                    <flux:select.option
                                                        value="{{ $subscriptionPlan['subscription_code'] }}">
                                                        {{ $subscriptionPlan['name'] }}
                                                    </flux:select.option>
                                                @endforeach
                                            @endif

                                        </flux:select>
                                        <flux:text>Active Subscription:
                                            @if ($subscription)
                                                <flux:badge>
                                                     {{\Illuminate\Support\Str::apa($subscription['renewal_period'] ?? 'Unsubscribe')}}
                                                </flux:badge>
                                            @endif
                                        </flux:text>

                                    </flux:field>
                                    <flux:error name="renewal_period" />
                                </div>

                                <div>
                                    <flux:field>
                                        <div class="flex gap-2">
                                            <flux:label>{{ __('whatsapp-gateway.label.payment-method') }} </flux:label>
                                            <div wire:loading wire:target="input.renewal_period">
                                                <flux:icon.loading />
                                            </div>
                                        </div>
                                        <flux:select wire:model.change="input.payment_method" name="payment_method">
                                            <flux:select.option value="">
                                                {{ trans('whatsapp-gateway.ph.select-bank') }}
                                            </flux:select.option>
                                            @if ($paymentMethods)
                                                @foreach ($paymentMethods as $paymentMethod)
                                                    <flux:select.option value="{{ $paymentMethod['code'] }}">
                                                        {{ $paymentMethod['name'] }}
                                                    </flux:select.option>
                                                @endforeach
                                            @endif
                                        </flux:select>

                                    </flux:field>
                                    <flux:error name="payment_method" />
                                </div>

                                <div class="flex items-center justify-end">
                                    <flux:button wire:click="$set('editSubscriptionModal', false)" variant="ghost"
                                        class="me-2" style="cursor:pointer">
                                        {{ __('device.button.cancel') }}
                                    </flux:button>
                                    <flux:button type="submit" variant="primary" style="cursor:pointer">
                                        {{ $subscription ? __('whatsapp-gateway.button.update') : __('whatsapp-gateway.button.add') }}
                                    </flux:button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
                @if ($currentStep == 2)
                    <div class="space-y-6">
                        <div>
                            <flux:heading size="lg">
                                Menunggu Pembayaran
                            </flux:heading>
                        </div>

                        <div class="flex flex-col gap-6">

                            <div class="flex flex-col gap-2">
                                <div class="flex justify-between">
                                    <div class="flex justify-start">
                                        <flux:heading>Nama Pelanggan</flux:heading>
                                    </div>
                                    <div class="flex justify-between">
                                        <flux:text>{{ $order['customer_name'] }}</flux:text>
                                    </div>
                                </div>

                                <div class="flex justify-between">
                                    <div class="flex justify-start">
                                        <flux:heading>Email Pelanggan</flux:heading>
                                    </div>
                                    <div class="flex justify-between">
                                        <flux:text>{{ $order['customer_email'] }}</flux:text>
                                    </div>
                                </div>

                                <div class="flex justify-between">
                                    <div class="flex justify-start">
                                        <flux:heading>Telepon Pelanggan</flux:heading>
                                    </div>
                                    <div class="flex justify-between">
                                        <flux:text>{{ $order['customer_phone'] }}</flux:text>
                                    </div>
                                </div>

                                <flux:separator class="my-2" />

                                <div class="flex justify-between">
                                    <div class="flex justify-start">
                                        <flux:heading>Referensi</flux:heading>
                                    </div>
                                    <div class="flex justify-between">
                                        <flux:text>{{ $order['reference'] }}</flux:text>
                                    </div>
                                </div>
                                <div class="flex justify-between">
                                    <div class="flex justify-start">
                                        <flux:heading>Nomor Tagihan</flux:heading>
                                    </div>
                                    <div class="flex justify-between">
                                        <flux:text>
                                            {{ $order['merchant_ref'] }}
                                        </flux:text>
                                    </div>
                                </div>

                                <div class="flex justify-between">
                                    <div class="flex justify-start">
                                        <flux:heading>Metode Pembayaran</flux:heading>
                                    </div>
                                    <div class="flex justify-between">
                                        <flux:text>{{ $order['payment_name'] }}</flux:text>
                                    </div>
                                </div>

                                <div class="flex justify-between">
                                    <div class="flex justify-start">
                                        <flux:heading>Jumlah Tagihan</flux:heading>
                                    </div>
                                    <div class="flex justify-between">
                                        <flux:text>
                                            @moneyIDR($order['amount'])
                                        </flux:text>
                                    </div>
                                </div>


                                <flux:input wire:model="input.paymentCode" readonly copyable :label="__('Kode Bayar')"
                                    class="mt-4" />


                                <flux:separator class="mt-2 mb-2" />

                                <div class="flex items-center justify-end gap-2">
                                    <flux:button wire:click='instructions' variant="success" style="cursor: pointer">
                                        Lihat Cara Bayar
                                    </flux:button>
                                    <flux:button wire:click="closeModal" variant="primary" style="cursor: pointer">Tutup
                                    </flux:button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($currentStep == 3)
                    <flux:heading size="lg" class="mb-4">
                        Cara Pembayaran
                    </flux:heading>
                    <!-- Instruction -->
                    <div class="flex flex-col gap-4">
                        @foreach ($inctructions as $instruction)
                            <div>
                                <flux:heading>{{ $loop->index + 1 }}. {{ $instruction['title'] }}
                                </flux:heading>
                                @foreach ($instruction['steps'] as $step)
                                    <flux:text>
                                        {{ $loop->index + 1 }}. {!! $step !!}
                                    </flux:text>
                                @endforeach
                            </div>
                        @endforeach
                        <flux:separator class="mt-2 mb-2" />
                        <div class="flex items-center justify-end gap-2">
                            <flux:button wire:click="back(2)" variant="success" style="cursor: pointer">
                                Kembali
                            </flux:button>
                            <flux:button wire:click="closeModal" variant="primary" style="cursor: pointer">Tutup
                            </flux:button>
                        </div>
                    </div>
                @endif
            @else
                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">
                            Langganan belum tersedia, silahkan kunjungi halaman ini lagi nanti.
                        </flux:heading>
                        <flux:subheading>
                            Fitur ini masih dalam pengembangan, silahkan tunggu sampai fitur ini tersedia.

                        </flux:subheading>
                    </div>


                    <div class="flex flex-col gap-6">
                        <div class="flex items-center justify-end">
                            <flux:button wire:click="$set('editSubscriptionModal', false)" variant="ghost"
                                class="me-2" style="cursor:pointer">
                                {{ __('device.button.close') }}
                            </flux:button>
                        </div>
                    </div>
            @endif
        </flux:modal>
    @endif
</div>
