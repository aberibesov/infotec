<?php

use yii\db\Migration;

/**
 * Class m240924_085824_init_service_library
 */
class m240924_085824_init_service_library extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Создание таблицы book
        $this->createTable('book', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(), // Название
            'release_year' => $this->integer()->notNull(), // Год выпуска
            'description' => $this->text(), // Описание
            'isbn' => $this->string(13)->notNull()->unique(), // ISBN
            'cover_image' => $this->string(), // Фото главной страницы
        ]);

        // Создание таблицы author
        $this->createTable('author', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string()->notNull(), // ФИО
        ]);

        // Создание связующей таблицы book_author
        $this->createTable('book_author', [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'PRIMARY KEY(book_id, author_id)', // Уникальная пара
        ]);

        // Индексы и внешние ключи для таблицы book_author
        $this->createIndex(
            'idx-book_author-book_id',
            'book_author',
            'book_id'
        );

        $this->addForeignKey(
            'fk-book_author-book_id',
            'book_author',
            'book_id',
            'book',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-book_author-author_id',
            'book_author',
            'author_id'
        );

        $this->addForeignKey(
            'fk-book_author-author_id',
            'book_author',
            'author_id',
            'author',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаление внешних ключей и индексов для таблицы book_author
        $this->dropForeignKey(
            'fk-book_author-book_id',
            'book_author'
        );

        $this->dropForeignKey(
            'fk-book_author-author_id',
            'book_author'
        );

        $this->dropIndex(
            'idx-book_author-book_id',
            'book_author'
        );

        $this->dropIndex(
            'idx-book_author-author_id',
            'book_author'
        );

        // Удаление таблицы book_author
        $this->dropTable('book_author');

        // Удаление таблицы author
        $this->dropTable('author');

        // Удаление таблицы book
        $this->dropTable('book');
    }
}

