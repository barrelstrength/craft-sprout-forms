<?php

namespace barrelstrength\sproutforms\fields\formfields;

use barrelstrength\sproutbasefields\SproutBaseFields;
use barrelstrength\sproutforms\base\ConditionalLogic;
use barrelstrength\sproutforms\rules\fieldrules\TextCondition;
use Craft;
use craft\base\Element;
use craft\base\ElementInterface;
use craft\base\PreviewableFieldInterface;
use craft\fields\Dropdown as CraftDropdown;
use craft\fields\Email as CraftEmail;
use craft\fields\PlainText as CraftPlainText;
use craft\helpers\Template as TemplateHelper;
use barrelstrength\sproutforms\base\FormField;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Markup;
use Twig_Markup;

/**
 *
 * @property array  $elementValidationRules
 * @property string $svgIconPath
 * @property mixed  $settingsHtml
 * @property array  $compatibleCraftFields
 * @property array  $compatibleCraftFieldTypes
 * @property mixed  $exampleInputHtml
 */
class Email extends FormField implements PreviewableFieldInterface
{
    /**
     * @var string|null
     */
    public $customPattern;

    /**
     * @var bool
     */
    public $customPatternToggle;

    /**
     * @var string|null
     */
    public $customPatternErrorMessage;

    /**
     * @var bool
     */
    public $uniqueEmail;

    /**
     * @var string
     */
    public $cssClasses;

    /**
     * @var string|null
     */
    public $placeholder;

    public static function displayName(): string
    {
        return Craft::t('sprout-forms', 'Email');
    }

    /**
     * @inheritdoc
     *
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getExampleInputHtml(): string
    {
        return Craft::$app->getView()->renderTemplate('sprout-forms/_components/fields/formfields/email/example',
            [
                'field' => $this
            ]
        );
    }

    /**
     * @return string
     */
    public function getSvgIconPath(): string
    {
        return '@sproutbaseicons/envelope.svg';
    }

    /**
     * @inheritdoc
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('sprout-forms/_components/fields/formfields/email/settings',
            [
                'field' => $this,
            ]);
    }

    /**
     * @inheritdoc
     *
     * @param                       $value
     * @param ElementInterface|null $element
     *
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        $name = $this->handle;
        $inputId = Craft::$app->getView()->formatInputId($name);
        $namespaceInputId = Craft::$app->getView()->namespaceInputId($inputId);

        $fieldContext = SproutBaseFields::$app->utilities->getFieldContext($this, $element);

        /** Set this to false for Quick Entry Dashboard Widget
         *
         * @var Element $element
         */
        $elementId = ($element !== null) ? $element->id : false;

        $rendered = Craft::$app->getView()->renderTemplate(
            'sprout-base-fields/_components/fields/formfields/email/input',
            [
                'namespaceInputId' => $namespaceInputId,
                'id' => $inputId,
                'name' => $name,
                'value' => $value,
                'elementId' => $elementId,
                'fieldContext' => $fieldContext,
                'placeholder' => $this->placeholder
            ]);

        return TemplateHelper::raw($rendered);
    }

    /**
     * @param mixed      $value
     * @param array|null $renderingOptions
     *
     * @return Twig_Markup
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getFrontEndInputHtml($value, array $renderingOptions = null): Markup
    {
        $attributes = $this->getAttributes();
        $errorMessage = SproutBaseFields::$app->emailField->getErrorMessage($attributes['name'], $this);
        $placeholder = $this['placeholder'] ?? '';

        $rendered = Craft::$app->getView()->renderTemplate(
            'email/input',
            [
                'name' => $this->handle,
                'value' => $value,
                'field' => $this,
                'errorMessage' => $errorMessage,
                'renderingOptions' => $renderingOptions,
                'placeholder' => $placeholder
            ]
        );

        return TemplateHelper::raw($rendered);
    }

    /**
     * @inheritdoc
     */
    public function getElementValidationRules(): array
    {
        return ['validateEmail'];
    }

    /**
     * Validates our fields submitted value beyond the checks
     * that were assumed based on the content attribute.
     *
     *
     * @param Element|ElementInterface $element
     *
     * @return void
     */
    public function validateEmail(ElementInterface $element)
    {
        $value = $element->getFieldValue($this->handle);

        $customPattern = $this->customPattern;
        $checkPattern = $this->customPatternToggle;

        if (!SproutBaseFields::$app->emailField->validateEmailAddress($value, $customPattern, $checkPattern)) {
            $element->addError($this->handle,
                SproutBaseFields::$app->emailField->getErrorMessage(
                    $this->name, $this)
            );
        }

        $uniqueEmail = $this->uniqueEmail;

        if ($uniqueEmail && !SproutBaseFields::$app->emailField->validateUniqueEmailAddress($value, $element, $this)) {
            $element->addError($this->handle,
                Craft::t('sprout-forms', $this->name.' must be a unique email.')
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function getCompatibleCraftFieldTypes(): array
    {
        return [
            CraftPlainText::class,
            CraftEmail::class,
            CraftDropdown::class
        ];
    }

	public function getCompatibleConditional()
	{
		$textCondition = new TextCondition(['formField' => $this]);
		return $textCondition;
	}
}
