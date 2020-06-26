<?php

namespace App\Clients;

class OneSignalPlayerIds
{
    /**
     * @var OneSignalPlayerIds
     */
    private $_client;

    /**
     * OneSignalCampaign constructor.
     *
     * @param OneSignalClient $client
     */
    public function __construct($client)
    {
        $this->_client = $client;
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send()
    {
        return $this->_client->post('playerid');
    }
}
