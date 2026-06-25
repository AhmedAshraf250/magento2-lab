<?php

namespace Ahmed\Jobs\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $connection = $installer->getConnection();
        $installer->startSetup();

        // Action to do if module version is less than 1.0.0.0
        if ($context->getVersion() && version_compare($context->getVersion(), '1.0.0.0') < 0) {

            /**
             * Create table 'ahmed_job'
             */

            $tableName = $installer->getTable('ahmed_job');
            if (!$connection->isTableExists($tableName)) {
                $tableComment = 'Job management on Magento 2';
                $columns = [
                    'entity_id' => [
                        'type' => Table::TYPE_INTEGER,
                        'size' => null,
                        'options' => ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'comment' => 'Job Id',
                    ],
                    'title' => [
                        'type' => Table::TYPE_TEXT,
                        'size' => 255,
                        'options' => ['nullable' => false, 'default' => ''],
                        'comment' => 'Job Title',
                    ],
                    'type' => [
                        'type' => Table::TYPE_TEXT,
                        'size' => 255,
                        'options' => ['nullable' => false, 'default' => ''],
                        'comment' => 'Job Type (CDI, CDD...)',
                    ],
                    'location' => [
                        'type' => Table::TYPE_TEXT,
                        'size' => 255,
                        'options' => ['nullable' => false, 'default' => ''],
                        'comment' => 'Job Location',
                    ],
                    'date' => [
                        'type' => Table::TYPE_DATE,
                        'size' => null,
                        'options' => ['nullable' => false],
                        'comment' => 'Job date begin',
                    ],
                    'status' => [
                        'type' => Table::TYPE_BOOLEAN,
                        'size' => null,
                        'options' => ['nullable' => false, 'default' => 0],
                        'comment' => 'Job status',
                    ],
                    'description' => [
                        'type' => Table::TYPE_TEXT,
                        'size' => 2048,
                        'options' => ['nullable' => false, 'default' => ''],
                        'comment' => 'Job description',
                    ],
                    'department_id' => [
                        'type' => Table::TYPE_INTEGER,
                        'size' => null,
                        'options' => ['unsigned' => true, 'nullable' => false],
                        'comment' => 'Department linked to the job',
                    ],
                ];

                $indexes =  [
                    'title',
                ];

                $foreignKeys = [
                    'department_id' => [
                        'ref_table' => 'ahmed_department',
                        'ref_column' => 'entity_id',
                        'on_delete' => Table::ACTION_CASCADE,
                    ]
                ];

                /**
                 *  We can use the parameters above to create our table
                 */

                // Table creation
                $table = $connection->newTable($tableName);

                // Columns creation
                foreach ($columns as $name => $values) {
                    $table->addColumn(
                        $name,
                        $values['type'],
                        $values['size'],
                        $values['options'],
                        $values['comment']
                    );
                }

                // Indexes creation
                foreach ($indexes as $index) {
                    $table->addIndex(
                        $installer->getIdxName($tableName, [$index]),
                        [$index]
                    );
                }

                // Foreign keys creation
                foreach ($foreignKeys as $column => $foreignKey) {
                    $table->addForeignKey(
                        $installer->getFkName($tableName, $column, $foreignKey['ref_table'], $foreignKey['ref_column']),
                        $column,
                        $installer->getTable($foreignKey['ref_table']),
                        $foreignKey['ref_column'],
                        $foreignKey['on_delete']
                    );
                }

                // Table comment
                $table->setComment($tableComment);

                // Execute SQL to create the table
                $connection->createTable($table);
            }
        }

        if ($context->getVersion() && version_compare($context->getVersion(), '1.0.0.2') < 0) {
            // Action to do if module version is less than 1.0.0.2
            /**
             * Add full text index to our table department
             */

            $tableName = $installer->getTable('ahmed_department');
            $fullTextIntex = ['name']; // Column with fulltext index, you can put multiple fields


            $connection->addIndex(
                $tableName,
                $installer->getIdxName($tableName, $fullTextIntex, \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT),
                $fullTextIntex,
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            );

            /**
             * Add full text index to our table jobs
             */

            $tableName = $installer->getTable('ahmed_job');
            $fullTextIntex = ['title', 'type', 'location', 'description']; // Column with fulltext index, you can put multiple fields


            $connection->addIndex(
                $tableName,
                $installer->getIdxName($tableName, $fullTextIntex, \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT),
                $fullTextIntex,
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }

        if ($context->getVersion() && version_compare($context->getVersion(), '1.1.0.0') < 0) {
            // Action to do if module version is less than 1.1.0.0
        }

        $installer->endSetup();
    }
}
