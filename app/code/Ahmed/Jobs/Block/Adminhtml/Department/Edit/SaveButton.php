<?php

namespace Ahmed\Jobs\Block\Adminhtml\Department\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'jobs_department_form.jobs_department_form',
                                'actionName' => 'save',
                                'params' => [true],
                            ],
                        ],
                    ],
                ],
            ],
            'sort_order' => 90,
        ];
    }
}
