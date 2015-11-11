<?php

namespace janisto\timepicker;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\jui\DatePickerLanguageAsset;

/**
 * TimePicker renders a `timepicker` {@link https://github.com/trentrichardson/jQuery-Timepicker-Addon jQuery Timepicker} widget.
 * See {@link http://trentrichardson.com/examples/timepicker/#tp-options jQuery Timepicker} options.
 *
 * For example to use the timepicker with a `yii\base\Model`:
 *
 * ```php
 * echo TimePicker::widget([
 *      //'language' => 'fi',
 *     'model' => $model,
 *     'attribute' => 'created_at',
 *     'mode' => 'datetime',
 *     'clientOptions' => [
 *         'dateFormat' => 'yy-mm-dd',
 *         'timeFormat' => 'HH:mm:ss',
 *         'showSecond' => true,
 *     ]
 * ]);
 * ```
 *
 * ```php
 * echo TimePicker::widget([
 *      //'language' => 'fi',
 *     'model' => $model,
 *     'attribute' => 'created_at',
 *     'mode' => 'datetime',
 *     'inline' => true,
 *     'clientOptions' => [
 *         'onClose' => new \yii\web\JsExpression('function(dateText, inst) { console.log("onClose: " + dateText); }'),
 *         'onSelect' => new \yii\web\JsExpression('function(dateText, inst) { console.log("onSelect: " + dateText); }'),
 *     ]
 * ]);
 * ```
 *
 * The following example will use the name property instead:
 *
 * ```php
 * echo TimePicker::widget([
 *      //'language' => 'fi',
 *     'name' => 'from_time',
 *     'value' => $value,
 *     'mode' => 'time',
 *     'clientOptions' => [
 *         'hour' => date('H'),
 *         'minute' => date('i'),
 *         'second' => date('s'),
 *     ]
 * ]);
 * ```
 *
 * You can also use this widget in an `yii\widgets\ActiveForm` using the `yii\widgets\ActiveField::widget()`
 * method, for example like this:
 *
 * ```php
 * echo $form->field($model, 'field')->widget(\janisto\timepicker\TimePicker::className(), [
 *     //'language' => 'fi',
 *     'mode' => 'datetime',
 *     'clientOptions' => [
 *         'dateFormat' => 'yy-mm-dd',
 *         'timeFormat' => 'HH:mm:ss',
 *         'showSecond' => true,
 *     ]
 * ]);
 * ```
 *
 * @author Jani Mikkonen <janisto@php.net>
 * @license public domain (http://unlicense.org)
 * @link https://github.com/janisto/yii2-timepicker
 */
class TimePicker extends DatePicker
{
    /**
     * @var string Widget mode (date, time or datetime).
     */
    public $mode = 'datetime';
    /**
     * @var string The addon markup if you wish to display the input as a component. If you don't wish to render as a
     * component then set it to null or false.
     */
    public $addon = '<i class="glyphicon glyphicon-calendar"></i>';
    /**
     * @var string The template to render the input.
     */
    public $template = '{addon}{input}';
    /**
     * @var string The size of the input (lg, md or sm)
     */
    public $size;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!in_array($this->mode, ['date', 'time', 'datetime'])) {
            throw new InvalidConfigException('Unknown mode: "' . $this->mode . '". Use time, datetime or date!');
        }
        if ($this->size) {
            Html::addCssClass($this->options, 'input-' . $this->size);
            Html::addCssClass($this->containerOptions, 'input-group-' . $this->size);
        }
        Html::addCssClass($this->options, 'form-control');
        Html::addCssClass($this->containerOptions, 'input-group ' . $this->mode);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->mode == 'date') {
            $this->clientOptions['showTime'] = false;
        }

        if ($this->inline === false) {
            if ($this->hasModel()) {
                $input = Html::activeTextInput($this->model, $this->attribute, $this->options);
            } else {
                $input = Html::textInput($this->name, $this->value, $this->options);
            }
            if ($this->addon) {
                $addon = Html::tag('span', $this->addon, ['class' => 'input-group-addon']);
                $input = strtr($this->template, ['{input}' => $input, '{addon}' => $addon]);
                $input = Html::tag('div', $input, $this->containerOptions);
            }
        } else {
            if ($this->hasModel()) {
                $input = Html::activeHiddenInput($this->model, $this->attribute, $this->options);
                $attribute = $this->attribute;
                $this->clientOptions['defaultDate'] = $this->model->$attribute;
            } else {
                $input = Html::hiddenInput($this->name, $this->value, $this->options);
                $this->clientOptions['defaultDate'] = $this->value;
            }
            $this->clientOptions['altField'] = '#' . $this->options['id'];
            $this->clientOptions['altFieldTimeOnly'] = false;
            $input .= Html::tag('div', null, $this->containerOptions);
            $input = strtr($this->template, ['{input}' => $input, '{addon}' => '']);
        }

        echo $input;
        $this->registerClientScript();
    }

    /**
     * Register widget assets.
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        $containerID = $this->inline ? $this->containerOptions['id'] : $this->options['id'];
        $language = $this->language ? $this->language : Yii::$app->language;
        $name = $this->mode . 'picker';

        $timeAssetBundle = TimePickerAsset::register($view);
        if ($language !== 'en-US') {
            $timeAssetBundle->language = $language;
            $dateAssetBundle = DatePickerLanguageAsset::register($view);
            $dateAssetBundle->language = $language;
        }

        $this->registerClientOptions($name, $containerID);
        $this->registerClientEvents($name, $containerID);
    }
}
