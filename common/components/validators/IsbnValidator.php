<?php

namespace common\components\validators;

use yii\validators\Validator;

class IsbnValidator extends Validator
{
    /**
     * @param $model
     * @param $attribute
     * @return void
     */
    public function validateAttribute($model, $attribute)
    {
        $isbn = $model->$attribute;

        // Удаляем дефисы и пробелы из ISBN
        $isbn = str_replace(['-', ' '], '', $isbn);

        // Определяем тип ISBN (10 или 13) и проверяем
        if (strlen($isbn) === 10) {
            if (!$this->isValidISBN10($isbn)) {
                $this->addError($model, $attribute, 'Некорректный ISBN-10.');
            }
        } elseif (strlen($isbn) === 13) {
            if (!$this->isValidISBN13($isbn)) {
                $this->addError($model, $attribute, 'Некорректный ISBN-13.');
            }
        } else {
            $this->addError($model, $attribute, 'ISBN должен содержать 10 или 13 символов.');
        }
    }

    /**
     * Метод для проверки ISBN-10
     * @param $isbn
     * @return bool
     */
    private function isValidISBN10($isbn)
    {
        // ISBN-10 должен содержать 9 цифр и последний символ, который может быть цифрой или "X"
        if (!preg_match('/^\d{9}[\dX]$/', $isbn)) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += ((int)$isbn[$i]) * ($i + 1);
        }

        $checkDigit = $isbn[9] === 'X' ? 10 : (int)$isbn[9];
        $sum += $checkDigit * 10;

        return $sum % 11 === 0;
    }

    /**
     * Метод для проверки ISBN-13
     * @param $isbn
     * @return bool
     */
    private function isValidISBN13($isbn)
    {
        // ISBN-13 должен содержать 13 цифр
        if (!preg_match('/^\d{13}$/', $isbn)) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int)$isbn[$i] * ($i % 2 === 0 ? 1 : 3);
        }

        $checkDigit = (10 - ($sum % 10)) % 10;

        return $checkDigit == (int)$isbn[12];
    }
}
