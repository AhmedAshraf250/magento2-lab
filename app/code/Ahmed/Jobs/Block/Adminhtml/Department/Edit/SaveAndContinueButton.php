<?php

namespace Ahmed\Jobs\Block\Adminhtml\Department\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveAndContinueButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Save and Continue Edit'),
            'class' => 'save',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'jobs_department_form.jobs_department_form',
                                'actionName' => 'save',
                                'params' => [
                                    true,
                                    ['back' => 'edit'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'sort_order' => 80,
        ];
    }
}
