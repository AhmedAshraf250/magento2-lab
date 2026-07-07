<?php

namespace Ahmed\Jobs\Block\Job;

use Ahmed\Jobs\Model\Department;
use Ahmed\Jobs\Model\Job;
use Ahmed\Jobs\Model\ResourceModel\Job\Collection;
use Ahmed\Jobs\Model\ResourceModel\Job\CollectionFactory;
use Magento\Framework\App\ResourceConnection;

class ListJob extends \Magento\Framework\View\Element\Template
{
    protected Job $_job;

    protected Department $_department;

    protected ResourceConnection $_resource;

    protected ?Collection $_jobCollection = null;

    protected CollectionFactory $_jobCollectionFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param Job $job
     * @param Department $department
     * @param ResourceConnection $resource
     * @param CollectionFactory $jobCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Job $job,
        Department $department,
        ResourceConnection $resource,
        CollectionFactory $jobCollectionFactory,
        array $data = []
    ) {
        $this->_job = $job;
        $this->_department = $department;
        $this->_resource = $resource;
        $this->_jobCollectionFactory = $jobCollectionFactory;

        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();


        // You can put these informations editable on BO
        $title = __('We are hiring');
        $description = __('Look at the jobs we have got for you');
        $keywords = __('job,hiring');

        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbsBlock instanceof \Magento\Theme\Block\Html\Breadcrumbs) {
            $breadcrumbsBlock->addCrumb(
                'jobs',
                [
                    'label' => $title,
                    'title' => $title,
                    'link' => false // No link for the last element
                ]
            );
        }

        $this->pageConfig->getTitle()->set($title);
        $this->pageConfig->setDescription($description);
        $this->pageConfig->setKeywords($keywords);


        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle instanceof \Magento\Theme\Block\Html\Title) {
            $pageMainTitle->setPageTitle($title);
        }

        return $this;
    }

    protected function _getJobCollection()
    {
        if ($this->_jobCollection === null) {
            /* 
                SELECT main_table.*, department.name AS department_name
                FROM ahmed_job AS main_table
                INNER JOIN ahmed_department AS department
                    ON main_table.department_id = department.entity_id
                WHERE main_table.status = 1
            */

            // $jobCollection = $this->_job->getCollection()
            //     ->addFieldToSelect('*')
            //     ->addFieldToFilter('status', $this->_job->getEnableStatus())
            //     ->join(
            //         ['department' => $this->_department->getResource()->getMainTable()],
            //         'main_table.department_id = department.' . $this->_department->getIdFieldName(), // job.department_id = department.entity_id
            //         ['department_name' => 'name']
            //     );

            $jobCollection = $this->_jobCollectionFactory->create();
            $jobCollection->addStatusFilter($this->_job, $this->_department);

            $this->_jobCollection = $jobCollection;
        }

        return $this->_jobCollection;
    }


    public function getLoadedJobCollection()
    {
        return $this->_getJobCollection();
    }

    public function getJobUrl(Job $job)
    {
        if (!$job->getId()) {
            return '#';
        }

        return $this->getUrl('jobs/job/view', ['id' => $job->getId()]);
    }

    public function getDepartmentUrl(Job $job)
    {
        if (!$job->getDepartmentId()) {
            return '#';
        }

        return $this->getUrl('jobs/department/view', ['id' => $job->getDepartmentId()]);
    }
}
