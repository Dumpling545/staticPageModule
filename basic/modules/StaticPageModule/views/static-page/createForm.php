<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<?php $form = ActiveForm::begin([
    'id' => 'create-form',
    'options' => ['class' => 'form-horizontal'],
]) ?>
    <?= $form->field($model, 'slug') ?>
    <?= $form->field($model, 'header') ?>
    <?php 
       $categories[-1] = '<create new>';
       $categories = array_reverse($categories, true);
       ?>
    <?= $form->field($model, 'categoryId')->dropDownList($categories,['id' => 'categoryIdInput'])->label("Category") ?>
    <div id="categoryInputs">
       <?= $form->field($categoryModel, 'slug')->label("Category slug") ?>
       <?= $form->field($categoryModel, 'name')->label("Category name") ?>
       <?php 
       $categoriesCopy = $categories;
       $categoriesCopy[-1] = '<no parent>';
       ?>
       <?= $form->field($categoryModel, 'parentId')->dropDownList($categoriesCopy,['id' => 'categoryIdInput'])->label("Parent Category")  ?>
       <?= $form->field($categoryModel, 'accessibilityStatus')->dropDownList([0 => "Allowed to all", 1 => "Allowed by link", 
        2 => "Allowed to authorized", 3 => "Allowed to admins"])->label("Category accessibility status") ?>
    </div>
    <?= $form->field($model, 'tags') ?>
    <?= $form->field($model, 'accessibilityStatus')->dropDownList([0 => "Allowed to all", 1 => "Allowed by link", 
        2 => "Allowed to authorized", 3 => "Allowed to admins"]) ?>
    <?= $form->field($model, 'summary') ?>
    <?= $form->field($model, 'description')->textarea() ?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Create Page', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
<?php ActiveForm::end() ?>
<script type="text/javascript">  
    $(document).ready(function() {
        
        $('#categoryIdInput').change(function(){
            if($('#categoryIdInput').val() == -1){
                $('#categoryInputs').show();
            } else {
                $('#categoryInputs').hide();
            }
        });
        $('#categoryIdInput').change();
    });
</script>
