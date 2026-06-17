<?php

namespace Ahmed\Jobs\Setup;

use Ahmed\Jobs\Model\DepartmentFactory;
use Ahmed\Jobs\Model\JobFactory;
use Ahmed\Jobs\Model\ResourceModel\Department as DepartmentResource;
use Ahmed\Jobs\Model\ResourceModel\Job as JobResource;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    private DepartmentFactory $departmentFactory;

    private JobFactory $jobFactory;

    private DepartmentResource $departmentResource;

    private JobResource $jobResource;

    public function __construct(
        DepartmentFactory $departmentFactory,
        JobFactory $jobFactory,
        DepartmentResource $departmentResource,
        JobResource $jobResource
    ) {
        $this->departmentFactory = $departmentFactory;
        $this->jobFactory = $jobFactory;
        $this->departmentResource = $departmentResource;
        $this->jobResource = $jobResource;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $departments = [
            [
                'name' => 'Marketing',
                'description' => 'Duplexque isdem diebus acciderat malum, quod et Theophilum insontem atrox'
            ],
            [
                'name' => 'Technical Support',
                'description' => 'Post hanc adclinis Libano monti Phoenice, regio plena gratiarum et'
            ],
            [
                'name' => 'Human Resource',
                'description' => 'Duplexque isdem diebus acciderat malum, quod et Theophilum insontem atrox.'
            ]
        ];

        $departmentIds = [];
        foreach ($departments as $data) {
            $department = $this->departmentFactory->create();
            $department->setData($data);
            $this->departmentResource->save($department);

            $departmentIds[] = $department->getId();
        }

        $jobStatus = $this->jobFactory->create();
        $jobs = [
            [
                'title' => 'Sample Marketing Job 1',
                'type' => 'CDI',
                'location' => 'Paris, France',
                'date' => '2016-01-05',
                'status' => $jobStatus->getEnableStatus(),
                'description' => 'Duplexque isdem diebus acciderat malum, quod et Theophilum insontem atrox',
                'department_id' => $departmentIds[0]
            ],
            [
                'title' => 'Sample Marketing Job 2',
                'type' => 'CDI',
                'location' => 'Paris, France',
                'date' => '2016-01-10',
                'status' => $jobStatus->getDisableStatus(),
                'description' => 'Duplexque isdem diebus acciderat malum, quod et Theophilum insontem atrox',
                'department_id' => $departmentIds[0]
            ],
            [
                'title' => 'Sample Technical Support Job 1',
                'type' => 'CDD',
                'location' => 'Lille, France',
                'date' => '2016-02-01',
                'status' => $jobStatus->getEnableStatus(),
                'description' => 'Duplexque isdem diebus acciderat malum, quod et Theophilum insontem atrox',
                'department_id' => $departmentIds[1]
            ],
            [
                'title' => 'Sample Human Resource Job 1',
                'type' => 'CDI',
                'location' => 'Paris, France',
                'date' => '2016-01-01',
                'status' => $jobStatus->getEnableStatus(),
                'description' => 'Duplexque isdem diebus acciderat malum, quod et Theophilum insontem atrox.',
                'department_id' => $departmentIds[2]
            ]
        ];

        foreach ($jobs as $data) {
            $job = $this->jobFactory->create();
            $job->setData($data);
            $this->jobResource->save($job);
        }

        $installer->endSetup();
    }
}
