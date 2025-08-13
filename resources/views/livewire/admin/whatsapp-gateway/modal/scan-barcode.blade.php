<div>
    @if ($scanBarcodeModal)
        <flux:modal class="md:min-w-sm" wire:model="scanBarcodeModal" :dismissible="false" @close="$js.refreshData">
            <div id="qrcode">
                <flux:heading size="lg">
                    {{ trans('device.heading.whatsapp-account') }}
                </flux:heading>
                <flux:heading>{{ $number }} </flux:heading>
                <flux:subheading><span class="text-red-500">{{ trans('device.heading.dont-leave-phone') }}</span>
                </flux:subheading>

                <flux:separator class="mt-2 mb-2" />

                <div class="grid auto-rows-min px-3 py-4">
                    <div
                        class="barcode-image items-center justify-center flex rounded-lg border-2 border-neutral-200 dark:border-neutral-700">
                        <div class="py-15">
                            <flux:icon.loading class="size-20" />
                        </div>
                    </div>

                    <div id="connection-status" class="items-center justify-center flex gap-2 mt-2 w-80">
                        <flux:icon.loading variant="solid" class="loader text-emerald-500 dark:text-emerald-300" />
                        <flux:text>
                            <span class="text-message">{{ trans('device.connecting-whatsapp-server') }}</span>
                        </flux:text>

                    </div>

                </div>
            </div>
            <!-- If device connected -->
            <div id="profile-wa" class="hidden">
                <div class="flex">
                    <div class="pp-image p-2">
                        <flux:icon.loading class="size-20" />
                    </div>
                    <div class="p-2">
                        <flux:heading size="lg">
                            {{ trans('device.heading.whatsapp-account') }}
                        </flux:heading>
                        <flux:heading>{{ $number }} </flux:heading>
                        <flux:subheading>{{ trans('device.heading.disconnect-device') }}
                        </flux:subheading>
                    </div>

                </div>

                <flux:separator class="mt-2 mb-2" />

                <div class="grid auto-rows-min gap-2 px-3 py-4">
                    <div class="md:flex gap-2">
                        <flux:heading> {{ trans('device.label.device-name') }}:</flux:heading>
                        <flux:subheading>
                            <span id="device-name" class="text-wrap">
                                <flux:icon.loading />
                            </span>
                        </flux:subheading>
                    </div>

                    <div class="md:flex gap-2">
                        <flux:heading>
                            {{ trans('device.label.device-number') }}:
                        </flux:heading>
                        <flux:subheading>
                            <span id="device-number" class="text-wrap">
                                <flux:icon.loading />
                            </span>
                        </flux:subheading>
                    </div>
                    <div class="md:flex gap-2">
                        <flux:heading>
                            {{ trans('device.label.device-device') }}:
                        </flux:heading>
                        <flux:subheading>
                            <span id="device-device" class="text-wrap">
                                <flux:icon.loading />
                            </span>
                        </flux:subheading>
                    </div>


                    <div class="items-center justify-center flex mt-2 ">
                        <div id="connection-status" class="hidden">
                            <flux:badge color="rose" icon="loading">
                                <span class="text-wrap text-message"></span>
                            </flux:badge>
                        </div>
                        <div id="disconnection-button" class="gap-4 flex">
                            <flux:button variant="danger" size="sm" icon="arrow-right-circle"
                                style="cursor: pointer;" wire:click="$js.logout('{{ $number }}')">
                                {{ trans('device.button.disconnect') }}
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- If Expired Subscribtion -->
            <div id="expired" class="hidden">
                <flux:heading size="lg">
                    {{ trans('device.heading.whatsapp-account') }}
                </flux:heading>
                <flux:heading>{{ $number }} </flux:heading>
                <flux:subheading>
                    <span id="header-content" class="text-red-500">
                        {{ trans('device.heading.dont-leave-phone') }}
                    </span>
                </flux:subheading>

                <flux:separator class="mt-2 mb-2" />
                <div
                    class="items-center justify-center flex rounded-lg border-2 border-neutral-200 dark:border-neutral-700">
                    <div class="py-15">
                        <flux:icon.x-circle class="size-20" />
                    </div>
                </div>
                <div id="connection-status" class="grid auto-rows-min px-3 py-4">
                    <div class="items-center justify-center flex mt-2">
                        <flux:badge color="red" icon="loading">
                            <span
                                class="text-message text-wrap">{{ trans('device.connecting-whatsapp-server') }}</span>
                        </flux:badge>
                    </div>

                </div>
            </div>

        </flux:modal>
        @script
            <script wire-navigate-once>
                $wire.on('get-whatsapp-barcode', (data) => {
                    //console.log(data[0]['body'] + data[1]['privatekey'] + data[1]['url']);
                    const device = data[0]['body'];
                    const url = data[1]['url'];
                    //const privatekey = data[1]['privatekey'];
                    //const socket = new io(data[1]['url'], {
                    //    transports: ['websocket', 'polling', 'flashsocket']
                    //});
                    const username = data[1]['user-name'];
                    
                    const socket = new io(url, {
                        transports: ['websocket'],
                        withCredentials: true,
                        extraHeaders: {
                            "my-custom-header": "abcd"
                        },
                        query: {
                            "user-name": username
                        }
                    });
                    
                    socket.emit('get-qrcode', device)
                    socket.on('qrcode', ({
                        token,
                        data,
                        message
                    }) => {
                        //console.log('show qrcode ' + message);
                        if (token == device) {
                            $('#qrcode .barcode-image').html(` <img src="${data}">`)
                            $('#qrcode #connection-status .text-message').text(message)
                        }
                    })
                    socket.on('connection-open', ({
                        token,
                        user,
                        ppUrl
                    }) => {
                        if (token == device) {
                            //  $wire.dispatch('delete-cache-device');
                            $('#qrcode').addClass('hidden');
                            $('#profile-wa').removeClass('hidden');
                            $('.pp-image').html(` <img src="${ppUrl}" height="150px" alt="${token}">`)
                            $('#device-name').html(user.name ?? 'undefined')
                            $('#device-number').html(user.id)
                            $('#device-device').html(token)
                            $('#loading').addClass('hidden');
                            $('#profile-wa #disconnection-button').removeClass('hidden');
                        }
                    })

                    socket.on('unauthorized', ({
                        token,
                        message
                    }) => {
                        if (token == device) {
                            $('#connection-status .text-message').text(message)
                        }
                    })

                    socket.on('subscription-suspended', ({
                        message
                    }) => {
                        console.log('suspended');
                        $('#qrcode').addClass('hidden');
                        $('#expired').removeClass('hidden');
                        $('#expired #connection-status .text-message').text(message)
                    })
                    
                    socket.on("connect_error", (err) => {
                        $('#qrcode').addClass('hidden');
                        $('#expired').removeClass('hidden');
                        $('#expired #connection-status .text-message').text(err.message)
                        $('#header-content').text(err.data.content)
                    })
                    
                    socket.on('message', ({
                        token,
                        message
                    }) => {
                        //console.log('Message: ' + message + ' Token: ' + token + ' Device: ' + device);
                        // $('#connection-status .text-message').text(message);
                        if (token == device) {
                            $('#connection-status .text-message').removeClass('hidden');
                            // $('#disconnection-button').addClass('hidden');
                            $('#connection-status .text-message').text(message);
                            // if (message.includes('Connection closed')) {
                            //     $('#connection-status .loader').addClass('hidden');
                            //  }
                            if (message.includes('QR ended')) {
                                $('#connection-status .loader').addClass('hidden');
                            }
                            if (message.includes('Connection closed')) {
                                 let count = 5;
                                $('#profile-wa #connection-status').removeClass('hidden');
                                  let interval = setInterval(() => {
                                $('#profile-wa #connection-status .text-message').text(
                                    `${message} in ${count} second`);
                                if (count == 0) {
                                    clearInterval(interval);
                                    $wire.dispatch('close-scan-barcode-modal');
                                }
                                    count--;
                             }, 1000);
                            }
                        }
                    });
                    /*
                                        socket.on('logged-out', ({
                                            token,
                                            message
                                        }) => {
                                            if (token == device) {
                                             //   let count = 5;
                                                $('#connection-status .text-message').removeClass('hidden');
                                                $('#connection-status .text-message').text(message);
                                                $('#profile-wa #connection-status').removeClass('hidden');
                                                $('#disconnection-button').addClass('hidden');
                                              //  let interval = setInterval(() => {
                                              //      $('#profile-wa #connection-status .text-message').text(
                                             //           `${message} in ${count} second`);
                                             //       if (count == 0) {
                                              //          clearInterval(interval);
                                              //          $wire.dispatch('close-scan-barcode-modal');
                                               //     }
                                                //    count--;
                                               // }, 1000);
                                            }
                                        })

                                        socket.on('qr-ended', ({
                                            token,
                                            message
                                        }) => {
                                            if (token == device) {
                                               // $('#profile-wa #connection-status').removeClass('hidden');
                                               // $('#disconnection-button').addClass('hidden');
                                                $('#connection-status .loader').addClass('hidden');
                                            }
                                        })
                    */
                    $js('logout', (device) => {
                        console.log('logout: ' + device)
                        socket.emit('LogoutDevice', device)
                    })

                    $js('refreshData', () => {
                        $wire.dispatch('close-scan-barcode-modal');
                    })

                })
            </script>
        @endscript

    @endif
</div>
