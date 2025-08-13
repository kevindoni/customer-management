<section class="w-full">
    <x-layouts.help>
        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <flux:heading size="lg" class="underline md:underline-offset-8 flex gap-1">
                    <flux:icon.user-group/> Menghapus Pelanggan
                </flux:heading>
                <div class="space-y-4">
                    <flux:text>
                        Pelanggan dapat di hapus dari halaman <flux:button variant="primary" icon="users" size="xs" color="blue"
                            :href="route('customers.management')" target="_blank">Customers Management</flux:button>
                    </flux:text>
                    <flux:text class="flex flex-col gap-1">
                        Untuk menghapus pelanggan, kami menyediakan 2 opsi.
                    </flux:text>

                    <div class="flex flex-col gap-1">
                        <flux:text class="font-semibold">
                            1. Menghapus Pelanggan Beserta Data Paket di Mikrotik
                        </flux:text>

                        <flux:text>
                            Centang opsi  <flux:label>{{ trans('customer.label.delete-on-mikrotik') }}</flux:label> untuk <flux:badge size="sm" color="red">menghapus data di mikrotik</flux:badge>. Paket pelanggan yang dihapus akan tersimpan di
                            <flux:button variant="primary" color="red" icon="trash" size="xs" :href="route('deletedCustomers.management')" target="_blank">Deleted Customers</flux:button>.
                            Pelanggan yang di hapus dapat di kembalikan dengan menekan tombol
                                <flux:tooltip content="{{ trans('customer.button.restore-customer') }}">
                                    <flux:button size="xs" variant="success" icon="arrow-uturn-left"
                                        style="cursor: pointer;"
                                        title="{{ trans('customer.button.restore-customer') }}" />
                                </flux:tooltip> dan tersedia 2 opsi. Jika anda mencentang opsi <flux:label>{{ trans('customer.label.restore-on-mikrotik') }}</flux:label> maka paket pelanggan di mikrotik akan dibuat kembali oleh system. Jika opsi tersebut tidak dicentang, maka paket pelanggan diubah menjadi status dibatalkan. Anda harus memulai aktivasi ulang untuk membuat user di Mikrotik.
                        </flux:text>

                    </div>

                    <div class="flex flex-col gap-1">
                        <flux:text class="font-semibold">
                            2. Menghapus Pelanggan Dan Membiarkan Data Paket Pelanggan di Mikrotik Tetap Ada
                        </flux:text>

                        <flux:text>
                            Kosongkan opsi  <flux:label>{{ trans('customer.label.delete-on-mikrotik') }}</flux:label> agar data di mikrotik tetap ada namun system akan menonaktifkan (disable) user di mikrotik. Paket pelanggan yang dihapus akan tersimpan di
                            <flux:button variant="primary" color="red" icon="trash" size="xs" :href="route('deletedCustomers.management')" target="_blank">Deleted Pakets</flux:button>.
                            Pelanggan yang di hapus dapat di kembalikan dengan menekan tombol
                                <flux:tooltip content="{{ trans('customer.button.restore-customer') }}">
                                    <flux:button size="xs" variant="success" icon="arrow-uturn-left"
                                        style="cursor: pointer;"
                                        title="{{ trans('customer.button.restore-customer') }}" />
                                </flux:tooltip> dan tersedia 2 opsi. Jika anda mencentang opsi <flux:label>{{ trans('customer.label.restore-on-mikrotik') }}</flux:label> maka paket pelanggan di mikrotik akan daktifkan kembali oleh system. Jika opsi tersebut tidak dicentang, maka paket pelanggan diubah menjadi status dibatalkan dan data di mikrotik akan <flux:badge size="sm" color="red">dihapus</flux:badge>. Anda harus memulai aktivasi ulang untuk membuat user di Mikrotik.
                        </flux:text>

                    </div>
                </div>
            </div>
        </div>
    </x-layouts.help>
</section>
