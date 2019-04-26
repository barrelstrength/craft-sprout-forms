<?php

namespace barrelstrength\sproutforms\migrations;

use barrelstrength\sproutforms\integrationtypes\PayloadForwarding;
use barrelstrength\sproutforms\records\Integration as IntegrationRecord;
use craft\db\Migration;
use craft\db\Query;

/**
 * m190410_000000_add_payload_forwarding_to_integration migration.
 */
class m190410_000000_add_payload_forwarding_to_integration extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $forms = (new Query())
            ->select(['id', 'submitAction'])
            ->from(['{{%sproutforms_forms}}'])
            ->where('[[submitAction]] is not null')
            ->all();

        $type = PayloadForwarding::class;

        foreach ($forms as $form) {
            $integrationRecord = new IntegrationRecord();
            $integrationRecord->type = $type;
            $integrationRecord->formId = $form['id'];
            /** @var PayloadForwarding $integrationApi */
            $integrationApi = $integrationRecord->getIntegrationApi();
            $settings = [];

            if ($form['submitAction']){
                $settings['submitAction'] = $form['submitAction'];
                $formFields = $integrationApi->getFormFieldsAsOptions();
                $fieldsMapped = [];
                foreach ($formFields as $formField){
                    $fieldsMapped[] = [
                        'sproutFormField' => $formField['value'],
                        'integrationField' => ''
                    ];
                }

                $integrationRecord->name = $integrationApi->getName();
                $integrationRecord->settings = json_encode($settings);
                $integrationRecord->save();
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m190410_000000_add_payload_forwarding_to_integration cannot be reverted.\n";
        return false;
    }
}
