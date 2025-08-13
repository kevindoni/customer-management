<?php

namespace App\Services\Mikrotiks;

use App\Models\PppProfile;
use App\Models\Websystem;
use RouterOS\Query;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Exceptions\QueryException;
use RouterOS\Exceptions\ClientException;
use RouterOS\Exceptions\ConfigException;
use RouterOS\Exceptions\ConnectException;
use RouterOS\Exceptions\BadCredentialsException;

class MikrotikIpStaticService
{
    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     * @throws \Exception
     */
    public function getMikrotik($mikrotik): Client
    {
        if (!$mikrotik) {
            throw new \Exception('Mikrotik not found.');
        }

        $config = (new Config())
            ->set('timeout', 2)
            ->set('host', $mikrotik->host)
            ->set('port', (int) $mikrotik->port)
            ->set('pass', $mikrotik->password)
            ->set('user', $mikrotik->username)
            ->set('ssl', (bool) $mikrotik->use_ssl);

        return new Client($config);
    }

    public function getSimpleques($mikrotik, $simplequeID)
    {
        $client = $this->getMikrotik($mikrotik);
        $simpleques = new Query('/queue/simple/print');
        $simpleques->where('.id', $simplequeID);
        return $client->query($simpleques)->read();
    }

    /**=========================================================================================
     * Create simpleque Ip Static Paket
     ===========================================================================================*/
    public function createSimpleQueue($mikrotik, $name, $target, $paketProfile, $comment)
    {
        $parent = $paketProfile->parent_queue ?? 'none';
        $queuetype = $paketProfile->queue_type ?? '';

        $valueLimitasi = $paketProfile->rate_limit;
        $limitasi = explode(' ', $valueLimitasi);
        $maxlimit = $limitasi[0];
        $priority = $limitasi[4] . '/' . $limitasi[4];
        $limitat = $limitasi[5];

        $client = $this->getMikrotik($mikrotik);
        $query = (new Query('/queue/simple/add'))
            ->equal('name', $name)
            ->equal('target', $target)
            ->equal('parent', $parent)
            ->equal('queue', $queuetype)
            ->equal('priority', $priority)
            ->equal('limit-at', $limitat)
            ->equal('max-limit', $maxlimit)
            ->equal('comment', $comment);

        return $client->query($query)->read();
    }

    public function updateSimpleQueue($mikrotik, $simplequeID, $paketProfile, $ipAddress)
    {
        $parent = $paketProfile->parent_queue ?? 'none';
        $queuetype = $paketProfile->queue_type ?? '';

        $valueLimitasi = $paketProfile->rate_limit;
        $limitasi = explode(' ', $valueLimitasi);
        $maxlimit = $limitasi[0];
        $priority = $limitasi[4] . '/' . $limitasi[4];
        $limitat = $limitasi[5];

        $client = $this->getMikrotik($mikrotik);
        $simpleques = $this->getSimpleques($mikrotik, $simplequeID);
        foreach ($simpleques as $simpleque) {
            $query = (new Query('/queue/simple/set'))
                ->equal('.id', $simpleque['.id'])
                ->equal('parent', $parent)
                ->equal('target', $ipAddress)
                ->equal('queue', $queuetype)
                ->equal('priority', $priority)
                ->equal('limit-at', $limitat)
                ->equal('max-limit', $maxlimit);

            return $client->query($query)->read();
        }
    }

    /**
     * Summary of updateCommentSimpleQueue
     * @param mixed $mikrotik
     * @param mixed $ipAddress
     * @param mixed $comment
     * Not found return null
     * Found return []
     */
    public function updateCommentSimpleQueue($mikrotik, $ipAddress, $comment)
    {
        $client = $this->getMikrotik($mikrotik);
        $simplequeues = new Query('/queue/simple/print');
        $simplequeues->where('target', $ipAddress . '/32');
        $simplequeues = $client->query($simplequeues)->read();
        foreach ($simplequeues as $simplequeue) {
            $client = $this->getMikrotik($mikrotik);
            $query = (new Query('/queue/simple/set'))
                ->where('target', $ipAddress)
                ->equal('.id', $simplequeue['.id'])
                ->equal('comment', $comment);

            return $client->query($query)->read();
        }
    }

    public function disableIpFromSimpleque($mikrotik, $simplequeID, $status)
    {
        $client = $this->getMikrotik($mikrotik);
        $simpleques = $this->getSimpleques($mikrotik, $simplequeID);
        foreach ($simpleques as $simpleque) {
            $disable = (new Query('/ip/arp/set'))
                ->equal('.id', $simpleque['.id'])
                ->equal('disabled', $status);
            return $client->query($disable)->read();
        }
    }

    public function deleteIpFromSimpleQueue($mikrotik, $simpleque_id)
    {

        $client = $this->getMikrotik($mikrotik);
        $simplequeues = new Query('/queue/simple/print');
        $simplequeues->where('.id', $simpleque_id);
        $simplequeues = $client->query($simplequeues)->read();
        foreach ($simplequeues as $simplequeue) {
            $client = $this->getMikrotik($mikrotik);
            $query = (new Query('/queue/simple/remove'))
                ->where('.id', $simpleque_id)
                ->equal('.id', $simplequeue['.id']);

            return $client->query($query)->read();
        }
    }

    public function deleteIpFromSimpleQueueByIp($mikrotik, $ipAddress)
    {
        // return dd($ipAddress);
        $client = $this->getMikrotik($mikrotik);
        $simplequeues = new Query('/queue/simple/print');
        //  $simplequeues = $client->query($simplequeues)->read();
        // return dd($simplequeues);
        $simplequeues->where('target', $ipAddress . '/32');
        $simplequeues = $client->query($simplequeues)->read();
        //return dd($simplequeues);
        foreach ($simplequeues as $simplequeue) {
            $client = $this->getMikrotik($mikrotik);
            $query = (new Query('/queue/simple/remove'))
                ->where('target', $ipAddress)
                ->equal('.id', $simplequeue['.id']);

            return $client->query($query)->read();
        }
    }
    /**=========================================================================================
     * Sdd Ip to Arp List
     *
     *
     ===========================================================================================*/

    public function getIpFromArp($mikrotik, $arpID)
    {
        $client = $this->getMikrotik($mikrotik);
        $arps = new Query('/ip/arp/print');
        $arps->where('.id', $arpID);
        return $client->query($arps)->read();
    }

    public function getIpFromArpByIp($mikrotik, $ipAddress)
    {
        $client = $this->getMikrotik($mikrotik);
        $arps = new Query('/ip/arp/print');
        $arps->where('address', $ipAddress);
        //return dd($client->query($arps)->read());
        return $client->query($arps)->read();
    }

    /**
     * Summary of updateCommentArp
     * @param mixed $mikrotik
     * @param mixed $ipAddress
     * @param mixed $comment
     * Not found return null
     * found return []
     */
    public function updateCommentArp($mikrotik, $ipAddress, $comment)
    {
        $client = $this->getMikrotik($mikrotik);
        $arps = $this->getIpFromArpByIp($mikrotik, $ipAddress);
        foreach ($arps as $arp) {
            //  $client = $this->getMikrotik($mikrotik);
            $query = (new Query('/ip/arp/set'))
                ->where('address', $ipAddress)
                ->equal('.id', $arp['.id'])
                ->equal('comment', $comment);

            return $client->query($query)->read();
        }
    }

    public function addIpToArp($mikrotik, $ip_address, $mac_address, $interface, $comment)
    // public function addIpToArp($mikrotik, $ip_address, $interface, $comment)
    {
        $client = $this->getMikrotik($mikrotik);
        $query = new Query('/ip/arp/add');
        $query->equal('address', $ip_address);
        $query->equal('mac-address', $mac_address);
        $query->equal('interface', $interface);
        $query->equal('comment', $comment);
        return ($client->query($query)->read());
    }

    public function disableIpFromArp($mikrotik, $arpID, $status)
    {
        $client = $this->getMikrotik($mikrotik);
        $arps = $this->getIpFromArp($mikrotik, $arpID);
        foreach ($arps as $arp) {
            $disable = (new Query('/ip/arp/set'))
                ->equal('.id', $arp['.id'])
                ->equal('disabled', $status);
            return $client->query($disable)->read();
        }
    }

    public function deleteIpFromArp($mikrotik, $arpID)
    {
        $client = $this->getMikrotik($mikrotik);
        $arps = $this->getIpFromArp($mikrotik, $arpID);
        foreach ($arps as $arp) {
            $query = (new Query('/ip/arp/remove'))
                ->equal('.id', $arp['.id']);
            return $client->query($query)->read();
        }
    }

    public function deleteIpFromArpByIp($mikrotik, $ipAddress)
    {
        $client = $this->getMikrotik($mikrotik);
        $arps = $this->getIpFromArpByIp($mikrotik, $ipAddress);
        foreach ($arps as $arp) {
            $query = (new Query('/ip/arp/remove'))
                ->where('address', $ipAddress)
                ->equal('.id', $arp['.id']);
            return $client->query($query)->read();
        }
    }

    public function deleteIpStaticPaket($mikrotik, $ip_address)
    {
        $arp = $this->deleteIpFromArpByIp($mikrotik, $ip_address);
        if (isset($arp['after']['message'])) {
            return $arp['after']['message'];
        }
        return $this->deleteIpFromSimpleQueueByIp($mikrotik, $ip_address);
    }

    /**
     * Address List
     */
    public function getAddressListByIpAddress($mikrotik, $ip_address)
    {
        $client = $this->getMikrotik($mikrotik);
        $address_lists = (new Query('/ip/firewall/address-list/print'))
            ->where('address', $ip_address);
        $address_list = $client->query($address_lists)->read();
        if ($address_list == []) {
            return null;
        } else {
            return $address_list;
        }
    }


    public function addIpToAddressList($mikrotik, $ip_address, $address_list, $comment = null)
    {
        $client = $this->getMikrotik($mikrotik);
        $query = new Query('/ip/firewall/address-list/add');
        $query->equal('list', $address_list);
        $query->equal('address', $ip_address);
        if (!is_null($comment)) $query->equal('comment', $comment);
        return ($client->query($query)->read());
    }

    public function deleteIpFromAddressList($mikrotik, $ip_address, $addresst_list_name)
    {
        $client = $this->getMikrotik($mikrotik);
        $query = new Query('/ip/firewall/address-list/print');
        $query->where('address', $ip_address);
        $query->where('list', $addresst_list_name);
        $results = $client->query($query)->read();
        foreach ($results as $result) {
            $query = (new Query('/ip/firewall/address-list/remove'))
                ->where('address', $ip_address)
                ->equal('.id', $result['.id']);
            return $client->query($query)->read();
        }
    }
}
