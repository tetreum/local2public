<?php

/**********
** LOOKS FOR IP CHANGES AND UPDATES CLOUDFLARE DNS RECORD
** EXECUTE EVERY 5-10 MINUTES
***********/

require_once('vendor/autoload.php');

$config = require_once(__DIR__ . '/config.php');
$jsonPath = __DIR__ . '/ip.json';

if (!file_exists($jsonPath)) {
    $success = file_put_contents($jsonPath, '{"ip":null}');

    if ($success === false) {
        throw new \Exception("Could not create the required json file: " . $jsonPath);
    }
}

$lastIp = json_decode(file_get_contents($jsonPath));
$currentIp = json_decode(file_get_contents('https://api.ipify.org?format=json'));


if ($currentIp->ip == $lastIp->ip) {
    exit;
}

// ip changed, time to notify cloudflare

$key = new \Cloudflare\API\Auth\APIKey($config['cloudflare']['email'], $config['cloudflare']['api_key']);
$adapter = new Cloudflare\API\Adapter\Guzzle($key);
$zones = new \Cloudflare\API\Endpoints\Zones($adapter);

$zoneID = $zones->getZoneID($config['cloudflare']['domain']);

$dns = new \Cloudflare\API\Endpoints\DNS($adapter);

// stupid cloudflare that doesnt have getRecordID (at the time of writting this code) https://github.com/cloudflare/cloudflare-php/pull/53
$records = $dns->listRecords($zoneID, 'A', $config['cloudflare']['subdomain'] . '.' . $config['cloudflare']['domain']);

if ($dns->updateRecordDetails($zoneID, $records->result[0]->id, [
     'type' => $records->result[0]->type,
    'name' => $records->result[0]->name,
    'content' => $currentIp->ip,
    'ttl' => $records->result[0]->ttl,
    'proxied' => $records->result[0]->proxied
]) === true) {
    echo "DNS record updated.". PHP_EOL;
}

$lastIp->ip = $currentIp->ip;
file_put_contents($jsonPath, json_encode($lastIp));



