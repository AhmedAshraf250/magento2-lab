<?php

namespace Ahmed\Jobs\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    public const XML_PATH_DEPARTMENT_VIEW_LIST = 'ahmed_jobs/department/view_list'; // [\vendor\module\etc\adminhtml\system.xml] || section/group/field || 
    public const XML_PATH_CRON_SCHEDULE = 'ahmed_jobs/cron/schedule';

    public function canShowDepartmentJobList($store = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_DEPARTMENT_VIEW_LIST,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getCronSchedule(): ?string
    {
        $schedule = $this->scopeConfig->getValue(
            self::XML_PATH_CRON_SCHEDULE,
            ScopeInterface::SCOPE_STORE
        );

        return $schedule !== null ? (string) $schedule : null;
    }
}
