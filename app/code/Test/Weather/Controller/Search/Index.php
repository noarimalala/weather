<?php

declare(strict_types=1);

namespace Test\Weather\Controller\Search;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 *
 * @package Test\Weather\Controller\Search
 */
class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * Weather constructor.
     *
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(Context $context, PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;

        return parent::__construct($context);
    }

    /**
     * @return ResultInterface|Page
     */
    public function execute()
    {
        return $this->pageFactory->create();
    }
}
