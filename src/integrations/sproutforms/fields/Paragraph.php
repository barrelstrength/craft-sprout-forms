<?php

namespace barrelstrength\sproutforms\integrations\sproutforms\fields;

use Craft;
use craft\helpers\Template as TemplateHelper;
use yii\db\Schema;
use craft\base\ElementInterface;
use craft\base\PreviewableFieldInterface;

use barrelstrength\sproutforms\contracts\SproutFormsBaseField;

/**
 * Class PlainText
 *
 * @package Craft
 */
class Paragraph extends SproutFormsBaseField implements PreviewableFieldInterface
{
    /**
     * @var string|null The input’s placeholder text
     */
    public $placeholder = '';

    /**
     * @var int The minimum number of rows the input should have, if multi-line
     */
    public $initialRows = 4;

    /**
     * @var int|null The maximum number of characters allowed in the field
     */
    public $charLimit;

    /**
     * @var string The type of database column the field should have in the content table
     */
    public $columnType = Schema::TYPE_TEXT;

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('sprout-forms', 'Paragraph');
    }

    /**
     * @inheritdoc
     */
    public function getExampleInputHtml()
    {
        return Craft::$app->getView()->renderTemplate('sprout-forms/_components/fields/paragraph/example',
            [
                'field' => $this
            ]
        );
    }

    /**
     * Adds support for edit field in the Entries section of SproutForms (Control
     * panel html)
     *
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        return Craft::$app->getView()->renderTemplate('sprout-base/sproutfields/_fields/paragraph/input',
            [
                'name' => $this->handle,
                'value' => $value,
                'field' => $this,
            ]);
    }

    /**
     * @param \barrelstrength\sproutforms\contracts\FieldModel $field
     * @param mixed                                            $value
     * @param mixed                                            $settings
     * @param array|null                                       $renderingOptions
     *
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function getFormInputHtml($field, $value, $settings, array $renderingOptions = null): string
    {
        $this->beginRendering();

        $rendered = Craft::$app->getView()->renderTemplate(
            'paragraph/input',
            [
                'name' => $field->handle,
                'value' => $value,
                'field' => $field,
                'settings' => $settings,
                'renderingOptions' => $renderingOptions
            ]
        );

        $this->endRendering();

        return TemplateHelper::raw($rendered);
    }

    /**
     * @return null|string
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function getSettingsHtml()
    {
        $rendered = Craft::$app->getView()->renderTemplate(
            'sprout-forms/_components/fields/paragraph/settings',
            [
                'field' => $this,
            ]
        );

        return $rendered;
    }

    /**
     * @return string
     */
    public function getSvgIconPath()
    {
        return '@sproutbaseicons/paragraph.svg';
    }
}