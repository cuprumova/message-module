<?php
/**
 * Copyright © 2021 Anastasiia Miednykh
 */
namespace Tudock\TodaysMessage\Controller\Adminhtml\Message;

use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Tudock\TodaysMessage\Model\Message;
use Tudock\TodaysMessage\Repository\MessageRepository;

/**
 * Class MassDelete
 *
 * @package Tudock\TodaysMessage\Controller\Adminhtml\Message
 */
class MassDelete extends Action implements HttpPostActionInterface
{
    /**
     * @var \Tudock\TodaysMessage\Repository\MessageRepository
     */
    private $messageRepository;
    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    private $filterBuilder;

    /**
     * Delete constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Tudock\TodaysMessage\Repository\MessageRepository $messageRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        Action\Context $context,
        MessageRepository $messageRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->messageRepository = $messageRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->logger = $logger;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * Delete message action
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $messageIds = $this->getRequest()->getParam('selected');

        if (is_array($messageIds)) {
            $this->searchCriteriaBuilder->addFilters(
                [
                    $this->filterBuilder
                        ->setField('main_table.entity_id')
                        ->setConditionType('in')
                        ->setValue($messageIds)
                        ->create(),
                ]
            );

            $this->searchCriteriaBuilder->setPageSize(100);
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $result = $this->messageRepository->getList($searchCriteria);

            foreach ($result->getItems() as $message) {
                try {
                    $this->messageRepository->deleteById($message->getId());

                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                    $this->logger->error($e->getMessage());
                } catch (Exception $e) {
                    $this->messageManager->addErrorMessage(
                        __('We can\'t delete the message right now. Please review the log and try again.')
                    );
                    $this->logger->error($e->getMessage());
                    $this->_redirect(
                        'tudock_todaysmessage/message/'
                    );
                }
            }
        }

        $this->messageManager->addSuccessMessage(__('You deleted the message(-s).'));
        $this->_redirect('tudock_todaysmessage/message/');
    }
}
