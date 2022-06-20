<?php

declare(strict_types=1);

namespace Test\Weather\Block\Search;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Serialize\Serializer\JsonHexTag;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\UrlInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Weather
 *
 * @package Test\Weather\Block\Search
 */
class Weather extends Template
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var UrlInterface
     */
    protected $_urlInterface;

    /**
     * Weather constructor.
     *
     * @param Context $context
     * @param UrlInterface $urlInterface
     * @param SerializerInterface null $serializerInterface
     */
    public function __construct(
        Context $context,
        UrlInterface $urlInterface,
        SerializerInterface $serializerInterface = null
    )
    {
        $this->_urlInterface = $urlInterface;
        $this->serializer = $serializerInterface ?: ObjectManager::getInstance()->get(JsonHexTag::class);
        parent::__construct($context);
    }

    /**
     * @return Json
     */
    public function getSerializedConfig()
    {
        $configs = [
            'url' => $this->_urlInterface->getUrl('weather/search/ajax'),
        ];

        return $this->serializer->serialize($configs);
    }
}
