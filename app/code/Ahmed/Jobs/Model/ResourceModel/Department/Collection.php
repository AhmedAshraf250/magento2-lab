<?php

namespace Ahmed\Jobs\Model\ResourceModel\Department;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = \Ahmed\Jobs\Model\Department::DEPARTMENT_ID;
    protected $_eventPrefix = 'ahmed_jobs_department_collection';
    protected $_eventObject = 'department_collection';

    /**
     * Define the resource model & the model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ahmed\Jobs\Model\Department', 'Ahmed\Jobs\Model\ResourceModel\Department');
    }
}
