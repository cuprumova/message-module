<?php
/**
 * Copyright © 2021 Anastasiia Miednykh
 */
namespace Tudock\TodaysMessage\Ui\DataProvider;

use Magento\Backend\Model\Session;
use Magento\Framework\Registry;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Tudock\TodaysMessage\Model\MessageFactory;
use Tudock\TodaysMessage\Model\ResourceModel\Message\CollectionFactory;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

/**
 * Class MessageDataProvider
 */
class MessageDataProvider extends AbstractDataProvider
{
    /**
     * @var \Magento\Ui\DataProvider\Modifier\PoolInterface
     */
    private $pool;
    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;
    /**
     * @var \Tudock\TodaysMessage\Model\MessageFactory
     */
    private $messageFactory;
    /**
     * @var \Magento\Backend\Model\Session
     */
    private Session $session;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $messageCollectionFactory
     * @param \Magento\Ui\DataProvider\Modifier\PoolInterface $pool
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Tudock\TodaysMessage\Model\MessageFactory $messageFactory
     * @param \Magento\Backend\Model\Session $session
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $messageCollectionFactory,
        PoolInterface $pool,
        Registry $coreRegistry,
        MessageFactory $messageFactory,
        Session $session,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $messageCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->pool = $pool;
        $this->coreRegistry = $coreRegistry;
        $this->messageFactory = $messageFactory;
        $this->session = $session;
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $messageData = $this->coreRegistry->registry('message');
        if (!is_null($messageData) && $messageData->getId()) {
            $message = $this->messageFactory->create();
            $message->load($messageData->getId());
            $this->_loadedData[$message->getId()] = $message->getData();

            return $this->_loadedData;
        } elseif ($this->session->getPageData()) {
            $this->_loadedData[null] = $this->session->getPageData();
            $this->session->setPageData(false);

            return $this->_loadedData;
        }

        return [];
    }

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function getMeta()
    {
        $meta = parent::getMeta();

        /** @var ModifierInterface $modifier */
        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }

        return $meta;
    }
}
