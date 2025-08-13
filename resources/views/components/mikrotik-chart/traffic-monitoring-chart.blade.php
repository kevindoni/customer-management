<div wire:ignore id="{!! $chartId !!}"></div>

@push('scripts')
    <script>
        (function() {
            const options = {
                chart: {
                    type: 'area',
                    stacked: false,
                    height: 350,
                    zoom: {
                        type: 'x',
                        enabled: true,
                        autoScaleYaxis: true
                    },
                    toolbar: {
                        autoSelected: 'zoom'
                    }
                },
                grid: {
                    show: true,
                    borderColor: '#dddfdf',
                    strokeDashArray: 0,
                    position: 'back',
                    xaxis: {
                        lines: {
                            show: true
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    },
                    row: {
                        //  colors: '#90A4AE',
                        opacity: 0.5
                    },
                    column: {
                        //  colors: '#90A4AE',
                        opacity: 0.5
                    },

                },
                dataLabels: {
                    enabled: false,
                    textAnchor: 'start',
                    formatter: function(value) {
                        const sizes = ['bps', 'kbps', 'Mbps', 'Gbps', 'Tbps'];
                        if (value === 0) return '0 bps';
                        const i = parseInt(Math.floor(Math.log(value) / Math.log(1024)));
                        return parseFloat((value / Math.pow(1024, i)).toFixed(2)) + ' ' + sizes[i];
                    },
                },
                series: [{
                        name: 'Tx Rate',
                        data: [],
                        type: "area",
                    },
                    {
                        name: 'Rx Rate',
                        data: [],
                        type: "area",
                    },
                ],
                title: {
                    text: '',
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            const sizes = ['bps', 'kbps', 'Mbps', 'Gbps', 'Tbps'];
                            if (value === 0) return '0 bps';
                            const i = parseInt(Math.floor(Math.log(value) / Math.log(1024)));
                            return parseFloat((value / Math.pow(1024, i)).toFixed(2)) + ' ' + sizes[
                                i];
                        },
                    },
                },
                colors: ["#0090fc", "#ff6666"],
                stroke: {
                    curve: "smooth",
                    width: 2,
                    dashArray: [0, 0, 3]
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        inverseColors: false,
                        opacityFrom: 0.5,
                        opacityTo: 0,
                        stops: [0, 90, 100]
                    },
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            const sizes = ['bps', 'kbps', 'Mbps', 'Gbps', 'Tbps'];
                            if (value === 0) return '0 bps';
                            const i = parseInt(Math.floor(Math.log(value) / Math.log(1024)));
                            return parseFloat((value / Math.pow(1024, i)).toFixed(2)) + ' ' + sizes[
                                i];
                        },
                    }
                },
                markers: {
                    size: 3,
                    strokeWidth: 3,
                    hover: {
                        size: 4,
                        sizeOffset: 2
                    }
                },
                xaxis: {
                    range: 15,
                },
                noData: {
                    text: 'Loading data...'
                },


            };

            const chart = new ApexCharts(document.getElementById('{!! $chartId !!}'), options);
            chart.render();

            Livewire.on('traffic-from-mikrotik-{!! $chartId !!}', (event) => {

                var mikrotikTraffic = JSON.parse(event[0].traffic);


                chart.appendData([{
                        name: 'Tx Rate',
                        data: mikrotikTraffic[0].Tx
                    },
                    {
                        name: 'Rx Rate',
                        data: mikrotikTraffic[0].Rx
                    },
                ])
            });

        }());
    </script>
@endpush
