<?php

namespace Ahmed\Jobs\Model\ResourceModel\Department;

class CollectionFactory_legacy
{
    public function __construct(
        protected \Magento\Framework\ObjectManagerInterface $objectManager,
        protected $instanceName = '\\Ahmed\\Jobs\\Model\\ResourceModel\\Department\\Collection'
    ) {
        $this->objectManager = $objectManager;
        $this->instanceName = $instanceName;
    }

    public function create(array $data = [])
    {
        return $this->objectManager->create($this->instanceName, $data);
    }
}
