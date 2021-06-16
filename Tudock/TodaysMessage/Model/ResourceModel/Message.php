<?php
/**
 * Copyright © 2021 Anastasiia Miednykh
 */
namespace Tudock\TodaysMessage\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Message extends AbstractDb
{
    /**
     * Define main table
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('tudock_todaysmessage_message', 'entity_id');
    }
}
