 <!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<?php $form = ActiveForm::begin([
    'id' => 'update-form',
    'options' => ['class' => 'form-horizontal'],
]) ?>
    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'slug') ?>
    <?= $form->field($model, 'categoryId')->dropDownList($categories,['id' => 'categoryIdInput'])->label("Category") ?>
    <?= $form->field($model, 'tags') ?>
    <?= $form->field($model, 'accessibilityStatus')->dropDownList([0 => "Allowed to all", 1 => "Allowed by link", 
        2 => "Allowed to authorized", 3 =>"Allowed to admins"]) ?>
    <?= $form->field($model, 'header') ?>
    <?= $form->field($model, 'summary') ?>
    <?= $form->field($model, 'description')->textarea() ?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Update Page', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
<?php ActiveForm::end() ?>