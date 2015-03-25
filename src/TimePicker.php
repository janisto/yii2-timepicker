<?php

namespace janisto\timepicker;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\helpers\FormatConverter;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\jui\DatePicker;

/**
 * TimePicker widget
 * Widget wrapper for {@link https://github.com/trentrichardson/jQuery-Timepicker-Addon jQuery Timepicker Addon}.
 *
 * Usage:
 * ```php
 * echo $form->field($model, 'field')->widget(\janisto\timepicker\TimePicker::className(), [
 *     //'language' => 'fi',
 *     //'dateFormat' => 'yyyy-MM-dd',
 * ]);
 * ```
 */
class TimePicker extends DatePicker
{
    /**
     * @var array {@link http://trentrichardson.com/examples/timepicker/#tp-options Timepicker} options
     */
    public $settings = [];
    /**
     * @var string|null Selector.
     */
    public $selector;
    /**
     * @var string widget mode. Use time, datetime or date.
     */
    public $mode = 'datetime';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!in_array($this->mode, array('date', 'time', 'datetime'))) {
            throw new InvalidConfigException('Unknown mode: "' . $this->mode . '". Use time, datetime or date!');
        }

        // Set language
        if ($this->language === null && ($language = Yii::$app->language) !== 'en-US') {
            $this->language = substr($language, 0, 2);
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->mode == 'date') {
            parent::run();
        } else {
            $this->registerClientScript();

            if ($this->hasModel()) {
                return Html::activeTextInput($this->model, $this->attribute, $this->options);
            } else {
                return Html::textInput($this->name, $this->value, $this->options);
            }
        }
    }

    /**
     * Register widget asset.
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        $selector = '#' . $this->options['id'];
        $settings = !empty($this->settings) ? Json::encode($this->settings) : '';
        $asset = TimePickerAsset::register($view);

        if ($this->language !== null) {
            $asset->language = $this->language;
            $view->registerJs("jQuery.datepicker.setDefaults(jQuery.datepicker.regional['{$this->language}']);");
            //$view->registerJs("jQuery.timepicker.setDefaults(jQuery.timepicker.regional['{$this->language}']);");
        }

        $view->registerJs("jQuery('$selector').' . $this->mode . 'picker($settings);");
    }
}
