<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Book;
use common\models\Author;
use yii\web\UploadedFile;
use common\models\BookAuthor;
use common\components\validators\IsbnValidator;

class BookForm extends Model
{
    const FILE_IMAGE_PATH = '@frontend/web/covers/';

    public $id;
    public $title;
    public $release_year;
    public $description;
    public $isbn;
    public $file;
    public $cover_image;
    public $authors;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title', 'release_year', 'isbn'], 'required'],
            ['release_year', 'integer', 'min' => 0, 'max' => date('Y')],
            [['title', 'description'], 'string'],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxSize' => 1024 * 1024 * 2],
            ['isbn', IsbnValidator::class],
            ['isbn', 'unique', 'targetClass' => Book::class, 'targetAttribute' => ['isbn' => 'isbn']],
            ['authors', 'each', 'rule' => ['integer']],
            ['authors', 'exist', 'targetClass' => Author::class, 'targetAttribute' => 'id', 'allowArray' => true],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'release_year' => 'Год выпуска',
            'description' => 'Описание',
            'isbn' => 'ISBN',
            'cover_image' => 'Обложка',
            'authors' => 'Авторы',
        ];
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    private function encryptFileName(UploadedFile $file)
    {
        return sha1($file->baseName . time());
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $book = new Book([
            'title' => $this->title,
            'release_year' => $this->release_year,
            'description' => $this->description,
            'isbn' => str_replace(['-', ' '], '', $this->isbn)
        ]);

        $file = UploadedFile::getInstance($this, 'file');

        if ($file !== null) {
            //если на этом этапе есть id значит книга уже существует и обложку залили новую
            if ($this->id) {
                $book = Book::findOne($this->id);
                unlink(self::FILE_IMAGE_PATH . $book->cover_image);
            }
            $file_path = $this->encryptFileName($file) . '.' . $file->extension;
            $file->saveAs(Yii::getAlias(self::FILE_IMAGE_PATH . $file_path));
            $book->cover_image = $file_path;
        }

        if ($book->save()) {
            $this->id = $book->id;
            //удаляем всех авторов у книги, перед добавлением новых
            BookAuthor::deleteAll(['book_id' => $this->id]);
            if (!empty($this->authors)) {
                foreach ($this->authors as $author) {
                    $bookAuthor = new BookAuthor([
                        'book_id' => $book->id,
                        'author_id' => $author
                    ]);
                    $bookAuthor->save(false);
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function initFormById(int $id): bool
    {
        $bookModel = Book::findOne($id);

        if ($bookModel === null) {
            return false;
        }

        $this->id = $bookModel->id;
        $this->title = $bookModel->title;
        $this->release_year = $bookModel->release_year;
        $this->description = $bookModel->description;
        $this->isbn = $bookModel->isbn;
        $this->authors = BookAuthor::find()->select('author_id')->where(['book_id' => $id])->column();
        $this->cover_image = $bookModel->cover_image;
        return true;
    }
}