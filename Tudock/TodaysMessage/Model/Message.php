<?php
/**
 * Copyright © 2021 Anastasiia Miednykh
 */
namespace Tudock\TodaysMessage\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;

class Message
    extends AbstractModel
    implements IdentityInterface
{
    const CACHE_TAG = 'tudock_todaysmessage_message';

    /**
     * Message constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param \Magento\Framework\Stdlib\DateTime\DateTimeFactory $dateFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        DateTimeFactory $dateFactory,
        array $data = [])
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->dateFactory = $dateFactory;
    }

    /**
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('Tudock\TodaysMessage\Model\ResourceModel\Message');
    }

}
