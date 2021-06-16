<?php
/**
 * Copyright © 2021 Anastasiia Miednykh
 */
namespace Tudock\TodaysMessage\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Tudock\TodaysMessage\Api\Data\MessageInterface;
use Tudock\TodaysMessage\Api\Data\MessageSearchResultsInterface;

/**
 * Interface MessageRepositoryInterface
 *
 * @api
 */
interface MessageRepositoryInterface
{
    /**
     * Create message
     *
     * @param \Tudock\TodaysMessage\Api\Data\MessageInterface $messageData
     * @return \Tudock\TodaysMessage\Api\Data\MessageInterface
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function save(MessageInterface $messageData): MessageInterface;

    /**
     * @param int $id
     * @return \Tudock\TodaysMessage\Api\Data\MessageInterface
     */
    public function getById(int $id): MessageInterface;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \Tudock\TodaysMessage\Api\Data\MessageSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): MessageSearchResultsInterface;

    /**
     * Delete message by ID.
     *
     * @param int $id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $id): bool;
}
