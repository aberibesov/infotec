<?php

use common\models\Author;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Book $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'release_year')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?php if ($model->cover_image): ?>
        <img src="<?= '/covers/' . $model->cover_image ?>">
    <?php endif; ?>

    <?= $form->field($model, 'file')->fileInput(['max']) ?>

    <?= $form->field($model, 'authors')->widget(Select2::class, [
        'data' => Author::find()->select('full_name')->indexBy('id')->column(),
        'options' => ['multiple' => true, 'placeholder' => 'Введите автора(ов)'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
