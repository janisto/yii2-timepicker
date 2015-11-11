<?php

namespace janisto\timepicker;

use Yii;
use yii\web\AssetBundle;

class TimePickerAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/jqueryui-timepicker-addon/dist';
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\jui\JuiAsset',
    ];
    /**
     * @var string language to register translation file for
     */
    public $language;

    /**
     * @inheritdoc
     */
    public function registerAssetFiles($view)
    {
        $this->css[] = 'jquery-ui-timepicker-addon' . (YII_DEBUG ? '' : '.min') . '.css';
        $this->js[] = 'jquery-ui-timepicker-addon' . (YII_DEBUG ? '' : '.min') . '.js';

        $language = $this->language;

        if ($language !== null && $language !== 'en-US') {
            $fallbackLanguage = substr($language, 0, 2);
            if ($fallbackLanguage !== $language && !file_exists(Yii::getAlias($this->sourcePath . "/i18n/jquery-ui-timepicker-{$language}.js"))) {
                $language = $fallbackLanguage;
            }
            $this->js[] = "i18n/jquery-ui-timepicker-{$language}.js";
        }

        parent::registerAssetFiles($view);
    }
}
