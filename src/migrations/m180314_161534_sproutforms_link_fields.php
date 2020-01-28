<?php

namespace barrelstrength\sproutforms\migrations;

use barrelstrength\sproutforms\fields\formfields\Url;
use craft\db\Migration;
use craft\db\Query;

/**
 * m180314_161534_sproutforms_link_fields migration.
 */
class m180314_161534_sproutforms_link_fields extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $fields = (new Query())
            ->select(['id', 'handle', 'settings'])
            ->from(['{{%fields}}'])
            ->where(['type' => 'SproutFields_Link'])
            ->andWhere(['like', 'context', 'sproutForms:'])
            ->all();

        foreach ($fields as $field) {
            $this->update('{{%fields}}', ['type' => Url::class], ['id' => $field['id']], [], false);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m180314_161534_sproutforms_link_fields cannot be reverted.\n";

        return false;
    }
}
