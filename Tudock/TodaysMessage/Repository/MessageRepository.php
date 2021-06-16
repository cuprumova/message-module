<?php
/**
 * Copyright © 2021 Anastasiia Miednykh
 */
namespace Tudock\TodaysMessage\Repository;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Tudock\TodaysMessage\Api\MessageRepositoryInterface;
use Tudock\TodaysMessage\Api\Data\MessageInterface;
use Tudock\TodaysMessage\Api\Data\MessageInterfaceFactory;
use Tudock\TodaysMessage\Api\Data\MessageSearchResultsInterface;
use Tudock\TodaysMessage\Api\Data\MessageSearchResultsInterfaceFactory;
use Tudock\TodaysMessage\Model\MessageFactory;
use Tudock\TodaysMessage\Model\ResourceModel\Message;
use Tudock\TodaysMessage\Model\ResourceModel\Message\CollectionFactory;

class MessageRepository implements MessageRepositoryInterface
{
    /**
     * @var \Tudock\TodaysMessage\Model\MessageFactory
     */
    private $messageFactory;
    /**
     * @var \Tudock\TodaysMessage\Model\ResourceModel\Message
     */
    private $messageResourceModel;
    /**
     * @var \Magento\Framework\Api\SearchResultsInterface
     */
    private $searchResults;
    /**
     * @var \Tudock\TodaysMessage\Model\ResourceModel\Message\CollectionFactory
     */
    private $messageCollectionFactory;
    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;
    /**
     * @var \Tudock\TodaysMessage\Api\Data\MessageSearchResultsInterfaceFactory
     */
    private $searchResultFactory;
    /**
     * @var \Tudock\TodaysMessage\Api\Data\MessageInterfaceFactory
     */
    private $messageDataFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * MessageRepository constructor.
     *
     * @param \Tudock\TodaysMessage\Model\MessageFactory $messageFactory
     * @param \Tudock\TodaysMessage\Api\Data\MessageInterfaceFactory $messageDataFactory
     * @param \Tudock\TodaysMessage\Model\ResourceModel\Message $messageResourceModel
     * @param \Magento\Framework\Api\SearchResultsInterface $searchResults
     * @param \Tudock\TodaysMessage\Api\Data\MessageSearchResultsInterfaceFactory $searchResultFactory
     * @param \Tudock\TodaysMessage\Model\ResourceModel\Message\CollectionFactory $messageCollectionFactory
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        MessageFactory $messageFactory,
        MessageInterfaceFactory $messageDataFactory,
        Message $messageResourceModel,
        SearchResultsInterface $searchResults,
        MessageSearchResultsInterfaceFactory $searchResultFactory,
        CollectionFactory $messageCollectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->messageFactory = $messageFactory;
        $this->messageResourceModel = $messageResourceModel;
        $this->searchResults = $searchResults;
        $this->messageCollectionFactory = $messageCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultFactory = $searchResultFactory;
        $this->messageDataFactory = $messageDataFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function save(MessageInterface $messageData): MessageInterface
    {
        $message = $this->initializeMessageData($messageData->__toArray());
        $this->messageResourceModel->save($message);

        return $this->messageDataFactory->create()
            ->setId($message->getId())
            ->setMessage($message->getMessage())
            ->setCategoryId($message->getCategoryId());
    }

    /**
     * Merges data from DB and updates from request
     *
     * @param array $messageData
     *
     * @return \Tudock\TodaysMessage\Model\Message
     * @throws NoSuchEntityException
     */
    protected function initializeMessageData(array $messageData)
    {
        if (!empty($messageData['entityid'])) {
            $message = $this->getById($messageData['id']);
        } else {
            $message = $this->messageFactory->create();
        }

        foreach ($messageData as $key => $value) {
            $message->setData($key, $value);
        }

        return $message;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $id): MessageInterface
    {
        $message = $this->messageFactory->create();
        $message->load($id);

        $messageDataObject = $this->messageDataFactory->create()
            ->setId($message->getId())
            ->setMessage($message->getMessage())
            ->setCategoryId($message->getCategoryId());

        return $messageDataObject;
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): MessageSearchResultsInterface
    {
        /** @var \Tudock\TodaysMessage\Model\ResourceModel\Message\Collection $collection */
        $collection = $this->messageCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var  $searchResults */
        $searchResults = $this->searchResultFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        $messages = [];

        foreach ($collection as $message) {
            $messageDataObject = $this->messageDataFactory->create()
                ->setId($message->getEntityId())
                ->setMessage($message->getMessage())
                ->setCategoryId($message->getCategoryId());

            $messages[] = $messageDataObject;
        }

        $searchResults->setTotalCount($collection->getSize());

        return $searchResults->setItems($messages);
    }

    /**
     * @inheritdoc
     */
    public function deleteById(int $id): bool
    {
        $model = $this->messageFactory->create()->load($id);

        if (!$model->getId()) {
            throw new NoSuchEntityException();
        }

        $this->messageResourceModel->delete($model);

        return true;
    }
}
