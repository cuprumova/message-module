<?php
/**
 * Copyright © 2021 Anastasiia Miednykh
 */
namespace Tudock\TodaysMessage\Model\Data;

use Magento\Framework\Api\AbstractSimpleObject;
use Tudock\TodaysMessage\Api\Data\MessageInterface;

/**
 * Class Message
 */
class Message extends AbstractSimpleObject implements MessageInterface
{
    /**
     * @inheridoc
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * @inheridoc
     */
    public function setId($value)
    {
        $this->setData(self::ID, $value);

        return $this;
    }

    /**
     * @inheridoc
     */
    public function getCategoryId()
    {
        return $this->_get(self::CATEGORY_ID);
    }

    /**
     * @inheridoc
     */
    public function setCategoryId(int $value)
    {
        $this->setData(self::CATEGORY_ID, $value);

        return $this;
    }

    /**
     * @inheridoc
     */
    public function getMessage()
    {
        return $this->_get(self::MESSAGE);
    }

    /**
     * @inheridoc
     */
    public function setMessage(string $value)
    {
        $this->setData(self::MESSAGE, $value);

        return $this;
    }

}
