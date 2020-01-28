<?php /** @noinspection ClassConstantCanBeUsedInspection */

namespace barrelstrength\sproutforms\migrations;

use barrelstrength\sproutbaseemail\migrations\m200110_000000_update_to_cc_bcc_columns_to_text_type;
use craft\db\Migration;
use yii\base\NotSupportedException;

class m200110_000000_update_to_cc_bcc_columns_to_text_type_sproutforms extends Migration
{
    /**
     * @return bool
     * @throws NotSupportedException
     */
    public function safeUp(): bool
    {
        $migration = new m200110_000000_update_to_cc_bcc_columns_to_text_type();

        ob_start();
        $migration->safeUp();
        ob_end_clean();

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m200110_000000_update_to_cc_bcc_columns_to_text_type_sproutforms cannot be reverted.\n";

        return false;
    }
}
