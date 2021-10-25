<?php

class Proxys
{

    public function getProxy(): string {
        $file = file_get_contents('config/proxy.txt');
        $proxys = json_decode($file, true);
        $ip = array_rand($proxys);
        $port = $proxys[$ip];
        return $ip . ":" . $port; // format <ip:port>
    }

}