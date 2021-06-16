<?php
/**
 * Copyright © 2021 Anastasiia Miednykh
 */

namespace Tudock\TodaysMessage\Controller\Adminhtml\Message;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Tudock\TodaysMessage\Api\Data\MessageInterfaceFactory;
use Tudock\TodaysMessage\Model\MessageUploader;
use Tudock\TodaysMessage\Repository\MessageRepository;

/**
 * Class Save
 *
 * @package Tudock\TodaysMessage\Controller\Adminhtml\Message
 */
class Save extends Action
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
     * @var \Tudock\TodaysMessage\Api\Data\MessageInterfaceFactory
     */
    private MessageInterfaceFactory $messageDataFactory;


    /**
     * Save constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Tudock\TodaysMessage\Repository\MessageRepository $messageRepository
     * @param \Tudock\TodaysMessage\Api\Data\MessageInterfaceFactory $messageDataFactory
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        MessageRepository $messageRepository,
        MessageInterfaceFactory $messageDataFactory,
        LoggerInterface $logger
    ) {
        parent::__construct($context);

        $this->messageRepository = $messageRepository;
        $this->logger = $logger;
        $this->messageDataFactory = $messageDataFactory;
    }

    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            try {
                $data = $this->getRequest()->getPostValue();

                $id = $this->getRequest()->getParam('entity_id');
                if ($id !== null) {
                    $message = $this->messageRepository->getById((int)$id);
                } else {
                    $message = $this->messageDataFactory->create();
                }

                $message->setMessage($data['message']);
                $message->setCategoryId($data['category_id']);
                //@TODO validation category id
                $message = $this->messageRepository->save($message);

                $this->messageManager->addSuccessMessage(__('You saved the message.'));

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $message->getId()]);

                    return;
                }
                $this->_redirect('*/*/');

                return;
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $id = (int)$this->getRequest()->getParam('id');
                $this->_session->setPageData($data);
                $this->_redirect('*/*/edit', (!empty($id) ? ['id' => $id] : []));

                return;
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('Something went wrong while saving the message data. Please review the error log.')
                );
                $this->logger->critical($e);
                $this->_session->setPageData($data);
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);

                return;
            }
        }
        $this->_redirect('*/*/');
    }

}
