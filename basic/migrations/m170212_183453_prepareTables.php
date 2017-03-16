<?php

use yii\db\Migration;
use app\modules\StaticPageModule\repositories\entities\{Category, RatingItem, StaticPage, TagStaticPageMembership};
class m170212_183453_prepareTables extends Migration
{
    //$tagName;
    public $pageId;
    public function up()
    {
        $this->createTable((new ReflectionClass(new Category()))->getShortName(), [
            'id' => $this->primaryKey()->notNull(),
            'parentId' => $this->integer(),
            'slug' => $this->string(15)->notNull()->unique(),
            'name' => $this->string(30)->notNull(),
            'accessibilityStatus'=> $this->integer(1)->notNull()
        ]);
        $this->createTable((new ReflectionClass(new RatingItem()))->getShortName(), [
            'ipAddress' => $this->string(15)->notNull(),
            'pageId' => $this->integer()->notNull(),
            'rating'=> $this->integer(1)->notNull()
        ]);
        $this->createTable((new ReflectionClass(new StaticPage()))->getShortName(), [
            'id' => $this->primaryKey()->notNull(),
            'author' => $this->string(30)->notNull(),
            'slug' => $this->string(15)->notNull()->unique(),
            'header' => $this->string(30)->notNull(),
            'categoryId' => $this->integer()->notNull(),
            'accessibilityStatus'=> $this->integer(1)->notNull(),
            'dateCreated' => $this->string()->notNull(),
            'dateLastModified' => $this->string()->notNull(),
            'summary' => $this->string(250),
            'description' => $this->text(16777215)->notNull(),
            'rating'=> $this->decimal()->notNull()
        ]);
        $this->createTable((new ReflectionClass(new TagStaticPageMembership()))->getShortName(), [
            'tagName' => $this->string(30)->notNull(),
            'pageId' => $this->integer()->notNull()
        ]);
        $this->addPrimaryKey('pk-'.(new ReflectionClass(new RatingItem()))->getShortName(), (new ReflectionClass(new RatingItem()))->getShortName(), ['pageId', 'ipAddress']);
        $this->addForeignKey('fk-'.(new ReflectionClass(new TagStaticPageMembership()))->getShortName().'-page_id', 
                (new ReflectionClass(new TagStaticPageMembership()))->getShortName(), 
                'pageId',  
                (new ReflectionClass(new StaticPage()))->getShortName(),
                'id');
    }

    public function down()
    {
        echo "m170212_183453_prepareTables cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
