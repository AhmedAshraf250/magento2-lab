<?php

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ahmed\Jobs\Cron;

use Ahmed\Jobs\Model\Job;
use Ahmed\Jobs\Model\ResourceModel\Job as JobResource;
use Ahmed\Jobs\Model\ResourceModel\Job\CollectionFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class DisableJobs
{
    protected Job $_job;
    protected CollectionFactory $_jobCollectionFactory;
    protected JobResource $_jobResource;
    protected TimezoneInterface $_timezone;


    public function __construct(
        Job $job,
        CollectionFactory $jobCollectionFactory,
        JobResource $jobResource,
        TimezoneInterface $timezone
    ) {
        $this->_job = $job;
        $this->_jobCollectionFactory = $jobCollectionFactory;
        $this->_jobResource = $jobResource;
        $this->_timezone = $timezone;
    }

    /**
     * Disable jobs which date is less than the current date
     *
     * @param \Magento\Cron\Model\Schedule $schedule
     * @return void
     */
    public function execute(\Magento\Cron\Model\Schedule $schedule)
    {
        $today = $this->_timezone->date()->format('Y-m-d');

        $jobsCollection = $this->_jobCollectionFactory->create()
            ->addFieldToFilter('status', $this->_job->getEnableStatus())
            ->addFieldToFilter('date', ['lt' => $today]);

        foreach ($jobsCollection as $job) {
            $job->setStatus($job->getDisableStatus());
            $this->_jobResource->save($job);
        }
    }
}
