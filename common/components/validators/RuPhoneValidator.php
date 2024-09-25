<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 02.08.2017
 * Time: 13:20
 */

namespace common\components\validators;

use yii\validators\Validator;

class RuPhoneValidator extends Validator
{
    // Регулярное выражение: 10 цифр, первая из которых 9
    public $pattern = '/^9\d{9}$/';
    public $message = 'Номер должен состоять из 10 цифр, и первая цифра должна быть 9.';

    /**
     * @param $model
     * @param $attribute
     * @return void
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if (!preg_match($this->pattern, $value)) {
            $this->addError($model, $attribute, $this->message);
        }
    }
}