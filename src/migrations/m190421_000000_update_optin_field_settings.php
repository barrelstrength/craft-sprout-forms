<?php /** @noinspection ClassConstantCanBeUsedInspection */

namespace barrelstrength\sproutforms\migrations;

use barrelstrength\sproutforms\fields\formfields\OptIn;
use craft\db\Migration;
use craft\db\Query;

/**
 * m190421_000000_update_optin_field_settings migration.
 */
class m190421_000000_update_optin_field_settings extends Migration
{
    /**
     * @return bool
     * @throws \Throwable
     */
    public function safeUp(): bool
    {
        $optinFields = (new Query())
            ->select(['id', 'handle', 'settings'])
            ->from(['{{%fields}}'])
            ->where(['type' => OptIn::class])
            ->all();

        foreach ($optinFields as $optinField) {
            $settings = json_decode($optinField['settings']);

            if (!isset($settings->optInValueWhenTrue)) {
                $settings->optInValueWhenTrue = 'Yes';
            }

            if (!isset($settings->optInValueWhenFalse)) {
                $settings->optInValueWhenFalse = 'No';
            }

            $this->update('{{%fields}}', ['settings' => json_encode($settings)], ['id' => $optinField['id']], [], false);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m190421_000000_update_optin_field_settings cannot be reverted.\n";
        return false;
    }
}
