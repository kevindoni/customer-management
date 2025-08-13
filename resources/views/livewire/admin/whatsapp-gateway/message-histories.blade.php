<section class="w-full">
    <x-layouts.general-setting :heading="__('Message Histories')">
        <x-layouts.whatsapp-gateway.nav-mobile/>
        <div class="my-6 w-full space-y-6">
            <div wire:loading.class="opacity-75" class="relative overflow-x-auto ">
                <x-tables.table class="table-fixed">
                    <x-slot name="header">
                        <x-tables.theader>
                            <x-tables.header>{{ trans('whatsapp-gateway.table.no') }}</x-tables.header>
                            <x-tables.header>{{ trans('Sender') }}</x-tables.header>
                            <x-tables.header>{{ trans('Receive') }}</x-tables.header>
                            <x-tables.header>{{ trans('Message') }}</x-tables.header>
                            <x-tables.header>{{ trans('Status') }}</x-tables.header>
                            <x-tables.header>{{ trans('From') }}</x-tables.header>
                            <x-tables.header>{{ trans('whatsapp-gateway.table.action') }}</x-tables.header>
                        </x-tables.theader>
                    </x-slot>
                    <x-slot name="body">
                        @forelse ($messageHistories as $key => $messageHistory)
                            <x-tables.row>
                                <x-tables.cell
                                    class="text-center">{{ ($messageHistories->currentpage() - 1) * $messageHistories->perpage() + $loop->index + 1 }}</x-tables.cell>
                                <x-tables.cell> {{ $messageHistory['device'] }}</x-tables.cell>
                                <x-tables.cell> {{ $messageHistory['receiver'] }}</x-tables.cell>
                                <x-tables.cell> {{ $messageHistory['message'] }}</x-tables.cell>
                                <x-tables.cell class="text-center">
                                    <flux:badge color="{{ $messageHistory['status'] == 4 ? 'indigo' : ($messageHistory['status'] == 3 ? 'emerald' : ($messageHistory['status'] == 1 || $messageHistory['status'] == 2 ? 'amber' : 'rose')) }}">
                                     {{  $messageHistory['status'] == 4 ? 'Read' : ($messageHistory['status'] == 3 ? 'Sent' : ($messageHistory['status'] == 1 || $messageHistory['status'] == 2 ? 'Pending' : 'Unsent')) }}
                                    </flux:badge>
                                    </x-tables.cell>
                                <x-tables.cell class="text-center"> {{ $messageHistory['send_from'] }}</x-tables.cell>
                                <x-tables.cell>
                                    <flux:tooltip content="Delete">
                                    <flux:button variant="danger" icon="trash" disabled/>
                                    </flux:tooltip>
                                </x-tables.cell>
                            </x-tables.row>
                        @empty
                            <x-tables.row>
                                <x-tables.cell colspan=7>
                                    <div class="flex justify-center items-center">
                                        <span class="font-medium py-8 text-gray-400 text-xl">
                                            {{ trans('Anda belum memiliki pesan') }}
                                        </span>
                                    </div>
                                </x-tables.cell>
                            </x-tables.row>
                        @endforelse
                    </x-slot>
                </x-tables.table>
                @if ($messageHistories->hasPages())
                    <div class="p-3">
                        {{ $messageHistories->links() }}
                    </div>
                @endif


            </div>
    </x-layouts.whatsapp-gateway.layout>
</section>
