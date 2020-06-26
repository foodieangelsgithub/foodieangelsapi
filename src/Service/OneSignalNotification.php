<?php

namespace App\Service;

use App\Clients\OneSignalClient;


class OneSignalNotification
{

    /**
     * @var OneSignalClient $oneSignalClient
     */
    protected $oneSignalClient;


    public function __construct($oneSignalapplicationId, $restApiKey)
    {
        $this->oneSignalClient = new OneSignalClient($oneSignalapplicationId);
        $this->oneSignalClient->setRestApiKey($restApiKey);

    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(){

            return $this->oneSignalClient->oneSignalPlayerId->send();
    }

    public function setValues(array $array){
        if(array_key_exists('contents',$array)){
            $this->oneSignalClient->setContent($array['contents']);
        }else{
           return 'Debe tener contenido';
        }

        if(array_key_exists('playerIds',$array)) {
            $this->oneSignalClient->setPlayerIds($array['playerIds']);
        }

        if(array_key_exists('data',$array)) {
            $this->oneSignalClient->setData($array['data']);
        }

        if(array_key_exists('segments',$array)) {
            $this->oneSignalClient->setSegments($array['segments']);
        }
        return $this;
    }
}
