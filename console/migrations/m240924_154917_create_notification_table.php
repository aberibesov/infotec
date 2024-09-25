<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notification}}`.
 */
class m240924_154917_create_notification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Создаем таблицу `notification`
        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey(), // Первичный ключ
            'phone_number' => $this->string(15)->notNull(), // Номер телефона (макс. 15 символов)
            'author_id' => $this->integer()->notNull(), // Идентификатор автора
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
        ]);

        // Добавляем внешний ключ для таблицы `author`, предположим, что таблица `author` существует
        $this->addForeignKey(
            'fk-notification-author_id',
            '{{%notification}}',
            'author_id',
            '{{%author}}', // имя таблицы авторов (замените на актуальное)
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаляем внешний ключ
        $this->dropForeignKey(
            'fk-notification-author_id',
            '{{%notification}}'
        );

        // Удаляем таблицу `notification`
        $this->dropTable('{{%notification}}');
    }
}

