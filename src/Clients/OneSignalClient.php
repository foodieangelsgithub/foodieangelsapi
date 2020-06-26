<?php
/**
 * OneSignal
 *
 * OneSignal official PHP package 1.2
 *
 * @copyright 2018 Abdullah Diaa <abdullah@OneSignal.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      https://OneSignal.com
 */

namespace App\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Creates OneSignalClient using Application ID and secret
 *
 */
class OneSignalClient
{
    /**
     * @var Client $_client OneSignal Guzzle client
     */
    private $_client;

    /**
     * @var \GuzzleHttp\Client $http_client
     */
    protected $_http_client;


    /**
     *
     */
    protected $http_client;

    /**
     * @var string OneSignal application Id
     */
    protected $oneSignalapplicationId;

    /**
     * @var array OneSignal segment
     */
    protected $segments;

    /**
     * @var array OneSignal data
     */
    protected $data;

    /**
     * @var array OneSignal content
     */
    protected $content;

    /**
     * @var OneSignalPlayerIds $oneSignalPlayerId Sends OneSignalPlayerIds using OneSignal API
     */
    public $oneSignalPlayerId;

    /**
     * @var OneSignalSegment Send to a specific segment OneSignal API
     */
    public $oneSignalSegment;


    /**
     * @var array OneSignal include_player_ids
     */
    protected $playerIds;


    /**
     * @var string Authorization: YOUR_REST_API_KEY
     */
    protected $restApiKey;

    /**
     * var OneSignalTransactional $$transactional Sends a notification to single user using OneSignal API.

    public $transactional;
     * */

    /**
     * OneSignalClient constructor.
     *
     * @param string $oneSignalapplicationId App ID.
     */
    public function __construct($oneSignalapplicationId)
    {
        $this->_setClient();
        $this->oneSignalPlayerId = new OneSignalPlayerIds($this);
        $this->oneSignalSegment = new OneSignalSegment($this);

        $this->oneSignalapplicationId = $oneSignalapplicationId;
    }

    /**
     *  Sets guzzle Client
     *
     * @return void
     */
    private function _setClient()
    {
        $this->_http_client = new Client();
    }

    /**
     * Sets GuzzleHttp client.
     *
     * @param Client $client
     */
    public function setOneSignalClient($client)
    {
        $this->http_client = $client;
    }

    /**
     * Returns OneSignal application Id
     * @return string
     */
    public function getApplicationId()
    {
        return $this->oneSignalapplicationId;
    }

    /**
     * @return array
     */
    public function getSegments(): array
    {
        return $this->segments;
    }

    /**
     * @param array $segments
     * @return OneSignalClient
     */
    public function setSegments(array $segments): OneSignalClient
    {
        $this->segments[] = $segments;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return OneSignalClient
     */
    public function setData(array $data): OneSignalClient
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * @param array $content
     * @return OneSignalClient
     */
    public function setContent(array $content): OneSignalClient
    {
        foreach ($content as $key=>$val){
            $this->content[$key] = str_replace('\n',chr(10), $val);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getPlayerIds(): array
    {
        return $this->playerIds;
    }

    /**
     * @param array $playerIds
     * @return OneSignalClient
     */
    public function setPlayerIds(array $playerIds): OneSignalClient
    {
        $this->playerIds = $playerIds;
        return $this;
    }

    /**
     * @return string
     */
    public function getRestApiKey(): string
    {
        return $this->restApiKey;
    }

    /**
     * @param string $restApiKey
     * @return OneSignalClient
     */
    public function setRestApiKey(string $restApiKey): OneSignalClient
    {
        $this->restApiKey = "Basic {$restApiKey}";
        return $this;
    }





    /**
     * Sends request to OneSignal API.
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post($tipo)
    {

        $json=[
            'app_id'             => $this->getApplicationId(),
            'data'               => $this->getData(),
            'contents'           => $this->getContent(),
            'headins'=>[
                "es"=>"Título en español",
                "en"=>"Título in english"
            ]
        ];


        switch ($tipo){
            case 'segment':
                $json['included_segments'] = $this->getSegments();
                $json['headers']['Authorization']= $this->getRestApiKey();
                break;
            case 'playerid':
                $json['include_player_ids'] = $this->getPlayerIds();
                break;

        }

        $guzzleRequestOptions = [
            'json' => $json,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type'=> 'application/json; charset=utf-8'
            ],
        ];

        $response = $this->_http_client->request(
            'POST',
            "https://onesignal.com/api/v1/notifications",
            $guzzleRequestOptions
        );

        return $this->_handleResponse($response);
    }





    /**
     * Handling response through GuzzleHttp stream
     *
     * @param Response $response
     * @return mixed
     */
    private function _handleResponse(Response $response)
    {
        $stream = \GuzzleHttp\Psr7\stream_for($response->getBody());
        $data = json_decode($stream);
        return $data;
    }

}