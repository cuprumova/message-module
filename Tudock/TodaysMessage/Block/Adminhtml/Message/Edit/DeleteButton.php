<?php
/**
 * Copyright © 2021 Anastasiia Miednykh
 */
namespace Tudock\TodaysMessage\Block\Adminhtml\Message\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton
 * @package Tudock\TodaysMessage\Block\Adminhtml\Message\Edit
 */
class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * Get button data.
     *
     * @return array
     */
    public function getButtonData()
    {
        $messageId = $this->getMessageId();
        $data = [];

        if ($messageId) {
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'id' => 'edit-delete-button',
                'data_attribute' => [
                    'url' => $this->getDeleteUrl()
                ],
                'on_click' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to do this?'
                    ) . '\', \''
                    . $this->urlBuilder->getUrl('*/*/delete', ['id' => $messageId]) . '\', {data: {}})',
                'sort_order' => 20,
                'aclResource' => 'Tudock_TodaysMessage::message',
            ];
        }
        return $data;
    }

    /**
     * Get delete url.
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['id' => $this->getMessageId()]);
    }
}
