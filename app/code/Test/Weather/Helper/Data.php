<?php

declare(strict_types=1);

namespace Test\Weather\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use GuzzleHttp\Client;
use Magento\Framework\App\Helper\Context;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ResponseFactory;

/**
 * Class Data
 *
 * @package Test\Weather\Helper
 */
class Data extends AbstractHelper
{
    /**
     * @const string
     */
    private const XML_PATH_WEATHER_API = 'search_weather/';

    /**
     * @const string
     */
    private const URI_WEATHER_API = 'http://api.weatherapi.com/';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * Data constructor.
     *
     * @param Context         $context
     * @param Client          $client
     * @param ResponseFactory $responseFactory
     */
    public function __construct(
        Context $context,
        Client $client,
        ResponseFactory $responseFactory
    )
    {
        $this->client          = $client;
        $this->responseFactory = $responseFactory;

        parent::__construct($context);
    }

    /**
     * @param      $field
     * @param null $storeId
     *
     * @return mixed
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue($field, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param      $code
     * @param null $storeId
     *
     * @return mixed
     */
    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_WEATHER_API . 'general/' . $code, $storeId);
    }

    /**
     * @param $city
     *
     * @return array
     */
    public function getWeatherByCity($city): array
    {
        $apiKey      = $this->getGeneralConfig('weather_api_key');
        $currentHour = date('H');
        $apiUri      = self::URI_WEATHER_API . 'v1/forecast.json?key=' . $apiKey . '&q=' . $city . '&aqi=no&lang=fr&hour=' . $currentHour . '&days=4';

        try {
            $response = $this->client->request(
                'GET',
                $apiUri,
                [
                    'headers' => [
                        'Accept' => 'text/plain',
                    ],
                ]
            );
        } catch (GuzzleException $exception) {
            /** @var Response $response */
            return $this->responseFactory->create(
                [
                    'status' => $exception->getCode(),
                    'reason' => $exception->getMessage(),
                ]
            );
        }

        $status          = $response->getStatusCode();
        $responseBody    = $response->getBody();
        $responseContent = $responseBody->getContents();

        return [
            "status"  => $status,
            "weather" => $responseContent,
        ];
    }
}
