<div>
    @if ($addMikrotikModal)
        <flux:modal class="md:w-120" wire:model="addMikrotikModal" :dismissible="false">
            <div class="space-y-4">
                <div>
                    <flux:heading size="lg">
                        @if ($mikrotik->id)
                            {{ trans('mikrotik.edit-mikrotik', ['mikrotik' => $mikrotik->name]) }}
                        @else
                            {{ trans('mikrotik.add-mikrotik') }}
                        @endif
                    </flux:heading>
                    <flux:subheading>{{ trans('mikrotik.subtitles.add-mikrotik') }}</flux:subheading>
                </div>

                <form wire:submit="{{ $mikrotik->id ? 'updateMikrotik' : 'addMikrotik' }}">
                    <div class="{{ $currentStep != 1 ? 'hidden' : '' }} flex flex-col gap-6">
                        <!--ERROR-->
                        <div class="flex flex-col">
                            <flux:error name="name" />
                            <flux:error name="host" />
                            <flux:error name="port" />
                            <flux:error name="web_port" />
                            <flux:error name="username" />
                            <flux:error name="password" />
                            <flux:error name="version" />
                            <flux:error name="subVersion1" />
                            <flux:error name="subVersion2" />
                        </div>
                        <!--NAME-->
                        <flux:field>
                            <flux:label>
                                {{ __('mikrotik.label.name') }}
                                <flux:tooltip content="{{ __('mikrotik.helper.tooltip-name') }}">
                                    <flux:badge size="sm" color="zinc" class="ms-2">?</flux:badge>
                                </flux:tooltip>
                            </flux:label>
                            <flux:input wire:model="input.name" type="text" name="name" autofocus
                                autocomplete="name" placeholder="{{ __('mikrotik.helper.name') }}" />
                        </flux:field>

                        <!--HOST-->
                        <flux:field>
                            <flux:label>
                                {{ __('mikrotik.label.host') }}
                                <flux:tooltip content="{{ __('mikrotik.helper.tooltip-host') }}">
                                    <flux:badge size="sm" color="zinc" class="ms-2">?</flux:badge>
                                </flux:tooltip>
                            </flux:label>
                            <flux:input wire:model="input.host" type="text" name="host" autofocus
                                autocomplete="mikrotik_host" placeholder="{{ __('mikrotik.helper.host') }}" />

                        </flux:field>

                        <!--USE API SSL-->
                        <flux:field variant="inline">
                            <flux:checkbox wire:model.live="input.use_ssl" />
                            <flux:label>
                                {{ __('mikrotik.label.use-ssl') }}
                                <flux:tooltip content="{{ __('mikrotik.helper.tooltip-ssl') }}">
                                    <flux:badge size="sm" color="zinc" class="ms-2"><a
                                            href="{{ route('helps.servers.mikrotik') }}" target="_blank">?</a>
                                    </flux:badge>
                                </flux:tooltip>
                            </flux:label>
                        </flux:field>
                        <div class="grid md:grid-cols-2 gap-4">
                            <!--PORT API-->
                            <flux:field>
                                <flux:label>
                                    {{ __('mikrotik.label.port') }}
                                    <flux:tooltip content="{{ __('mikrotik.helper.tooltip-port') }}">
                                        <flux:badge size="sm" color="zinc" class="ms-2">?</flux:badge>
                                    </flux:tooltip>
                                </flux:label>
                                <flux:input wire:model="input.port" type="text" name="port" autofocus
                                    autocomplete="mikrotik_port"
                                    placeholder="{{ $input['use_ssl'] ? __('mikrotik.helper.port-ssl') : __('mikrotik.helper.port') }}" />
                            </flux:field>
                            <!--PORT REST API-->
                            <flux:field>
                                <flux:label>
                                    {{ __('mikrotik.label.web-port') }}
                                    <flux:tooltip content="{{ __('mikrotik.helper.tooltip-port') }}">
                                        <flux:badge size="sm" color="zinc" class="ms-2">?</flux:badge>
                                    </flux:tooltip>
                                </flux:label>
                                <flux:input wire:model="input.web_port" type="text" name="web_port" autofocus
                                    autocomplete="web_port"
                                    placeholder="{{ $input['use_ssl'] ? __('mikrotik.helper.web-port-ssl') : __('mikrotik.helper.web-port') }}" />
                            </flux:field>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <!--USERNAME-->
                            <flux:field>
                                <flux:label>
                                    {{ __('mikrotik.label.username') }}
                                    <flux:tooltip content="{{ __('mikrotik.helper.tooltip-username') }}">
                                        <flux:badge size="sm" color="zinc" class="ms-2">?</flux:badge>
                                    </flux:tooltip>
                                </flux:label>
                                <flux:input wire:model="input.username" type="text" name="username" autofocus
                                    autocomplete="mikrotik_username"
                                    placeholder="{{ __('mikrotik.helper.username') }}" />
                            </flux:field>

                            <!--PASSWORD-->
                            <flux:field>
                                <flux:label>
                                    {{ __('mikrotik.label.password') }}
                                    <flux:tooltip content="{{ __('mikrotik.helper.tooltip-password') }}">
                                        <flux:badge size="sm" color="zinc" class="ms-2">?</flux:badge>
                                    </flux:tooltip>
                                </flux:label>
                                <flux:input wire:model="input.password" type="password" name="password" autofocus
                                    autocomplete="mikrotik_password" viewable
                                    placeholder="{{ __('mikrotik.helper.password') }}" />
                            </flux:field>
                        </div>


                        @if ($supportRestApi)
                            <flux:field variant="inline">
                                <flux:checkbox wire:model.live="input.trying_rest_api" />
                                <flux:label>
                                    {{ __('mikrotik.label.trying-with-rest-api') }}
                                </flux:label>

                            </flux:field>
                        @endif
                        @if ($withoutTest)
                            <flux:field variant="inline">
                                <flux:checkbox wire:model.live="input.add_without_test" />
                                <flux:label>
                                    {{ __('mikrotik.label.add-without-test') }}
                                </flux:label>
                            </flux:field>
                            <div class="grid md:grid-cols-2 gap-2">
                                <!--platform-->
                                <flux:field>
                                    <flux:label>
                                        {{ __('mikrotik.label.platform') }}
                                        <flux:tooltip content="{{ __('mikrotik.helper.tooltip-platform') }}">
                                            <flux:badge size="sm" color="zinc" class="ms-2">?</flux:badge>
                                        </flux:tooltip>
                                    </flux:label>
                                    <flux:input wire:model="input.platform" type="text" name="platform" autofocus
                                        autocomplete="platform" placeholder="{{ __('mikrotik.helper.platform') }}" />
                                </flux:field>

                                <!--board-name-->
                                <flux:field>
                                    <flux:label>
                                        {{ __('mikrotik.label.boardname') }}
                                        <flux:tooltip content="{{ __('mikrotik.helper.tooltip-boardname') }}">
                                            <flux:badge size="sm" color="zinc" class="ms-2">?</flux:badge>
                                        </flux:tooltip>
                                    </flux:label>
                                    <flux:input wire:model="input.boardname" type="text" name="boardname" autofocus
                                        autocomplete="boardname"
                                        placeholder="{{ __('mikrotik.helper.boardname') }}" />
                                </flux:field>
                            </div>
                            <div class="grid md:grid-cols-2 gap-2">
                                <!--version-->

                                <flux:field>
                                    <flux:label>
                                        {{ __('mikrotik.label.version') }}
                                        <flux:tooltip content="{{ __('mikrotik.helper.tooltip-version') }}">
                                            <flux:badge size="sm" color="zinc" class="ms-2">?
                                            </flux:badge>
                                        </flux:tooltip>
                                    </flux:label>
                                    <div class="grid md:grid-cols-3 gap-1">
                                        <flux:select wire:model="input.version" name="version">
                                            <flux:select.option value="">
                                                {{ trans('-') }}
                                            </flux:select.option>
                                            <flux:select.option value="6.">
                                                {{ trans('6.') }}
                                            </flux:select.option>
                                            <flux:select.option value="7.">
                                                {{ trans('7.') }}
                                            </flux:select.option>
                                        </flux:select>

                                        <flux:select wire:model="input.subVersion1" name="subVersion1">
                                            <flux:select.option value="">
                                                {{ trans('-') }}
                                            </flux:select.option>
                                            @for ($i = 0; $i <= 9; $i++)
                                                <flux:select.option value="{{ $i }}">
                                                    {{ $i }}
                                                </flux:select.option>
                                            @endfor
                                        </flux:select>
                                        <flux:select wire:model="input.subVersion2" name="subVersion2">
                                            <flux:select.option value="">
                                                {{ trans('-') }}
                                            </flux:select.option>
                                            @for ($i = 0; $i <= 9; $i++)
                                                <flux:select.option value="{{ $i }}">
                                                    {{ $i }}
                                                </flux:select.option>
                                            @endfor
                                        </flux:select>
                                    </div>
                                </flux:field>


                            </div>
                        @endif
                        <!--Button-->
                        <div class="flex items-center justify-end">
                            @if ($mikrotik->id)
                                <flux:button wire:click='updateFirstStepSubmit' variant="primary">
                                    {{ __('mikrotik.button.next') }}
                                </flux:button>
                            @else
                                <flux:button wire:click='addFirstStepSubmit' variant="primary">
                                    {{ __('mikrotik.button.next-and-test-connection') }}
                                </flux:button>
                            @endif
                        </div>
                    </div>

                    <div class="{{ $currentStep != 2 ? 'hidden' : '' }} flex flex-col gap-6">
                        <div class="flex flex-col">
                            <flux:error name="description" />
                            <flux:error name="current_password" />
                        </div>
                        <flux:field>
                            <flux:label>
                                {{ __('mikrotik.label.description') }}
                            </flux:label>
                            <flux:textarea wire:model="input.description" type="text" name="description" autofocus
                                autocomplete="mikrotik_description"
                                placeholder="{{ __('mikrotik.helper.description') }}" />
                        </flux:field>
                        <flux:field>
                            <flux:label>{{ __('mikrotik.label.user-password') }}</flux:label>
                            <flux:input wire:model="input.current_password" type="password" name="current_password"
                                autofocus autocomplete="current_password"
                                placeholder="{{ __('mikrotik.helper.user-password') }}" />
                        </flux:field>

                        <div class="flex items-center justify-end">
                            <flux:button wire:click="back(1)" variant="primary" class="me-2"
                                style="cursor:pointer">
                                {{ __('mikrotik.button.back') }}
                            </flux:button>
                            <flux:button type="submit" variant="primary" style="cursor:pointer">
                                @if ($mikrotik->id)
                                    {{ __('mikrotik.button.update') }}
                                @else
                                    {{ __('mikrotik.button.create') }}
                                @endif
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
