<?php

namespace frontend\models;

use yii\base\Model;
use common\models\Author;
use common\models\Notification;
use common\components\validators\RuPhoneValidator;

class SubscribeAuthorForm extends Model
{
    public $authorID;
    public $phone;

    /**
     * @return string[]
     */
    public function activeAttributes()
    {
        return [
            'authorID' => 'Автор',
            'phone' => 'Номер телефона',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['authorID', 'phone'], 'required'],
            ['phone', RuPhoneValidator::class],
            ['authorID', 'integer'],
            ['authorID', 'exist', 'targetClass' => Author::class, 'targetAttribute' => ['authorID' => 'id']],
        ];
    }

    /**
     * @return bool
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $notificationModel = new Notification();
        $notificationModel->author_id = $this->authorID;
        $notificationModel->phone_number = $this->phone;
        $notificationModel->status = Notification::STATUS_NEW;
        return $notificationModel->save();
    }
}