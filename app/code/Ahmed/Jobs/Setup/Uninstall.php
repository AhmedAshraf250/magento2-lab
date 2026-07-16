<?php

namespace Ahmed\Jobs\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;

class Uninstall implements UninstallInterface
{
    private const CONFIG_PATH_PREFIX = 'jobs/%';

    private const UI_BOOKMARK_NAMESPACES = [
        'jobs_department_listing',
        'jobs_job_listing',
    ];

    private const TABLES = [
        'ahmed_job',
        'ahmed_department',
    ];

    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        $setup->startSetup();

        $connection = $setup->getConnection();

        $this->deleteConfig($setup);
        $this->deleteUiBookmarks($setup);

        foreach (self::TABLES as $table) {
            $connection->dropTable($setup->getTable($table));
        }

        $setup->endSetup();
    }

    private function deleteConfig(SchemaSetupInterface $setup): void
    {
        $connection = $setup->getConnection();
        $configTable = $setup->getTable('core_config_data');

        $connection->delete(
            $configTable,
            ['path LIKE ?' => self::CONFIG_PATH_PREFIX]
        );
    }

    private function deleteUiBookmarks(SchemaSetupInterface $setup): void
    {
        $connection = $setup->getConnection();
        $bookmarkTable = $setup->getTable('ui_bookmark');

        if (!$connection->isTableExists($bookmarkTable)) {
            return;
        }

        $connection->delete(
            $bookmarkTable,
            ['namespace IN (?)' => self::UI_BOOKMARK_NAMESPACES]
        );
    }
}
