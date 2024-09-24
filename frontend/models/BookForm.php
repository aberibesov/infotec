<?php

namespace frontend\models;

use common\components\validators\IsbnValidator;
use common\models\Book;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class BookForm extends Model
{
    const FILE_IMAGE_PATH = '@frontend/web/covers/';

    public $id;
    public $title;
    public $release_year;
    public $description;
    public $isbn;
    public $file;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title', 'release_year', 'isbn'], 'required'],
            [['release_year'], 'integer', 'min' => 0, 'max' => date('Y')],
            [['title', 'description'], 'string'],
            [['cover_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxSize' => 1024 * 1024 * 2],
            ['isbn', IsbnValidator::class],

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
            'cover_image' => 'Обложка'
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
            'isbn' => $this->isbn
        ]);

        $file = UploadedFile::getInstance($this, 'file');

        if ($file === null) {
            $this->addError('file', 'Ошибка загрузки файла');
            return false;
        }

        $file_path = $this->encryptFileName($file) . '.' . $this->file->extension;
        $file->saveAs(Yii::getAlias(self::FILE_IMAGE_PATH . $file_path));
        $book->cover_image = $file_path;

        return $book->save();
    }
}