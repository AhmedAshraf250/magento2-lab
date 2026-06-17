<?php

namespace Ahmed\Jobs\Model\ResourceModel\Job;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = \Ahmed\Jobs\Model\Job::JOB_ID;
    protected $_eventPrefix = 'ahmed_jobs_job_collection';
    protected $_eventObject = 'job_collection';

    /**
     * Define the resource model & the model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ahmed\Jobs\Model\Job', 'Ahmed\Jobs\Model\ResourceModel\Job');
    }
}
