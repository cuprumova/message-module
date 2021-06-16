<?php
/**
 * Copyright © 2021 Anastasiia Miednykh
 */
namespace Tudock\TodaysMessage\Model\ResourceModel\Message;

use Magento\Eav\Model\Entity\Attribute;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Psr\Log\LoggerInterface;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';
    /**
     * @var \Magento\Eav\Model\Entity\Attribute
     */
    private Attribute $eavAttribute;

    /**
     * @param EntityFactoryInterface $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     * @param \Magento\Eav\Model\Entity\Attribute $eavAttribute
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        \Magento\Eav\Model\Entity\Attribute $eavAttribute,
        AdapterInterface $connection = null,
        AbstractDb $resource = null
    ) {

        $this->eavAttribute = $eavAttribute;
        $this->_init(
            'Tudock\TodaysMessage\Model\Message',
            'Tudock\TodaysMessage\Model\ResourceModel\Message'
        );

        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);

    }

    protected function _initSelect()
    {
        parent::_initSelect();
        $attribute = $this->eavAttribute->loadByCode('catalog_category', 'name');
        $this->getSelect()
            ->joinLeft(
                ['catalog_category_varchar' => $this->getTable('catalog_category_entity_varchar')],
                'main_table.category_id = catalog_category_varchar.entity_id AND '
                . 'catalog_category_varchar.attribute_id = ' . $attribute->getId() . ' AND '
                . 'catalog_category_varchar.store_id = 0',
                ['category_name' => 'value']
            );

        return $this;

    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'Tudock\TodaysMessage\Model\Message',
            'Tudock\TodaysMessage\Model\ResourceModel\Message'
        );
    }
}
