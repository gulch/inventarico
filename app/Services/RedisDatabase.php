<?php namespace App\Services;

use Illuminate\Redis\Database;
use Illuminate\Contracts\Redis\Database as DatabaseContract;

class RedisDatabase extends Database implements DatabaseContract
{
    protected function createSingleClients(array $servers, array $options = [])
    {
        $clients = array();
        $servers = array_except($servers, array('cluster'));
        foreach ($servers as $key => $server) {
            $redis = new \Redis();
            if (isset($server['scheme']) && $server['scheme'] == 'unix') {
                $redis->connect($server['path']);
            } else {
                $redis->connect($server['host'], $server['port']);
            }

            if (isset($server['password'])) {
                $redis->auth($server['password']);
            }
            $redis->select($server['database']);
            $clients[$key] = $redis;
        }

        return $clients;
    }
}
