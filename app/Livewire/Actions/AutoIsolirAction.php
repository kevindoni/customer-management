<?php

namespace App\Livewire\Actions;

use App\Models\Websystem;
use Illuminate\Support\Str;
use App\Models\Customers\AutoIsolir;
use App\Services\Mikrotiks\ScriptService;
use App\Services\Mikrotiks\MikrotikPppService;


class AutoIsolirAction
{

    private ScriptService $scriptService;
    private MikrotikPppService $pppService;
    public function __construct()
    {
        // Initialize
        $this->scriptService = new ScriptService;
        $this->pppService = new MikrotikPppService;
    }
    /**
     * Validate and create a newly mikrotik from admin panel.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input)
    {
        try {
            if ($input['selectedAutoIsolirOption'] == 'false') {
                $activationDate = false;
            } else {
                $activationDate = true;
            }

            $autoisolir = AutoIsolir::create([
                'slug' => str(Str::random(10))->slug(),
                'name' => $input['name'],
                'mikrotik_id' => $input['selectedServer'],
                'profile_id' => $input['selectedProfile'],
                'script_id' => 0,
                'schedule_id' => 0,
                'activation_date' => $activationDate,
                'due_date' => $input['due_date'] ?? null,
                'nat_id' => 0,
                'proxy_access_id' => 0,
                'address_list_isolir' => $input['address_list_isolir'],
                // 'run_isolir_with' => $input['selectedRunIsolirOption'],
                // 'address_list_non_isolir' => $address_list_non_isolir,
            ]);
            return [
                'success' => true,
                'data' => $autoisolir
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function update(AutoIsolir $autoisolir, array $input)
    {
        try {
            $input['selectedAutoIsolirOption'] == 'false' ? $activationDate = false : $activationDate = true;


            if ( Websystem::first()->isolir_driver == 'mikrotik') $this->remove_script_isolir_from_mikrotik($autoisolir);

            $autoisolir->update([
                'name' => $input['name'],
                'profile_id' => $input['selectedProfile'],
                'script_id' => 0,
                'schedule_id' => 0,
                'activation_date' => $activationDate,
                'due_date' => $input['due_date'] ?? null,
                'nat_id' => 0,
                'proxy_access_id' => 0,
                'address_list_isolir' => $input['address_list_isolir'],
                // 'run_isolir_with' => $input['selectedRunIsolirOption'],
            ]);
            return [
                'success' => true,
                'data' => $autoisolir
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function add_script_to_mikrotik(AutoIsolir $autoIsolir)
    {
        //dd('mikrotik');
        $mikrotik = $autoIsolir->mikrotik;
        $commentUnpayment = Websystem::first()->comment_unpayment;
        // Check Profile isolir
        $profiles = $this->pppService->getProfile($mikrotik, $autoIsolir->profile_id);

        if (in_array($autoIsolir->profile_id, $profiles[0] ?? [])) {

            //Add Script
            if (version_compare('7.10.0', $mikrotik->version, '<=')) {
                //ROS version 7.10+
                $script = $this->scriptService->add_auto_isolir_mikrotik_v710($autoIsolir, $commentUnpayment);
            } else {
                //ROS version 6.4 - 7.9
                $script = $this->scriptService->add_auto_isolir_mikrotik_v640($autoIsolir, $commentUnpayment);
            }

            //Add Schedule
            $schedule = $this->scriptService->addScheduleEveryDay($autoIsolir);

            //Save Script ID if success
            if (isset($script['after']['message'])) {
                if ($script['after']['message'] == 'failure: item with such name already exists') {
                    //  $this->buttonReset = true;
                    $status = 'error';
                    $alert_title = 'Failure!';
                    $alert_message = 'Script with such name already exists';
                } else {
                    $status = 'error';
                    $alert_title = 'Failure!';
                    $alert_message = 'An error occurred while creating the script, ' . $script['after']['message'];
                }
            } else {
                $scriptId = $script['after']['ret'];
            }

            //Save Schedule ID if success
            if (isset($schedule['after']['message'])) {

                if ($schedule['after']['message'] == 'failure: item with this name already exists') {
                    // $this->buttonReset = true;
                    $status = 'error';
                    $alert_title = 'Failure!';
                    $alert_message = 'Scedule with such name already exists';
                } else {
                    $status = 'error';
                    $alert_title = 'Failure!';
                    $alert_message = 'An error occurred while creating the schedule, ' . $schedule['after']['message'] . ', Script creation cancelled!';
                }
            } else {
                $scheduleId = $schedule['after']['ret'];
                $autoIsolir->update([
                    'script_id' => $scriptId,
                    'schedule_id' => $scheduleId,
                    'disabled' => false,
                ]);

                $status = 'success';
                $alert_title = trans('autoisolir.alert.success');
                $alert_message = trans('autoisolir.alert.activation-message-successfully', ['name' => $autoIsolir->name, 'mikrotik' => $autoIsolir->mikrotik->name]);
            }

            return [
                'status' => $status,
                'title' =>  $alert_title,
                'message' =>  $alert_message
            ];
        } else {

            return [
                'status' => 'error',
                'title' =>  trans('autoisolir.alert.failed'),
                'message' =>  trans('autoisolir.alert.profile-notfound', ['profile_isolir' => $autoIsolir->profile_id, 'mikrotik' => $autoIsolir->mikrotik->name])
            ];
        }
    }

    public function reset_script_mikrotik(AutoIsolir $autoIsolir)
    {

        $this->remove_script_isolir_from_mikrotik($autoIsolir);
        $response = $this->add_script_to_mikrotik($autoIsolir);
        if ($response['status'] == 'success') {
            return [
                'status' => 'success',
                'title' =>  trans('autoisolir.alert.success'),
                'message' =>  trans('autoisolir.alert.reset-message-successfully', ['name' => $autoIsolir->name, 'mikrotik' => $autoIsolir->mikrotik->name])
            ];
        } else {
            return $response;
        }
    }

    public function remove_script_isolir_from_mikrotik(AutoIsolir $autoIsolir)
    {
        $this->scriptService->removeScriptByName($autoIsolir->mikrotik, $autoIsolir->name);
        $this->scriptService->removeScheduleByName($autoIsolir->mikrotik, $autoIsolir->name);
        $autoIsolir->update([
            'script_id' => 0,
            'schedule_id' => 0,
           // 'disabled' => true
        ]);
    }
}
