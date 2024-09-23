<?php

namespace AgileStoreLocator\Form;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * The Form Field Classes, used in the Form builder
 *
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/Form
 * @author     AgileLogix <support@agilelogix.com>
 */
class Field {

    protected $label;
    protected $name;
    protected $type;
    protected $options;
    protected $field_name;
    protected $value;
    protected $require;

    public function __construct($label, $name, $type, $value = '', $require = false) {
        
        $this->label    = $label;
        $this->name     = $name;
        $this->type     = $type;
        $this->value    = $value;
        $this->require  = $require;
    }

    public function render($nested = null) {

        //  will be a nested field name?
        $this->field_name = ($nested)? Field::generateFieldName($this->name, $nested): $this->name;

        switch ($this->type) {

            case 'text':
                return $this->renderTextField();


            case 'textarea':
                return $this->renderTextareaField();
            
            
            case 'dropdown':
                return $this->renderSelectField();

            case 'radio':
                return $this->renderRadioField();

            case 'checkbox':
                return $this->renderCheckboxField();

            default:
                return '';
        }
    }

    /**
     * [generateFieldName Generate the nested field name]
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public static function generateFieldName($name, $nested) {

        return esc_attr($nested).'['.esc_attr($name).']';
    }

    /**
     * [renderTextField description]
     * @return [type] [description]
     */
    protected function renderTextField() {

        $require_field = ($this->require)? 'validate[required]': '';

        return sprintf(
            '<div class="sl-form-group sl-group">
                <label class="control-label" for="sl-%s">%s</label>
                <input type="%s" id="sl-%s" name="%s" class="form-control %s" value="%s">
            </div>',
            $this->name,
            $this->label,
            $this->type,
            $this->name,
            $this->field_name,
            $require_field,
            $this->value
        );
    }


    /**
     * [renderTextareaField description]
     * @return [type] [description]
     */
    protected function renderTextareaField() {


        $require_field = ($this->require)? 'validate[required]': '';

        return sprintf(
            '<div class="sl-form-group sl-group">
                <label class="control-label" for="sl-%s">%s</label>
                <textarea type="%s" id="sl-%s" name="%s" class="form-control %s">%s</textarea>
            </div>',
            $this->name,
            $this->label,
            $this->type,
            $this->name,
            $this->field_name,
            $require_field,
            $this->value
        );
    }


    /**
     * [renderSelectField description]
     * @return [type] [description]
     */
    protected function renderSelectField() {


        $require_field = ($this->require)? 'validate[required]': '';
        
        $optionsHTML = '';
        foreach ($this->options as $value) {

            $selected = ($this->value == $value)? 'selected': '';
            $optionsHTML .= sprintf('<option %s value="%s">%s</option>', $selected, $value, $value);
        }

        return sprintf(
            '<div class="sl-form-group sl-form-ddl sl-group">
                <label class="control-label" for="sl-%s">%s</label>
                <select id="sl-%s" name="%s" class="form-control custom-select %s">
                    %s
                </select>
            </div>',
            $this->name,
            $this->label,
            $this->name,
            $this->field_name,
            $require_field,
            $optionsHTML
        );
    }

    /**
     * [renderRadioField description]
     * @return [type] [description]
     */
    protected function renderRadioField() {
        
        $radioOptions = '';


        $require_field = ($this->require)? 'validate[required]': '';
        
        foreach ($this->options as $value) {

            $label_for = sanitize_key($this->name.'-'.$value);

            $checked = ($this->value == $value)? 'checked': '';

            $radioOptions .= sprintf(
                '<div class="form-check"><input class="form-check-input %s" %s id="sl-%s" type="radio" name="%s" value="%s"><label class="form-check-label" for="sl-%s">%s</label></div>',
                $require_field,
                $checked,
                $label_for,
                $this->field_name,
                $value,
                $label_for,
                $value
            );
        }

        return sprintf(
            '<div class="sl-form-group sl-group">
                <label>%s</label><br>
                %s
            </div>',
            $this->label,
            $radioOptions
        );
    }


    /**
     * [renderCheckboxField description]
     * @return [type] [description]
     */
    protected function renderCheckboxField() {

        $checkboxOptions = '';
        
        $isChecked = ($this->value == true) ? 'checked' : '';

        $require_field = ($this->require)? 'validate[required]': '';
        

        $checkboxOptions .= sprintf(
            '<div class="form-check"><input class="form-check-input checkbox-form-check %s" %s id="sl-%s" type="checkbox" name="%s" value="%s"><label class="d-none form-check-label" for="sl-%s">%s</label></div>',
            $require_field,
            $isChecked,
            $this->name,
            $this->field_name,
            true,
            $this->name,
            $this->label
        );

        return sprintf(
            '<div class="sl-form-group sl-group">
                <label for="sl-%s">%s</label>
                %s
            </div>',
            $this->name,
            $this->label,
            $checkboxOptions
        );
    }
}