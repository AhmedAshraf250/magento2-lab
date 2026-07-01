<?php

namespace Ahmed\Jobs\Ui\DataProvider\Department\Form;

use Ahmed\Jobs\Model\ResourceModel\Department\CollectionFactory;
use Magento\Backend\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var array|null
     */
    private ?array $loadedData = null;


    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        private Session $session,
        private RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Return department data indexed by entity ID for the UI form provider.
     *
     * @return array
     */
    public function getData()
    {
        if ($this->loadedData !== null) {
            return $this->loadedData;
        }
        /* 'Ahmed\Jobs\view\adminhtml\layout\jobs_department_edit.xml' file contains the following code:
            <dataProvider   Provider class="Ahmed\Jobs\Ui\DataProvider\Department\Form\DataProvider" name="jobs_department_form_data_source">
                <settings>
                    <requestFieldName>id</requestFieldName>
                    <primaryFieldName>entity_id</primaryFieldName>
                </settings>
            </dataProvider>
            */
        $this->loadedData = [];
        $departmentId = (int) $this->request->getParam($this->getRequestFieldName()); // $this->getRequestFieldName() returns 'id' as defined in the layout file
        if ($departmentId) {
            $this->collection->addFieldToFilter($this->getPrimaryFieldName(), $departmentId);
        }

        foreach ($this->collection->getItems() as $department) {
            $this->loadedData[$department->getId()] = $department->getData();
        }

        $formData = $this->session->getFormData(true);
        if (!empty($formData)) {
            $persistedDepartmentId = $formData['entity_id'] ?? $formData['id'] ?? $departmentId;
            $this->loadedData[$persistedDepartmentId ?: 0] = $formData;
        }

        return $this->loadedData;
    }
}
