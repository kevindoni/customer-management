<?php

namespace App\Services\Mikrotiks;


use Exception;
use RouterOS\Query;
use RouterOS\Client;
use RouterOS\Config;
use App\Models\Servers\MikrotikMonitoring;
use App\Models\Customers\AutoIsolir;
use RouterOS\Exceptions\QueryException;
use RouterOS\Exceptions\ClientException;
use RouterOS\Exceptions\ConfigException;
use RouterOS\Exceptions\ConnectException;
use RouterOS\Exceptions\BadCredentialsException;

class ScriptService
{
    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     * @throws Exception
     */
    public function getMikrotik($routerboard): Client
    {
        $mikrotik = $routerboard;
        if (!$mikrotik) {
            throw new Exception('Mikrotik not found.');
        }

        $config = (new Config())
            ->set('host', $mikrotik->host)
            ->set('port', (int) $mikrotik->port)
            ->set('pass', $mikrotik->password)
            ->set('user', $mikrotik->username)
            ->set('ssl', (bool) $mikrotik->use_ssl);

        return new Client($config);
    }

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    /*  public function getAllScript($routerboard)
    {
        $client = $this->getMikrotik($routerboard);
        $query = new Query('/system/script/print');
        return $client->query($query)->read();
    }

    public function getScriptByName($routerboard, $scheduleName)
    {
        $client = $this->getMikrotik($routerboard);
        $query = (new Query('/system/script/print'))
            ->where('name', $scheduleName);
        return $client->query($query)->read();
    }

    public function getScriptById($routerboard, $id)
    {
        // $router = $routerboard;
        $client = $this->getMikrotik($routerboard);
        $query = (new Query('/system/script/print'))
            ->where('.id', $id);

        return $client->query($query)->read();

    }
*/


    /**
     *
     * Add script auto isolir with due date to mikrotik Ros Version 6.4 to 7.9
     *
     */

    public function addAutoisolirDueDate($routerboard, $rosVersion, $nameScript, $dueDate, $unpayment, $profile, $address_list)
    {
        $sourceScript = '
         /ip firewall address-list remove [/ip firewall address-list find list="' . $address_list . '"]
         :local day [:pick [/system clock get date] 4 6]
         :if (day >= "' . $dueDate . '") do={
         :local userppp
         :foreach v in=[/ppp secret find where comment="' . $unpayment . '"] do={
         :set userppp ( userppp [/ppp secret get $v name])
         /ppp active remove [find name=$userppp]
         /ppp secret set profile=' . $profile . ' [find name=$userppp];
         }



         :local userstatic
         foreach v in=[/queue simple find where comment="' . $unpayment . '"] do={
         :set userstatic ( userstatic [/queue simple get $v target])
         /ip firewall address-list add list=' . $address_list . ' address=$userstatic
         }
         }';
        $comment = 'create_by_customer_management_mikrotik_<7.10';

        $client = $this->getMikrotik($routerboard);
        $query =
            (new Query('/system/script/add'))
            ->equal('name', $nameScript)
            ->equal('source', $sourceScript)
            ->equal('comment', $comment);

        return $client->query($query)->read();
    }


    /**
     *
     * Add script auto isolir with due date to mikrotik Ros Version >= 7.10
     *
     */
    public function addAutoisolirDueDate710($routerboard, $rosVersion, $nameScript, $dueDate, $unpayment, $profile, $address_list)
    {
        $sourceScript = '
            /ip firewall address-list remove [/ip firewall address-list find list="' . $address_list . '"]
            :local day [:pick [/system clock get date] 8 10]
            :if (day >= "' . $dueDate . '") do={
            :local userppp
            :foreach v in=[/ppp/secret find comment="' . $unpayment . '"] do={
            :set userppp ( userppp [/ppp secret get $v name])
            /ppp active remove [find name=$userppp]
            /ppp secret set profile=' . $profile . ' [find name=$userppp];
            }


            :local userstatic
            foreach v in=[/queue/simple find comment="' . $unpayment . '"] do={
            :set userstatic ( userstatic [/queue simple get $v target])
            /ip firewall address-list add list=' . $address_list . ' address=$userstatic
            }}';

        $comment = 'create_by_customer_management_mikrotik_>=7.10';


        $client = $this->getMikrotik($routerboard);
        $query =
            (new Query('/system/script/add'))
            ->equal('name', $nameScript)
            ->equal('source', $sourceScript)
            ->equal('comment', $comment);

        return $client->query($query)->read();
    }

    //Mikrotik <V7.10
    public function script_auto_isolir_activation_date_v640($address_list, $profileIsolir, $commentUnpayment)
    {
        return '
        :local date [/system clock get date]
        :local yyyy  [:pick $date 7 11]
       :local MM ([:find "janfebmaraprmayjunjulaugsepoctnovdec" [:pick $date 0 3] ] / 3 + 1)
       :if ($MM < 10) do={:set MM "0$MM"}
       :local dd    [:pick $date 4 6]
       /ip firewall address-list remove [/ip firewall address-list find list="' . $address_list . '"]

        :for e from 1 to $dd do={
        :if ($e < 10) do={:set e "0$e"}
        :local duedate    "' . $commentUnpayment . '_$e_$MM_$yyyy"
        :local userppp
        :foreach v in=[/ppp secret find where comment=$duedate] do={
        :set userppp ( userppp [/ppp secret get $v name])
        /ppp active remove [find name=$userppp]
        /ppp secret set profile=' . $profileIsolir . ' [find name=$userppp];
        :log info  "Isolir $userppp change profile to ' . $profileIsolir . ' by Customer Management Script"
        }

        :local userstatic
        foreach v in=[/queue simple find where comment="$duedate"] do={
        :set userstatic ( userstatic [/queue simple get $v target])
        /ip firewall address-list add list=' . $address_list . ' address=$userstatic
        :log info  "Isolir $userstatic add to address list ' . $address_list . ' by Customer Management Script"
        }}';
    }

    public function script_auto_isolir_due_date_v640($address_list, $profileIsolir, $commentUnpayment, $dueDate)
    {
        return '
        /ip firewall address-list remove [/ip firewall address-list find list="' . $address_list . '"]
        :local day [:pick [/system clock get date] 4 6]
        :if (day >= "' . $dueDate . '") do={
        :local userppp
        :foreach v in=[/ppp secret find where comment="' . $commentUnpayment . '"] do={
        :set userppp ( userppp [/ppp secret get $v name])
        /ppp active remove [find name=$userppp]
        /ppp secret set profile=' . $profileIsolir . ' [find name=$userppp];
        :log info  "Isolir $userppp change profile to ' . $profileIsolir . ' by Customer Management Script"
        }

        :local userstatic
        foreach v in=[/queue simple find where comment="' . $commentUnpayment . '"] do={
        :set userstatic ( userstatic [/queue simple get $v target])
        /ip firewall address-list add list=' . $address_list . ' address=$userstatic
        :log info  "Isolir $userstatic add to address list ' . $address_list . ' by Customer Management Script"
        }
        }';
    }

    //Mikrotik V7.10+
    public function script_auto_isolir_activation_date_v710($address_list, $profileIsolir, $commentUnpayment)
    {
        return '
        :local date [/system clock get date]
        :local yyyy  [:pick $date 0 4]
        :local MM    [:pick $date 5 7]
        :local dd    [:pick $date 8 10]
        /ip firewall address-list remove [/ip firewall address-list find list="' . $address_list . '"]

        :for e from 1 to $dd do={
        :if ($e < 10) do={:set e "0$e"}
        :local duedate    "' . $commentUnpayment . '_$e_$MM_$yyyy"
        :local userppp
        :foreach v in=[/ppp/secret find comment="$duedate"] do={
        :set userppp ( userppp [/ppp secret get $v name])
        /ppp active remove [find name=$userppp]
        /ppp secret set profile=' . $profileIsolir . ' [find name=$userppp];
        :log info  "Isolir $userppp change profile to ' . $profileIsolir . ' by Customer Management Script"
        }

        :local userstatic
        foreach v in=[/queue/simple find comment="$duedate"] do={
        :set userstatic ( userstatic [/queue simple get $v target])
        /ip firewall address-list add list=' . $address_list . ' address=$userstatic
        :log info  "Isolir $userstatic add to address list ' . $address_list . ' by Customer Management Script"
        }}';
    }

    public function script_auto_isolir_due_date_v710($address_list, $profileIsolir, $commentUnpayment, $dueDate)
    {
        return '
        /ip firewall address-list remove [/ip firewall address-list find list="' . $address_list . '"]
        :local day [:pick [/system clock get date] 8 10]
        :if (day >= "' . $dueDate . '") do={
        :local userppp
        :foreach v in=[/ppp/secret find comment="' . $commentUnpayment . '"] do={
        :set userppp ( userppp [/ppp secret get $v name])
        /ppp active remove [find name=$userppp]
        /ppp secret set profile=' . $profileIsolir . ' [find name=$userppp];
        :log info  "Isolir $userppp change profile to ' . $profileIsolir . ' by Customer Management Script"
        }

        :local userstatic
        foreach v in=[/queue/simple find comment="' . $commentUnpayment . '"] do={
        :set userstatic ( userstatic [/queue simple get $v target])
        /ip firewall address-list add list=' . $address_list . ' address=$userstatic
        :log info  "Isolir $userstatic add to address list ' . $address_list . ' by Customer Management Script"
    }}';
    }

    //Mikrotik <V7.10
    public function add_auto_isolir_mikrotik_v640(AutoIsolir $autoIsolir, $commentUnpayment)
    {
        //ROS Versi < 7.10
        if ($autoIsolir->activation_date) {
            $sourceScript = $this->script_auto_isolir_activation_date_v640($autoIsolir->address_list_isolir, $autoIsolir->profile_id, $commentUnpayment);
            $comment = 'create_by_customer_management_mikrotik_<7.10_activation_date';
        } else {
            $sourceScript = $this->script_auto_isolir_due_date_v640($autoIsolir->address_list_isolir, $autoIsolir->profile_id, $commentUnpayment, $autoIsolir->due_date);
            $comment = 'create_by_customer_management_mikrotik_<7.10_due_date';
        }



        $client = $this->getMikrotik($autoIsolir->mikrotik);
        $query =
            (new Query('/system/script/add'))
            ->equal('name', $autoIsolir->name)
            ->equal('source', $sourceScript)
            ->equal('comment', $comment);

        return $client->query($query)->read();
    }

    //Mikrotik V7.10+
    public function add_auto_isolir_mikrotik_v710(AutoIsolir $autoIsolir, $commentUnpayment)
    {
        //ROS Versi 7.10+
        if ($autoIsolir->activation_date) {
            $sourceScript = $this->script_auto_isolir_activation_date_v710($autoIsolir->address_list_isolir, $autoIsolir->profile_id, $commentUnpayment);
            $comment = 'create_by_customer_management_mikrotik_>=7.10_activation_date';
        } else {
            $sourceScript = $this->script_auto_isolir_due_date_v710($autoIsolir->address_list_isolir, $autoIsolir->profile_id, $commentUnpayment, $autoIsolir->due_date);
            $comment = 'create_by_customer_management_mikrotik_>=7.10_due_date';
        }

        $client = $this->getMikrotik($autoIsolir->mikrotik);
        $query =
            (new Query('/system/script/add'))
            ->equal('name', $autoIsolir->name)
            ->equal('source', $sourceScript)
            ->equal('comment', $comment);

        return $client->query($query)->read();
    }


    /**
     * Remove script from mikrotik
     */
    public function removeScript($routerboard, $scriptId)
    {
        $client = $this->getMikrotik($routerboard);
        $scripts = new Query('/system/script/print');
        $scripts->where('.id', $scriptId);
        $resultScripts = $client->query($scripts)->read();
        foreach ($resultScripts as $script) {
            $client = $this->getMikrotik($routerboard);
            $query = (new Query('/system/script/remove'))
                ->where('.id', $scriptId)
                ->equal('.id', $script['.id']);
            return $client->query($query)->read();
        }
    }

    public function removeScriptByName($routerboard, $scriptName)
    {
        $client = $this->getMikrotik($routerboard);
        $scripts = new Query('/system/script/print');
        $scripts->where('name', $scriptName);
        $scripts = $client->query($scripts)->read();

        // dd($scripts);
        foreach ($scripts as $script) {
            $client = $this->getMikrotik($routerboard);
            $query = (new Query('/system/script/remove'))
                ->where('name', $scriptName)
                ->equal('.id', $script['.id']);
            return $client->query($query)->read();
        }
    }


    /**
     * Add schedule to mikrotik
     *
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function addScheduleEveryDay(AutoIsolir $autoisolir)
    {
        $onEventSchedule = '/system script run ' . $autoisolir->name . '';
        $client = $this->getMikrotik($autoisolir->mikrotik);

        $query =
            (new Query('/system/schedule/add'))
            ->equal('name', $autoisolir->name)
            ->equal('start-time', '01:00:00')
            ->equal('interval', '1d')
            ->equal('on-event', $onEventSchedule)
            ->equal('comment', 'create_by_customer_management');

        return $client->query($query)->read();
    }

    /**
     * Disable schedule on mikrotik
     *
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function disabledScheduleById($mikrotik, $scheduleId, $disabledStatus)
    {
        $client = $this->getMikrotik($mikrotik);
        $schedules = new Query('/system/schedule/print');
        $schedules->where('.id', $scheduleId);
        $resultSchedules = $client->query($schedules)->read();
        foreach ($resultSchedules as $schedule) {
            $client = $this->getMikrotik($mikrotik);
            $query = (new Query('/system/schedule/set'))
                ->where('.id', $scheduleId)
                ->equal('.id', $schedule['.id'])
                ->equal('disabled', $disabledStatus);

            return $client->query($query)->read();
        }
    }

    /**
     * Delete schedule on mikrotik
     *
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function removeSchedule($mikrotik, $scheduleId)
    {

        $client = $this->getMikrotik($mikrotik);
        $schedules = new Query('/system/schedule/print');
        $schedules->where('.id', $scheduleId);
        $resultSchedules = $client->query($schedules)->read();

        foreach ($resultSchedules as $schedule) {
            $client = $this->getMikrotik($mikrotik);
            $query = (new Query('/system/schedule/remove'))
                ->where('.id', $scheduleId)
                ->equal('.id', $schedule['.id']);

            return $client->query($query)->read();
        }
    }

    public function removeScheduleByName($mikrotik, $scheduleName)
    {

        $client = $this->getMikrotik($mikrotik);
        $schedules = new Query('/system/schedule/print');
        $schedules->where('name', $scheduleName);
        $resultSchedules = $client->query($schedules)->read();

        foreach ($resultSchedules as $schedule) {
            $client = $this->getMikrotik($mikrotik);
            $query = (new Query('/system/schedule/remove'))
                ->where('name', $scheduleName)
                ->equal('.id', $schedule['.id']);

            return $client->query($query)->read();
        }
    }

    public function list_tmon($mikrotik)
    {
        $client = $this->getMikrotik($mikrotik);

        $query = new Query('/tool/traffic-monitor/print');


        return $client->query($query)->read();
    }

    public function add_and_remove_tmon_script_mikrotik(MikrotikMonitoring $mikrotikMonitoring)
    {
        $trafficMonitorings = array(
            [
                'traffic' => 'transmitted',
                'trigger' => 'above',
                'threshold' => $mikrotikMonitoring->max_upload . 'M',
                'interface' => $mikrotikMonitoring->interface
            ],
            [
                'traffic' => 'transmitted',
                'trigger' => 'below',
                'threshold' => $mikrotikMonitoring->min_upload . 'M',
                'interface' => $mikrotikMonitoring->interface
            ],
            [
                'traffic' => 'received',
                'trigger' => 'above',
                'threshold' => $mikrotikMonitoring->max_download . 'M',
                'interface' => $mikrotikMonitoring->interface
            ],
            [
                'traffic' => 'received',
                'trigger' => 'below',
                'threshold' => $mikrotikMonitoring->min_download . 'M',
                'interface' => $mikrotikMonitoring->interface
            ],
        );

        $i = 0;
        foreach ($trafficMonitorings as $trafficMonitoring) {
            $i++;
            $this->remove_tmon($mikrotikMonitoring->mikrotik, $mikrotikMonitoring->slug . '_' . $i);
            $this->add_tmon($mikrotikMonitoring, $trafficMonitoring, $i);
        }
    }
    public function add_tmon($mikrotikMonitoring, $trafficMonitoring, $i)
    {
        $mikrotik = $mikrotikMonitoring->mikrotik;

        $client = $this->getMikrotik($mikrotik);
        $query =
            (new Query('/tool/traffic-monitor/add'))
            ->equal('name', $mikrotikMonitoring->slug . '_' . $i)
            ->equal('interface', $trafficMonitoring['interface'])
            ->equal('traffic', $trafficMonitoring['traffic'])
            ->equal('trigger', $trafficMonitoring['trigger'])
            ->equal('threshold', $trafficMonitoring['threshold'])
            ->equal('on-event', $this->script_tmon($mikrotikMonitoring))
            ->equal('disabled',  $mikrotikMonitoring->disabled ? 'true' : 'false');

        $client->query($query)->read();
    }

    public function remove_tmon($mikrotik, $name)
    {

        $client = $this->getMikrotik($mikrotik);
        $tmons = new Query('/tool/traffic-monitor/print');
        $tmons->where('name', $name);
        $tmons = $client->query($tmons)->read();
        //return dd($tmons);
        foreach ($tmons as $tmon) {
            $client = $this->getMikrotik($mikrotik);
            $query = (new Query('/tool/traffic-monitor/remove'))
                ->where('name', $name)
                ->equal('.id',  $tmon['.id']);

            return $client->query($query)->read();
        }
    }


    public function script_tmon(MikrotikMonitoring $mikrotikMonitoring)
    {
        $headerSecret = hash('sha256', env('API_CLIENT_MIKROTIK'));
        $app_url = env('APP_URL');
        return '
        :local interfaces {"' . $mikrotikMonitoring->interface . '"}

/interface monitor-traffic [/interface find name=$interfaces] once do={
	:local interfaceId [ /interface find where name=$interfaces ]
	:if ([:len $interfaceId] > 0) do={
		:local tx (tx-bits-per-second);
		:local rx (rx-bits-per-second);
		:put $tx;
		:put $rx;

		/tool fetch url="' . $app_url . '/mikrotik/webhook?monitoring=wan-monitoring&mikrotik_id=' . $mikrotikMonitoring->mikrotik->id . '&interface_name=$interfaces&rx_byte=$rx&tx_byte=$tx" http-method=post  keep-result=no http-header-field="X-Mikrotik-Signature:' . $headerSecret . '";

	}
}
        ';
    }
}
