<?php

namespace Ahmed\Jobs\Block\Adminhtml\Department\Edit;

use Magento\Backend\Block\Widget\Context;

class GenericButton
{
    public function __construct(
        protected Context $context
    ) {
    }

    /**
     * Return current department ID from request.
     *
     * @return int|null
     */
    public function getDepartmentId(): ?int
    {
        $departmentId = (int) $this->context->getRequest()->getParam('id');
        return $departmentId > 0 ? $departmentId : null;
    }

    /**
     * Generate backend URL.
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
