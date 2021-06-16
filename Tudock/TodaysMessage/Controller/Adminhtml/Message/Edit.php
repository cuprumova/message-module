<?php
/**
 * Copyright © 2021 Anastasiia Miednykh
 */
namespace Tudock\TodaysMessage\Controller\Adminhtml\Message;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Registry;
use Tudock\TodaysMessage\Repository\MessageRepository;

/**
 * Class Edit
 * @package Tudock\TodaysMessage\Controller\Adminhtml\Message
 */
class Edit extends Action
{
    /**
     * @var \Tudock\TodaysMessage\Repository\MessageRepository
     */
    private $messageRepository;
    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * Edit constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Tudock\TodaysMessage\Repository\MessageRepository $messageRepository
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(Action\Context $context,
                                MessageRepository $messageRepository,
                                Registry $coreRegistry)
    {
        parent::__construct($context);
        $this->messageRepository = $messageRepository;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if (!empty($id)) {
            $message = $this->messageRepository->getById($id);
            $this->coreRegistry->register('message', $message);
        }

        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        return $resultPage;
    }
}
