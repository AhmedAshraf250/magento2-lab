<?php

namespace Ahmed\Jobs\Block\Department;

use Ahmed\Jobs\Helper\Data as JobsHelper;
use Ahmed\Jobs\Model\Department;
use Ahmed\Jobs\Model\Job;
use Ahmed\Jobs\Model\ResourceModel\Job\Collection;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Theme\Block\Html\Breadcrumbs;
use Magento\Theme\Block\Html\Title;

class View extends Template
{
    protected ?Collection $_jobCollection = null;

    protected Department $_department;

    protected Job $_job;

    protected Registry $_registry;

    protected JobsHelper $jobsHelper;

    public function __construct(
        Context $context,
        Department $department,
        Job $job,
        Registry $registry,
        JobsHelper $jobsHelper,
        array $data = []
    ) {
        $this->_department = $department;
        $this->_job = $job;
        $this->_registry = $registry;
        $this->jobsHelper = $jobsHelper;

        parent::__construct($context, $data);
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $department = $this->getLoadedDepartment();
        $title = $department->getName();
        $description = __('Look at the jobs we have got for you');
        $keywords = __('job,hiring');

        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');

        if ($breadcrumbsBlock instanceof Breadcrumbs) {
            $breadcrumbsBlock->addCrumb(
                'jobs',
                [
                    'label' => __('We are hiring'),
                    'title' => __('We are hiring'),
                    'link' => $this->getListJobUrl()
                ]
            );
            $breadcrumbsBlock->addCrumb(
                'department',
                [
                    'label' => $title,
                    'title' => $title,
                    'link' => false
                ]
            );
        }

        $this->pageConfig->getTitle()->set($title);
        $this->pageConfig->setDescription($description);
        $this->pageConfig->setKeywords($keywords);

        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle instanceof Title) {
            $pageMainTitle->setPageTitle($title);
        }

        return $this;
    }

    public function getLoadedDepartment(): Department
    {
        return $this->_getDepartment();
    }

    protected function _getDepartment(): Department
    {
        if (!$this->_department->getId()) {
            $department = $this->_registry->registry('ahmed_jobs_current_department');

            if ($department instanceof Department && $department->getId()) {
                $this->_department = $department;
            } else {
                $entityId = $this->getRequest()->getParam('id');
                $this->_department = $this->_department->load($entityId);
            }
        }

        return $this->_department;
    }

    public function getLoadedJobsCollection(): ?Collection
    {
        return $this->_getJobsCollection();
    }

    protected function _getJobsCollection(): ?Collection
    {
        if (!$this->getConfigJobsDepartmentViewList()) {
            return null;
        }

        $department = $this->getLoadedDepartment();

        if ($this->_jobCollection === null && $department->getId()) {
            /** @var Collection  $jobCollection */
            $jobCollection = $this->_job->getCollection();
            $jobCollection->addFieldToFilter('department_id', $department->getId());
            $jobCollection->addStatusFilterWithDepartment($this->_job, $department); // 
            $this->_jobCollection = $jobCollection;
        }

        return $this->_jobCollection;
    }

    public function getJobUrl(Job $job): string
    {
        if (!$job->getId()) {
            return '#';
        }

        return $this->getUrl('jobs/job/view', ['id' => $job->getId()]);
    }

    public function getListJobUrl(): string
    {
        return $this->getUrl('jobs/job');
    }

    public function getConfigJobsDepartmentViewList(): bool // getConfigListJobs
    {
        return $this->jobsHelper->canShowDepartmentJobList();
    }
}
