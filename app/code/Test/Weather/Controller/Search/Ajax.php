<?php

declare(strict_types=1);

namespace Test\Weather\Controller\Search;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Test\Weather\Helper\Data;

/**
 * Class Ajax
 *
 * @package Test\Weather\Controller\Search
 */
class Ajax extends Action
{
    /**
     * @var Context
     */
    protected $context;
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * Ajax constructor.
     *
     * @param Context $context
     * @param Data    $helperData
     */
    public function __construct(Context $context, Data $helperData)
    {
        $this->helperData = $helperData;
        $this->context    = $context;

        return parent::__construct($context);
    }

    /**
     * @return void
     */
    public function execute()
    {
        $city        = $this->getRequest()->getParam('city');
        $dataWeather = $this->helperData->getWeatherByCity($city);

        if ($dataWeather['status'] == 200) {
            $weather       = json_decode($dataWeather['weather']);
            $searchWeather = [
                "now"          => [
                    "name"        => $city,
                    "temperature" => $weather->forecast->forecastday[0]->hour[0]->temp_c,
                    "condition"   => $weather->forecast->forecastday[0]->day->condition->text,
                    "wind_kph"    => $weather->forecast->forecastday[0]->hour[0]->wind_kph,
                    "wind_dir"    => $weather->forecast->forecastday[0]->hour[0]->wind_dir,
                ],
                "tomorrow"     => [
                    "temperature" => $weather->forecast->forecastday[1]->hour[0]->temp_c,
                    "condition"   => $weather->forecast->forecastday[1]->day->condition->text,
                    "wind_kph"    => $weather->forecast->forecastday[1]->hour[0]->wind_kph,
                    "wind_dir"    => $weather->forecast->forecastday[1]->hour[0]->wind_dir,
                ],
                "nextTomorrow" => [
                    "temperature" => $weather->forecast->forecastday[2]->hour[0]->temp_c,
                    "condition"   => $weather->forecast->forecastday[2]->day->condition->text,
                    "wind_kph"    => $weather->forecast->forecastday[2]->hour[0]->wind_kph,
                    "wind_dir"    => $weather->forecast->forecastday[2]->hour[0]->wind_dir,
                ],
            ];

            echo json_encode($searchWeather);
        } else {
            echo $dataWeather['status'];
        }

        exit();
    }
}
