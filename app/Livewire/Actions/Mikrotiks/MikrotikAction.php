<?php

namespace App\Livewire\Actions\Mikrotiks;

use Illuminate\Support\Str;
use App\Traits\CaptureIpTrait;
use App\Models\Servers\Mikrotik;
use Illuminate\Support\Facades\DB;
use App\Models\Customers\AutoIsolir;
use App\Models\Servers\WanMonitoring;
use Illuminate\Support\Facades\Validator;
use App\Models\Servers\MikrotikMonitoring;
use App\Services\Mikrotiks\MikrotikService;


class MikrotikAction
{
    private MikrotikService $mikrotikService;
    /**Test Connection to Mikrotik */
    public function __construct()
    {
        // Initialize Product
        $this->mikrotikService = new MikrotikService;
    }

    /**
     * Validate and create a newly mikrotik from admin panel.
     *
     * @param  array<string, string>  $input
     */
    public function store(array $input)
    {
        DB::beginTransaction();
        try {
            $ipAddress = new CaptureIpTrait();
            $mikrotik = Mikrotik::create([
                'slug' => str(Str::random(10))->slug(),
                'name' => $input['name'],
                'username' => $input['username'],
                'password' => $input['password'],
                'host' => $input['host'],
                'use_ssl' => $input['use_ssl'],
                'port' => $input['port'],
                'web_port' => $input['web_port'],
                'merk_router' => $input['mikrotik']['platform'] ?? null,
                'version' => $input['mikrotik']['version'] ?? null,
                'type_router' => $input['mikrotik']['board-name'] ?? null,
                'description' => $input['description'] ?? null,
                'admin_ip_address' => $ipAddress->getClientIp(),
            ]);

            $autoIsolir = new AutoIsolir();
            $autoIsolir = $mikrotik->auto_isolir()->save($autoIsolir);
            $autoIsolir->forceFill([
                'slug' => str($mikrotik->name . '-' . Str::random(5))->slug(),
                'name' => str($mikrotik->name)->slug(),
                'mikrotik_id' => $mikrotik->id,
            ])->save();

            $mikrotikMonitoring = new MikrotikMonitoring();
            $mikrotikMonitoring = $mikrotik->mikrotik_monitoring()->save($mikrotikMonitoring);
            $mikrotikMonitoring->forceFill([
                'slug' => str($mikrotik->name . '-' . Str::random(5))->slug(),
                'mikrotik_id' => $mikrotik->id,
            ])->save();

            DB::commit();
            return ([
                'status' => 'success',
                'title' => trans('mikrotik.alert.connected'),
                'message' => trans('mikrotik.alert.connected') . ' ⇌ ' .  trans('mikrotik.alert.connected-boardname', ['boardname' => $input['mikrotik']['board-name']])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return ([
                'status' => 'error',
                'title' => trans('mikrotik.alert.failed'),
                'message' => trans('mikrotik.alert.failed') . ' ⇌ ' . trans('mikrotik.alert.failed-to-connect', ['host' => $input['host'], 'message' => $e->getMessage()])
            ]);
        }
    }

    /**
     * Validate and edit a mikrotik from admin panel.
     *
     * @param  array<string, string>  $input
     */
    public function update(Mikrotik $mikrotik, array $input)
    {

        $ipAddress = new CaptureIpTrait();
        $mikrotik->forceFill([
            'username' => $input['username'],
            'password' => $input['password'],
            'host' => $input['host'],
            'port' => $input['port'],
            'use_ssl' => $input['use_ssl'],
            'merk_router' => $input['mikrotik']['platform'] ?? null,
            'version' => $input['mikrotik']['version'] ?? null,
            'type_router' => $input['mikrotik']['board-name'] ?? null,
            'updated_ip_address' => $ipAddress->getClientIp(),
            'description' => $input['description'] ?? null,
        ])->save();
        //  } catch (\Exception $e) {
        //      return ([
        //          'status' => 'error',
        //           'title' => trans('mikrotik.alert.failed'),
        //         'message' => trans('mikrotik.alert.failed') . ' ⇌ ' . trans('mikrotik.alert.failed-to-connect', ['host' => $input['host'], 'message' => $e->getMessage()])
        //      ]);
        //  }
        // }




    }

    /**
     * Validate delete mikrotik from admin panel.
     *
     * @param  array<string, string>  $input
     */
    public function delete(Mikrotik $mikrotik, array $input)
    {
        Validator::make($input, [
            'current_password' => ['required', 'string', 'current_password:web'],
            'selectedServer' => ['required']
        ])->validate();

        $pakets = $mikrotik->pakets;
        $ipAddress = new CaptureIpTrait();
        if ($input['selectedServer'] == $mikrotik->id) {
            foreach ($pakets as $paket) {
                $paket->delete();
            }
        } else {
            foreach ($pakets as $paket) {
                $paket->update([
                    'mikrotik_id' => $input['selectedServer']
                ]);
            }
        }
        $mikrotik->deleted_ip_address = $ipAddress->getClientIp();
        $mikrotik->save();
        $mikrotik->delete();
    }
}
