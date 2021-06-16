<?php
/**
 * Copyright © 2021 Anastasiia Miednykh
 */
namespace Tudock\TodaysMessage\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface MessageSearchResultsInterface
 *
 * @api
 */
interface MessageSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get messages list.
     *
     * @return \Tudock\TodaysMessage\Api\Data\MessageInterface[]
     */
    public function getItems();

    /**
     * Set messages list.
     *
     * @api
     * @param \Tudock\TodaysMessage\Api\Data\MessageInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
