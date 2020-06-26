<?php

namespace App\Service;

use Pushbots\PushbotsClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PushNotification
{


    private $body;
    private $title;
    private $plataform;
    private $recipientsId=array();
    private $recipientsTokens=array();
    private $language='es';
    private $topic;
    private $playload;


    public function __construct($applitationId, $applicationSecret)
    {
        $this->applitationId        = $applitationId;
        $this->applicationSecret    = $applicationSecret;
    }

    /**
     * @param $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getPlataform()
    {
        return $this->plataform;
    }


    /**
     * @param $plataform integer|array
     * @return $this
     * //Platform [Required]
     *   0 => iOS
     *   1 => Android
     *   2 => Chrome
     *   3 => Firefox
     *   4 => Opera
     *   5=> Safari
     */
    public function setPlataform($plataform)
    {
        $this->plataform = $plataform;
        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage(string $language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return array
     */
    public function getRecipientsId()
    {
        return $this->recipientsId;
    }

    /**
     * @param $recipientsId
     * @return $this|void
     */
    public function setRecipientsId($recipientsId)
    {
        if(is_array($recipientsId)) {
            $this->recipientsId = $recipientsId;
        }else{
            array_push($this->recipientsId, $recipientsId);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getRecipientsTokens()
    {
        return $this->recipientsTokens;
    }

    /**
     * @param $recipientsToken
     * @return $this|void
     */
    public function setRecipientsTokens($recipientsToken)
    {
        if(is_array($recipientsToken)) {
            $this->recipientsTokens = $recipientsToken;
        }else{
            array_push($this->recipientsTokens, $recipientsToken);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param mixed $topic
     * @return PushNotification
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlayload()
    {
        return $this->playload;
    }

    /**
     * @param mixed $playload
     * @return PushNotification
     */
    public function setPlayload($playload)
    {
        $this->playload = $playload;
        return $this;
    }



    public function enviarDatos()
    {
        $objeto = $this->dataSend();
        if($objeto){
            $client = new PushbotsClient($this->applitationId, $this->applicationSecret);
            $return = $client->transactional->send($objeto);
        }else{
            $return=false;
        }

        return $return;
    }


    /**
     * @return array|bool
     */
    private function dataSend(){

        if($this->getBody()=='' || $this->getTopic()=='' || $this->getPlataform()=='' || (count($this->getRecipientsTokens())==0 && (count($this->getRecipientsId())==0))){
            return false;
        }

        $array=[
            //topic [Required] of the transactional notification [can be any value, used only for tracking]
            "topic" => $this->getTopic(),
            "platform" => $this->getPlataform(),
            "recipients"=> array(),
            "message" => [
                "body" => $this->getBody()
            ]
        ];

        //"payload":{"sound":"siren.wav"}
        if($this->getPlayload()){
            $array["message"]["payload"]=$this->getPlayload();
        }
        //"payload":{"sound":"siren.wav"}
        if($this->getTitle()){
            $array["message"]["title"]=$this->getTitle();
        }

        if($this->getRecipientsId()){
            $array["recipients"]["ids"]=$this->getRecipientsId();
        }else if($this->getRecipientsTokens()){
            $array["recipients"]["tokens"]=$this->getRecipientsTokens();
        }

        return $array;
    }





    /**
     * @deprecated "esto es de prueba, no usar"
     */
    public function getData($id){

        $request = new \HttpRequest();
        $request->setUrl("https://api.pushbots.com/3/stats/delivery/{$id}");
        $request->setMethod(HTTP_METH_POST);

        $request->setHeaders(array(
            'x-pushbots-secret' => $this->applicationSecret,
            'x-pushbots-appid' => $this->applitationId,
            'Content-Type' => 'application/json'
        ));

        try {
            $response = $request->send();

            echo $response->getBody();
        } catch (\HttpException $ex) {
            echo $ex;
        }
        exit;
    }

}
