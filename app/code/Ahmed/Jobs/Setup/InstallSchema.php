<?php

namespace Ahmed\Jobs\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $connection = $installer->getConnection();
        $installer->startSetup();

        $departmentTableName = $installer->getTable('ahmed_department');
        if (!$connection->isTableExists($departmentTableName)) {
            $departmentTable = $connection->newTable($departmentTableName)
                ->addColumn(
                    'entity_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Department Id'
                )
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Department Name'
                )
                ->addColumn(
                    'description',
                    Table::TYPE_TEXT,
                    2048,
                    ['nullable' => false, 'default' => ''],
                    'Department Description'
                )
                ->addIndex(
                    $installer->getIdxName(
                        $departmentTableName,
                        ['name'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['name'],
                    ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT]
                )
                ->setComment('Department management for jobs module');

            $connection->createTable($departmentTable);
        }

        $jobTableName = $installer->getTable('ahmed_job');
        if (!$connection->isTableExists($jobTableName)) {
            $jobTable = $connection->newTable($jobTableName)
                ->addColumn(
                    'entity_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Job Id'
                )
                ->addColumn(
                    'title',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Job Title'
                )
                ->addColumn(
                    'type',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Job Type (CDI, CDD...)'
                )
                ->addColumn(
                    'location',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Job Location'
                )
                ->addColumn(
                    'date',
                    Table::TYPE_DATE,
                    null,
                    ['nullable' => false],
                    'Job Date Begin'
                )
                ->addColumn(
                    'status',
                    Table::TYPE_BOOLEAN,
                    null,
                    ['nullable' => false, 'default' => 0],
                    'Job Status'
                )
                ->addColumn(
                    'description',
                    Table::TYPE_TEXT,
                    2048,
                    ['nullable' => false, 'default' => ''],
                    'Job Description'
                )
                ->addColumn(
                    'department_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Department linked to the job'
                )
                ->addIndex(
                    $installer->getIdxName($jobTableName, ['title']),
                    ['title']
                )
                ->addIndex(
                    $installer->getIdxName(
                        $jobTableName,
                        ['title', 'type', 'location', 'description'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['title', 'type', 'location', 'description'],
                    ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT]
                )
                ->addForeignKey(
                    $installer->getFkName($jobTableName, 'department_id', 'ahmed_department', 'entity_id'),
                    'department_id',
                    $departmentTableName,
                    'entity_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Job management on Magento 2');

            $connection->createTable($jobTable);
        }

        $installer->endSetup();
    }
}
