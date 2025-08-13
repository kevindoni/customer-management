<?php

namespace App\Services\Acs;

use App\Models\Acs;

/**
 * Summary of PartialPaymentService
 */
class AcsService
{
    //  const URL = 'http://10.1.5.4:7557';

    /**
     * curl
     *
     * @param string $endpoint
     * @param string|array $query
     * @param string|array $body
     * @param string $type
     * @param string $mimeType
     * @param array $rawQuery
     * @return array
     */
    static function curl($endpoint = '/', $query = '', $body = '', $type = 'GET', $mimeType = '', $rawQuery = null)
    {
        $acs_server = Acs::first();
        if (!$acs_server->disabled) {
            $host = $acs_server->host;
            $port = (int)$acs_server->port;
            $URL = $host . ':' . $port;

            if (is_array($query)) {
                $query = json_encode($query);
            }
            $query = ($query ? '?query=' . urlencode($query) : '');
            if ($rawQuery) {
                $query = '?' . http_build_query($rawQuery);
            }

            $curl = curl_init();
            $curl_settings = array(
                CURLOPT_URL => $URL . $endpoint . $query,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $type,
            );

            if ($body) {
                $curl_settings[CURLOPT_POSTFIELDS] = is_array($body) ? json_encode($body) : $body;
            }

            if ($mimeType) {
                $curl_settings[CURLOPT_HTTPHEADER] = array(
                    'Content-Type: ' . $mimeType // application/json
                );
            }
            curl_setopt_array($curl, $curl_settings);
            $response = curl_exec($curl);

            curl_close($curl);
            return [
                'success' => true,
                'data' => json_decode($response, true)
            ];
            //return $response;
        } else {
            return [
                'success' => false,
                'message' => 'Acs disabled'
            ];
        }
    }

    /**
     * getAllDevices
     *
     * @return array|bool
     */
    public static function getAllDevices()
    {
        return self::curl('/devices');
    }

    /**
     * getDeviceById
     *
     * @param int|string $id
     * @return array|bool
     */
    public static function getDeviceById($id)
    {
        $query = ["_id" => $id];
        return  self::curl('/devices', json_encode($query));
    }

    /**
     * getDeviceBySerialNumber
     *
     * @param int|string $id
     * @return array|bool
     */
    public static function getDeviceBySerial($serial)
    {
        $query = ["_deviceId._SerialNumber" => $serial];
        return  self::curl('/devices', json_encode($query));
    }


    /**
     * getDevicesByQuery
     *
     * @param array $query
     * @return array|bool
     */
    public static function getDevicesByQuery($query)
    {
        return self::curl('/devices', json_encode($query));
    }

    /**
     * getDevicesByTags
     *
     * @param array $tags
     * @return array|bool
     */
    public static function getDevicesByTags($tags)
    {
        $query = ["_tags" => ['$all' => $tags]];
        return self::curl('/devices', json_encode($query));
    }

    /**
     * deleteDevice
     *
     * @param string $id
     * @return array|bool
     */
    public static function deleteDevice($id)
    {
        return self::curl('/devices/' . $id, '', '', 'DELETE');
    }

    /**
     * addTag
     *
     * @param string $deviceId
     * @param string $tag
     * @return array|bool
     */
    public static function addTag($deviceId, $tag)
    {
        return self::curl('/devices/' . $deviceId . '/tags/' . $tag, '', '', 'POST');
    }

    /**
     * removeTag
     *
     * @param string $deviceId
     * @param string $tag
     * @return array|bool
     */
    public static function removeTag($deviceId, $tag)
    {
        return self::curl('/devices/' . $deviceId . '/tags/' . $tag, '', '', 'DELETE');
    }

    /**
     * getFiles
     *
     * @return array|bool
     */
    public static function getFiles()
    {
        return self::curl('/files');
    }

    /**
     * uploadFile
     *
     * @param string $path
     * @param string $filename
     * @return array|bool
     */
    public static function uploadFile($path, $filename)
    {
        if (function_exists('curl_file_create')) {
            $body = curl_file_create($path);
        } else {
            $body = '@' . realpath($path);
        }

        return self::curl('/files/' . $filename, '', $body, 'PUT');
    }

    /**
     * deleteFile
     *
     * @param string $filename
     * @return array|bool
     */
    public static function deleteFile($filename)
    {
        return self::curl('/files/' . $filename, '', '', 'DELETE');
    }

    /**
     * faults
     *
     * @param string $deviceId
     * @return array|bool
     */
    public static function faults($deviceId)
    {
        // $deviceId
        return self::curl('/faults', '', '', 'GET');
    }

    /**
     * getParameterValues
     *
     * @param string $parameters
     * @param string $deviceId
     * @return array|bool
     */
    public static function getParameterValues($parameters, $deviceId)
    {
        $query = ['timeout' => '3000', 'connection_request'];
        $body = [
            "device" => $deviceId,
            "name" => "getParameterValues",
            "parameterNames" => is_array($parameters) ? $parameters : [$parameters]
        ];

        return self::curl('/devices/' . $deviceId . '/tasks', $query, $body, 'POST');
    }


    /**
     * setParameterValues
     *
     * @param array $parameters
     * @param string $deviceId
     * @return array|bool
     */
    public static function setParameterValues($parameters, $deviceId)
    {
        $query = ['timeout' => '3000', 'connection_request'];
        $body = [
            "device" => $deviceId,
            "name" => "setParameterValues",
            "parameterValues" => $parameters
        ];

        return self::curl('/devices/' . $deviceId . '/tasks', $query, $body, 'POST');
    }

    /**
     * refreshObject
     *
     * @param array $parameter
     * @param string $deviceId
     * @return array|bool
     */
    public static function refreshObject($parameter, $deviceId)
    {
        $query = ['timeout' => '3000', 'connection_request'];
        $body = [
            "device" => $deviceId,
            "name" => "refreshObject",
            "objectName" => $parameter
        ];

        return self::curl('/devices/' . $deviceId . '/tasks', $query, $body, 'POST');
    }

    /**
     * refreshAllObject
     *
     * @param string $deviceId
     * @return array|bool
     */
    public static function refreshAllObjects($deviceId)
    {
        $query = ['timeout' => '3000', 'connection_request'];
        $body = [
            "device" => $deviceId,
            "name" => "refreshObject",
            "objectName" => ''
        ];

        return self::curl('/devices/' . $deviceId . '/tasks', $query, $body, 'POST');
    }

    /**
     * reboot
     *
     * @param string $deviceId
     * @return array|bool
     */
    public static function reboot($deviceId)
    {
        $query = ['timeout' => '3000', 'connection_request'];
        $body = [
            "device" => $deviceId,
            "name" => "reboot"
        ];

        return self::curl('/devices/' . $deviceId . '/tasks', $query, $body, 'POST');
    }

    /**
     * factoryReset
     *
     * @param string $deviceId
     * @return array|bool
     */
    public static function factoryReset($deviceId)
    {
        $query = ['timeout' => '3000', 'connection_request'];
        $body = [
            "device" => $deviceId,
            "name" => "factoryReset"
        ];

        return self::curl('/devices/' . $deviceId . '/tasks', $query, $body, 'POST');
    }

    /**
     * pendingTasks
     *
     * @param string $deviceId
     * @return array|bool
     */
    public static function pendingTasks($deviceId)
    {
        $query = ["device" => $deviceId];

        return self::curl('/tasks', $query);
    }

    /**
     * getParameters
     *
     * @param array|string $parameters
     * @param string $deviceId
     * @return array|bool
     */
    public static function getParameters($parameters, $deviceId)
    {
        $parameters = is_array($parameters) ? $parameters : [$parameters];
        $query = [
            "query" => json_encode(["_id" => $deviceId]),
            'projection' => implode(',', $parameters)
        ];

        foreach ($parameters as $key => $val) {
            $parameters[$key] = explode('.', $val);
        }

        $response = self::curl('/devices', '', '', 'GET', '', $query);

        if ($response['success']) {
            $returnValues = [];
            foreach ($parameters as $parameter) {
                $keyname = '';
                $val = $response[0];
                foreach ($parameter as $key) {
                    $keyname .= '.' . $key;
                    $val = $val[$key];
                }
                $val = $val['_value'];
                $keyname = trim($keyname, '.');
                $returnValues[$keyname] = $val;
            }

            return [
                'success' => true,
                'data' => $returnValues
            ];
            //return $returnValues;
        }
        return $response;
    }

    public static function getTasks($deviceId)
    {
        $query = ['device' => $deviceId];
        return self::curl('/tasks', $query);
    }

    public static function dispatchAction($op, $deviceId)
    {
        switch ($op) {
            case 'reset':
                return Acs::factoryReset($deviceId);
            case 'reboot':
                return Acs::reboot($deviceId);
            case 'delete':
                return Acs::deleteDevice($deviceId);
            case 'refresh':
                return Acs::refreshAllObjects($deviceId);
        }
    }


    public static function deleteTask($deviceId, $taskId)
    {
        return self::curl('/tasks/' . $taskId, '', '', 'DELETE');
    }

    public static function getTask($taskId)
    {
        $query = ['_id' => $taskId];
        return self::curl('/tasks', $query);
    }
}
