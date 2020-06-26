<?php

namespace App\Clients;


class OneSignalSegment
{
    /**
     * @var OneSignalSegment
     */
    private $_client;

    /**
     * OneSignalSegment constructor.
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
        return $this->_client->post('segment');
    }
}
