<?php

namespace Ahmed\Jobs\Block\Adminhtml\Department\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData(): array
    {
        $departmentId = $this->getDepartmentId();
        if (!$departmentId) {
            return [];
        }

        $confirmMessage = $this->context->getEscaper()->escapeJs(
            $this->context->getEscaper()->escapeHtml(__('Are you sure you want to delete this department?'))
        );

        return [
            'label' => __('Delete'),
            'class' => 'delete',
            'on_click' => 'deleteConfirm(\'' . $confirmMessage . '\', \''
                . $this->getUrl('*/*/delete', ['id' => $departmentId]) . '\', {"data": {}})',
            'sort_order' => 20,
        ];
    }
}
