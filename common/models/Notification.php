<?php

namespace common\models;

use common\components\services\SmsGate;

/**
 * This is the model class for table "notification".
 *
 * @property int $id
 * @property string $phone_number
 * @property int $author_id
 * @property int $status
 *
 * @property Author $author
 */
class Notification extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_SENT = 1;
    const STATUS_SEND = 2;
    const STATUS_FAIL = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone_number', 'author_id'], 'required'],
            [['author_id', 'status'], 'integer'],
            [['phone_number'], 'string', 'max' => 15],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone_number' => 'Phone Number',
            'author_id' => 'Author ID',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

    /**
     * @param $text
     * @return bool
     */
    public function sendNotification($text)
    {
        $statusSend = SmsGate::send($this->phone_number, $text);
        $this->status = $statusSend ? self::STATUS_SEND : self::STATUS_FAIL;
        return $this->save();
    }
}
