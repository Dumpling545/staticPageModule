<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'create-form',
    'options' => ['class' => 'form-horizontal'],
]) ?>
    <?= $form->field($model, 'slug') ?>
    <?= $form->field($model, 'name') ?>
<?php
    $categories[-1] = "<no parent>";
?>
    <?= $form->field($model, 'parentId')->dropDownList($categories)->label("Parent category") ?>
    <?= $form->field($model, 'accessibilityStatus')->dropDownList([0 => "Allowed to all", 1 => "Allowed by link", 
        2 => "Allowed to authorized", 3 => "Allowed to admins"]) ?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Create Category', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
<?php ActiveForm::end() ?>
