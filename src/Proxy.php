<?php

class Proxy
{

    public function getProxy(): string {
        $proxys = file('config/proxy.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $counter = count($proxys);
        $index = rand(0, $counter - 1);
        $proxy = $proxys[$index];
        return $proxy; // format <ip:port>
    }

}