<?php

use Illuminate\Support\Facades\Http;

if (! function_exists('setEnv')) {
    function setEnv(string $key, string $value)
    {
        $env = array_reduce(
            file(base_path('.env'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES),
            function ($carry, $item) {
                list($key, $val) = explode('=', $item, 2);
                $carry[$key] = $val;
                return $carry;
            },
            []
        );
        $env[$key] = $value;
        foreach ($env as $k => &$v) {
            $v = "{$k}={$v}";
        }
        file_put_contents(base_path('.env'), implode("\r\n", $env));
    }
}

if (! function_exists('str_extract')) {
    function str_extract($str, $pattern, $get = null, $default = null)
    {
        $result = [];
        preg_match($pattern, $str, $matches);
        preg_match_all('/(\(\?P\<(?P<name>.+)\>\.\+\)+)/U', $pattern, $captures);
        $names = $captures['name'] ?? [];
        foreach ($names as $name) {
            $result[$name] = $matches[$name] ?? null;
        }
        return $get ? $result[$get] ?? $default : $result;
    }
}
