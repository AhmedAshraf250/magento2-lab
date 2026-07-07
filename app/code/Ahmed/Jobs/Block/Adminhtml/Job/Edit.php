<?php

namespace Ahmed\Jobs\Block\Adminhtml\Job;

use Magento\Backend\Block\Widget\Form\Container;

class Edit extends Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Job edit block
     *
     * @return void
     */
    // Ahmed\Jobs\Block\Adminhtml\Job\Form\{actions}(Edit,...)
    // [Block_Group] + \Block\ + [Controller_Path] + \[Action_Block]
    // [Block_Group] + \Block\ + [Controller_Path] + \ [Mode] + \Form    || Mode is 'Edit' here name of this class
    protected function _construct()
    {
        $this->_objectId = 'id'; // This is the ID of the object being edited, typically used to identify the record in the database.
        $this->_blockGroup = 'Ahmed_Jobs'; // This specifies the module name, which is used to locate templates and other resources related to this block.
        $this->_controller = 'adminhtml_job'; // This defines the controller path that will handle requests for this block, typically used to generate URLs for form actions and buttons.

        parent::_construct();

        if ($this->_isAllowedAction('Ahmed_Jobs::job_save')) {
            $this->buttonList->update('save', 'label', __('Save Job'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }
    }

    /**
     * Get header with Job name
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('jobs_job')->getId()) {
            return __("Edit Job '%1'", $this->escapeHtml($this->_coreRegistry->registry('jobs_job')->getTitle()));
        } else {
            return __('New Job');
        }
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('jobs/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
}
