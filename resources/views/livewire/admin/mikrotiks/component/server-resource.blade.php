<div>
    @if ($mikrotikOnline)
        <div wire:poll.10s="routerResource">
            <div class="flex flex-1 flex-col md:flex-row lg:flex-row mx-2" x-data="getResource()" x-init="fetchResources()">
                <div class=" bg-white mx-2 w-full">
                    <div class="font-bold text-xl">{{ trans('mikrotik.label.resources') }}
                    </div>
                    <div
                        class="grid gap-4 grid-cols-2 py-2 border-b border-light-grey text-sm text-gray-900  dark:text-gray-400">
                        <div>

                            <div>
                                {{ trans('mikrotik.table.platform') }}: <span x-text="getPlatform()">
                            </div>
                            <div>
                                {{ trans('mikrotik.table.boardname') }}: <span x-text="getBoardName()">
                            </div>
                            <div>
                                {{ trans('mikrotik.table.version') }}: <span x-text="getVersion()">
                            </div>
                        </div>

                        <div>
                            <div>
                                {{ trans('mikrotik.label.cpu') }}: <span x-text="getCpu()">
                            </div>
                            <div>
                                {{ trans('mikrotik.label.cpu-frequency') }}: <span x-text="getCpuFrequency()">
                            </div>
                            <div>
                                {{ trans('mikrotik.label.cpu-count') }}: <span x-text="getCpuCount()">
                            </div>
                            <div>
                                {{ trans('mikrotik.label.mikrotik-uptime') }}:
                                <span x-text="getUptime()"></span>
                            </div>
                        </div>
                    </div>



                    <div class="mt-2">
                        <div class="mb-2">
                            <div class="text-sm text-gray-900  dark:text-gray-400">
                                {{ trans('mikrotik.label.cpu') }}:
                            </div>
                            <div class="flex shadow w-full bg-gray-100 border border-gray-200">
                                <div class="bg-blue-500 border leading-none  px-3" style="width: 50%"
                                    x-bind:style="'width: ' + getCPULoad() + '%'">

                                </div>

                                <span
                                    class="w-10 inline-flex items-center px-3 text-sm text-gray-900  dark:text-gray-400 dark:border-gray-600">
                                    <span x-text="getCPULoad()"></span>%
                                </span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="text-sm text-gray-900  dark:text-gray-400">
                                {{ trans('mikrotik.label.memory') }}: <span x-text="getMemoryTotal()">
                            </div>
                            <div class="flex shadow w-full bg-gray-100 border border-gray-200">
                                <div class="bg-teal-500 border leading-none  px-3" style="width: 50%"
                                    x-bind:style="'width: ' + getPersenMemoryUse() + '%'">
                                </div>
                                <span
                                    class="w-1/12 inline-flex items-center px-3 text-sm text-gray-900 dark:text-gray-400 dark:border-gray-600">
                                    {{ trans('mikrotik.label.usage') }}: <span x-text="getMemoryUse()"></span>
                                </span>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="text-sm text-gray-900  dark:text-gray-400">
                                {{ trans('mikrotik.label.hdd') }}: <span x-text="getHDDTotal()"></span>
                            </div>
                            <div class="flex shadow w-full bg-gray-100 border border-gray-200">
                                <div class="bg-orange-500 border leading-none  px-3" style="width: 50%"
                                    x-bind:style="'width: ' + getPersenHDDUse() + '%'">
                                </div>
                                <span
                                    class="w-1/12 inline-flex items-center px-3 text-sm text-gray-900 dark:text-gray-400 dark:border-gray-600">
                                    {{ trans('mikrotik.label.usage') }}: <span x-text="getHDDUse()"></span>
                                </span>
                            </div>
                        </div>


                    </div>



                </div>
            </div>
        </div>
    @endif
    @if ($mikrotikOnline)
        @push('scripts')
            <script data-navigate-once>
                function getResource() {
                    return {
                        resources: [],
                        times: [],
                        fetchResources() {


                            Livewire.on('resource-from-mikrotik-{{ $mikrotik->slug }}', (event) => {
                                var mikrotikResources = JSON.parse(event[0].resources);
                                this.resources = mikrotikResources[0];
                                var timesResources = JSON.parse(event[0].times);
                                this.times = timesResources[0];
                                // console.log(this.times);
                            });

                        },
                        getCPULoad() {
                            return this.resources.cpuLoad
                        },

                        getMemoryUse() {
                            return this.resources.useMemoryByte
                        },
                        getMemoryTotal() {
                            return this.resources.totalMemoryByte
                        },
                        getPersenMemoryUse() {
                            return Math.round(((this.resources.totalMemory - this.resources.freeMemory) / this.resources
                                .totalMemory) * 100);
                        },


                        getHDDTotal() {
                            return this.resources.totalHddByte
                        },
                        getHDDUse() {
                            return this.resources.useHddByte
                        },

                        getPersenHDDUse() {
                            return Math.round((this.resources.useHdd / this.resources.totalHdd) * 100);
                        },

                        getUptime() {
                            return this.resources.uptime
                        },

                        getMikrotikTime() {
                            return this.times.time
                        },

                        getVersion() {
                            return this.resources.version
                        },
                        getPlatform() {
                            return this.resources.platform
                        },
                        getBoardName() {
                            return this.resources.boardName
                        },
                        getCpu() {
                            return this.resources.cpu
                        },
                        getCpuFrequency() {
                            return this.resources.cpuFrequency
                        },
                        getCpuCount() {
                            return this.resources.cpuCount
                        },
                        getArc() {
                            return this.resources.arc
                        },

                    }
                }
            </script>
        @endpush
    @endif
</div>
