<?php

namespace Ahmed\Jobs\Model\Source;

use Ahmed\Jobs\Model\ResourceModel\Department\CollectionFactory;

class Department implements \Magento\Framework\Data\OptionSourceInterface
{
    protected $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];

        $departmentCollection = $this->collectionFactory->create()
            ->addFieldToSelect('entity_id')
            ->addFieldToSelect('name');

        foreach ($departmentCollection as $department) {
            //['label' => 'Department Name', 'value' => 1]
            $options[] = [
                'label' => $department->getName(),
                'value' => $department->getId(),
            ];
        }

        return $options;
    }
}
