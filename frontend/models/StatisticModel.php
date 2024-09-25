<?php

namespace frontend\models;

use common\models\BookAuthor;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class StatisticModel extends BookAuthor
{
    public $year;
    public $authorID;
    public $authorName;
    public $countBooks;

    /**
     * @return string[]
     */
    public function attributeLabels()
    {
        return [
            'year' => 'Год',
            'authorName' => 'ФИО',
            'countBooks' => 'Кол-во'
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['year', 'integer', 'min' => 0, 'max' => date('Y')],
            [['authorID', 'countBooks'], 'integer'],
            ['year', 'default', 'value' => date('Y')],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function query()
    {
        return self::find()->alias('t')
            ->select([
                'year' => 'book.release_year',
                'authorName' => 'author.full_name',
                'countBooks' => 'COUNT(*)',
                'authorID' => 't.author_id'
            ])
            ->joinWith('author')
            ->joinWith('book')
            ->groupBy(['t.author_id'])
            ->limit(10)
            ->orderBy(['countBooks' => SORT_DESC]);
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = $this->query();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'book.release_year' => $this->year,
            't.author_id' => $this->authorID,
        ]);
        $query->andFilterHaving(['countBooks' => $this->countBooks]);

        return $dataProvider;
    }
}