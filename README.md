# Yii 2 Timepicker

Yii 2 widget for [jQuery Timepicker Addon](https://github.com/trentrichardson/jQuery-Timepicker-Addon).

[![Software License](https://img.shields.io/badge/license-Unlicense-blue.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/janisto/yii2-timepicker/master.svg?style=flat-square)](https://travis-ci.org/janisto/yii2-timepicker)
[![Quality Score](https://img.shields.io/scrutinizer/g/janisto/yii2-timepicker.svg?style=flat-square)](https://scrutinizer-ci.com/g/janisto/yii2-timepicker)
[![Packagist Version](https://img.shields.io/packagist/v/janisto/yii2-timepicker.svg?style=flat-square)](https://packagist.org/packages/janisto/yii2-timepicker)
[![Total Downloads](https://img.shields.io/packagist/dt/janisto/yii2-timepicker.svg?style=flat-square)](https://packagist.org/packages/janisto/yii2-timepicker)

## Installation

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this package using the following command:

```php
php composer.phar require "janisto/yii2-timepicker" "*"
```
or add

```json
"janisto/yii2-timepicker": "*"
```

to the require section of your application's `composer.json` file.

## Usage

See [jQuery Timepicker](http://trentrichardson.com/examples/timepicker/#tp-options) options.

For example to use the timepicker with a `yii\base\Model`:

```php
echo TimePicker::widget([
     //'language' => 'fi',
    'model' => $model,
    'attribute' => 'created_at',
    'mode' => 'datetime',
    'clientOptions' => [
        'dateFormat' => 'yy-mm-dd',
        'timeFormat' => 'HH:mm:ss',
        'showSecond' => true,
    ]
]);
```

```php
echo TimePicker::widget([
    //'language' => 'fi',
    'model' => $model,
    'attribute' => 'created_at',
    'mode' => 'datetime',
    'inline' => true,
    'clientOptions' => [
        'onClose' => new \yii\web\JsExpression('function(dateText, inst) { console.log("onClose: " + dateText); }'),
        'onSelect' => new \yii\web\JsExpression('function(dateText, inst) { console.log("onSelect: " + dateText); }'),
    ]
]);
```

The following example will use the name property instead:

```php
echo TimePicker::widget([
     //'language' => 'fi',
    'name'  => 'from_time',
    'value'  => $value,
    'mode' => 'time',
    'clientOptions' => [
        'hour' => date('H'),
        'minute' => date('i'),
        'second' => date('s'),
    ]
]);
```

You can also use this widget in an `yii\widgets\ActiveForm` using the `yii\widgets\ActiveField::widget()`
method, for example like this:

```php
echo $form->field($model, 'field')->widget(\janisto\timepicker\TimePicker::className(), [
    //'language' => 'fi',
    'mode' => 'datetime',
    'clientOptions' => [
        'dateFormat' => 'yy-mm-dd',
        'timeFormat' => 'HH:mm:ss',
        'showSecond' => true,
    ]
]);
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Jani Mikkonen](https://github.com/janisto)
- [All Contributors](../../contributors)

## License

Public domain. Please see [License File](LICENSE.md) for more information.
