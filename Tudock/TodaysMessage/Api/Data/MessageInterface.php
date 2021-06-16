<?php
/**
 * Copyright © 2021 Anastasiia Miednykh
 */
namespace Tudock\TodaysMessage\Api\Data;

/**
 * Interface MessageInterface
 *
 * @api
 */
interface MessageInterface
{
    /**#@+
     * Constants for keys of data array
     */
    const ID = 'entity_id';
    const MESSAGE = 'message';
    const CATEGORY_ID = 'category_id';

    /**#@-*/

    /**
     * Identifier getter
     *
     * @return int
     */
    public function getId();

    /**
     * Set entity Id
     *
     * @param int $value
     * @return $this
     */
    public function setId($value);
    /**
     * Get message
     *
     * @return string|null
     */
    public function getMessage();

    /**
     * Set message
     *
     * @param string $value
     *
     * @return void
     */
    public function setMessage(string $value);

    /**
     * Get category id
     *
     * @return int|null
     */
    public function getCategoryId();

    /**
     * Set category id
     *
     * @param int $value
     *
     * @return void
     */
    public function setCategoryId(int $value);
}
