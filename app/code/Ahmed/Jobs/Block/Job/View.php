<?php

namespace Ahmed\Jobs\Block\Job;

use Ahmed\Jobs\Model\Department;
use Ahmed\Jobs\Model\Job;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Theme\Block\Html\Breadcrumbs;
use Magento\Theme\Block\Html\Title;

class View extends Template
{
    protected Job $_job;

    protected Department $_department;

    protected Registry $_registry;

    public function __construct(
        Context $context,
        Job $job,
        Department $department,
        Registry $registry,
        array $data = []
    ) {
        $this->_job = $job;
        $this->_department = $department;
        $this->_registry = $registry;

        parent::__construct($context, $data);
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $job = $this->getLoadedJob();
        $department = $this->getLoadedDepartment();

        $title = $job->getTitle();
        if ($department->getName()) {
            $title .= ' - ' . $department->getName();
        }

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
                'job',
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

    public function getLoadedJob(): Job
    {
        return $this->_getJob();
    }

    protected function _getJob(): Job
    {
        if (!$this->_job->getId()) {
            $job = $this->_registry->registry('ahmed_jobs_current_job');

            if ($job instanceof Job && $job->getId()) {
                $this->_job = $job;
            } else {
                $entityId = $this->getRequest()->getParam('id');
                $this->_job = $this->_job->load($entityId);
            }
        }

        return $this->_job;
    }

    public function getLoadedDepartment(): Department
    {
        return $this->_getDepartment();
    }

    protected function _getDepartment(): Department
    {
        if (!$this->_department->getId()) {
            $job = $this->getLoadedJob();
            if ($job->getDepartmentId()) {
                $this->_department->load($job->getDepartmentId());
            }
        }

        return $this->_department;
    }

    public function getListJobUrl(): string
    {
        return $this->getUrl('jobs/job');
    }

    public function getDepartmentUrl(Job $job): string
    {
        if (!$job->getDepartmentId()) {
            return '#';
        }

        return $this->getUrl('jobs/department/view', ['id' => $job->getDepartmentId()]);
    }
}
