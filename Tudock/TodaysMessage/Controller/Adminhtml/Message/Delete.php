<?php
/**
 * Copyright © 2021 Anastasiia Miednykh
 */
namespace Tudock\TodaysMessage\Controller\Adminhtml\Message;

use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Tudock\TodaysMessage\Repository\MessageRepository;

/**
 * Class Delete
 *
 * @package Tudock\TodaysMessage\Controller\Adminhtml\Message
 */
class Delete extends Action
{
    /**
     * @var \Tudock\TodaysMessage\Repository\MessageRepository
     */
    private $messageRepository;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Delete constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Tudock\TodaysMessage\Repository\MessageRepository $messageRepository
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        Action\Context $context,
        MessageRepository $messageRepository,
        LoggerInterface $logger
    ) {
        parent::__construct($context);

        $this->messageRepository = $messageRepository;
        $this->logger = $logger;
    }

    /**
     * Delete message action
     *
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if (!empty($id)) {
            try {
                $this->messageRepository->deleteById($id);

                $this->messageManager->addSuccessMessage(__('You deleted the message.'));
                $this->_redirect('tudock_todaysmessage/message/');

                return;
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->logger->error($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('We can\'t delete the message right now. Please review the log and try again.')
                );
                $this->logger->error($e->getMessage());
                $this->_redirect(
                    'tudock_todaysmessage/message/edit',
                    ['id' => $this->getRequest()->getParam('id')]
                );

                return;
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a message to delete.'));
        $this->_redirect('tudock_todaysmessage/message/');
    }
}
