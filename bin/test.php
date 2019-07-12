<?php

$client = new Redis();
$client->connect('redis');
//$client->setOption(Redis::OPT_PREFIX, $key . ':');
//$client->set('prova', 'ciao');

var_dump($client->hGetAll('jobboy-processes'));