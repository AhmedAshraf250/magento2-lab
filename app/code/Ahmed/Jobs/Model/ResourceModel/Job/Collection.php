<?php

namespace Ahmed\Jobs\Model\ResourceModel\Job;

use Ahmed\Jobs\Model\Department;
use Ahmed\Jobs\Model\Job;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = Job::JOB_ID;
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

    public function addStatusFilterWithDepartment(Job $job, Department $department)
    {
        $this->addFieldToSelect('*')
            ->addFieldToFilter('status', $job->getEnableStatus());

        $select = $this->getSelect();

        // ->addFieldToFilter('date', array('gt' => date('Y-m-d'))) // "AND" contidion with collections
        // ->addFieldToFilter(
        //     ['status', 'date'],
        //     [
        //         ['eq' => $job->getEnableStatus()],
        //         ['gt' => date('Y-m-d')]
        //     ]
        // ) // "OR" contidion => SELECT `main_table`.* FROM `maxime_job` AS `main_table` WHERE ((`status` = 1) OR (`date` > '2016-03-01'))

        $select->joinLeft(
            // ['department' => $department->getResource()->getMainTable()],
            ['department' => $this->getTable('ahmed_department')],
            'main_table.department_id = department.' . $department->getIdFieldName(),
            ['department_name' => 'name']
        );
        // var_dump($this->getSelect() . '');
        // echo '<br>';
        // var_dump($this->getSelectSql(true) . '');
        // echo '<br>';
        // var_dump($this->getSelect()->__toString());
        // die();
        return $this;
    }

    /* 
        eq	    Is equal
        gteq	Greater than equal
        gt	    Greater than
        lteq	Less than equal
        lt	    Less than
        neq	    Not equal
        like	SQL like (don’t forget to add ‘%’)
        nlike	SQL Not Like (don’t forget to add ‘%’)
        in	    Among
        nin	    Not among
        null	Is null (the array value does not matter, only the key is important)
        notnull	Is not null (the array value does not matter, only the key is important)
        finset	MySQL FIND_IN_SET, for columns with value like “valeur1,valeur2,valeurX”. Ex : Where value “100” exists on this string “76,82,100,628”
    */


    /* 

        "SELECT `main_table`.*, `department`.`name` AS `department_name` FROM `maxime_job` AS `main_table`
        LEFT JOIN `maxime_department` AS `department` ON main_table.department_id = department.entity_id WHERE (`status` = '1') AND ((`name` LIKE '%Sample%') OR (`date` >= '2026-07-05'))
        -----
        $this->addFieldToSelect('*')
            ->addFieldToFilter('status', $job->getEnableStatus())
            ->addFieldToFilter(
                array(
                    'name',
                    'date'
                ),
                array(
                    array('like' => '%Sample%'),
                    array('gteq' => date('Y-m-d'))
                )
            )
            ->getSelect()
            ->joinLeft(
                array('department' => $department->getResource()->getMainTable()),
                'main_table.department_id = department.'.$department->getIdFieldName(),
                array('department_name' => 'name')
            );

    */


    /* 
        
        $collection = $this;

        $today = date('Y-m-d');

        $collection->addFieldToSelect('*');

        $select = $collection->getSelect();

        $select->joinLeft(
            ['department' => $department->getResource()->getMainTable()],
            'main_table.department_id = department.' . $department->getIdFieldName(),
            ['department_name' => 'name']
        );

        // Main Condition: (main_table.status = 1) OR (main_table.status = 0 AND main_table.date >= '2026-07-05') OR (main_table.job_id > 0 AND (main_table.date < '2026-07-05' OR department.name LIKE '%mar%')) 
        $select->where('main_table.status = ?', $job->getEnableStatus())

            ->orWhere(
                'main_table.status = ? AND main_table.date >= ?',
                [$job->getDisableStatus(), $today]
            )

            ->orWhere(new Zend_Db_Expr(
                "main_table.{$job->getIdFieldName()} > 0 
                    AND (main_table.date < '{$today}' 
                        OR department.name LIKE '%mar%')"
            ));

    */
}
