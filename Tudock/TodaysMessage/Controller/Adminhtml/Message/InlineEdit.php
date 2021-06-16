<?php
/**
 * Copyright © 2021 Anastasiia Miednykh
 */

namespace Tudock\TodaysMessage\Controller\Adminhtml\Message;

use Tudock\TodaysMessage\Repository\MessageRepository;

class InlineEdit extends \Magento\Backend\App\Action
{
    protected $jsonFactory;
    /**
     * @var \Tudock\TodaysMessage\Repository\MessageRepository
     */
    private MessageRepository $messageRepository;

    /**
     * InlineEdit constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     * @param \Tudock\TodaysMessage\Repository\MessageRepository $messageRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        MessageRepository $messageRepository
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->messageRepository = $messageRepository;
    }

    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $entityId) {
                    /** load your model to update the data */

                    $message = $this->messageRepository->getById((int)$entityId);

                    try {
                        $message->setCategoryId($message->getCategoryId());
                        $message->setMessage(
                            !empty($postItems[$entityId]['message'])
                                ? $postItems[$entityId]['message']
                                : $message->getMessage()
                        );
                        $this->messageRepository->save($message);
                    } catch (\Exception $e) {
                        $messages[] = "[Error:]  {$e->getMessage()}";
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData(
            [
                'messages' => $messages,
                'error' => $error,
            ]
        );
    }
}
