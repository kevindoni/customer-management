<?php
namespace App\Livewire\Actions\Mikrotiks;

use Illuminate\Support\Str;
use App\Models\Servers\MikrotikMonitoring;

class MikrotikMonitoringAction
{

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function add_monitoring(array $input): MikrotikMonitoring
    {
        //return dd($input['interface']['name']);
        return MikrotikMonitoring::create([
            'slug' => str(Str::random(10))->slug(),
            'mikrotik_id' => $input['selectedServer'],
            'interface' => $input['interface'],
            'interface_type' => $input['interface_type'],
            'min_download' => $input['min_download'],
            'max_download' => $input['max_download'],
            'min_upload' => $input['min_upload'],
            'max_upload' => $input['max_upload'],

        ]);
    }

    public function update_monitoring(MikrotikMonitoring $mikrotikMonitoring, array $input): MikrotikMonitoring
    {


        $mikrotikMonitoring->forceFill([
            'interface' => $input['interface'],
            'interface_type' => $input['interface_type'],
            'min_download' => $input['min_download'],
            'max_download' => $input['max_download'],
            'min_upload' => $input['min_upload'],
            'max_upload' => $input['max_upload'],
        ])->save();

        return $mikrotikMonitoring;
    }



    /**
     * Validate delete mikrotik from admin panel.
     *
     * @param  array<string, string>  $input
     */
    public function delete_monitoring(MikrotikMonitoring $mikrotikMonitoring)
    {
        $mikrotikMonitoring->delete();
    }
}
