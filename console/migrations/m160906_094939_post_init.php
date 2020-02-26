<?php

use console\models\db\Migration;

class m160906_094939_post_init extends Migration
{
    public function up()
    {
        /** create post_category */
        $this->createTable('{{%post_category}}', [
            'id' => $this->primaryKey(),
        ]);
        $this->createTable('{{%post_category_translation}}', [
            'post_category_id' => $this->integer()->notNull(),
            'language_code' => $this->string(16)->notNull(),
            'name' => $this->string()->notNull()->unique(),
        ]);
        $this->addPrimaryKey(null, '{{%post_category_translation}}', ['post_category_id', 'language_code']);
        /** *********** */

        /** create post */
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'language_code' => $this->string(16)->notNull(),
            'title' => $this->string()->notNull()->unique(),
            'body' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        /** *********** */

        /** create post__target_audience */
        $this->createTable('{{%post__target_audience}}', [
            'post_id' => $this->integer()->notNull(),
            'target_audience_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey(null, '{{%post__target_audience}}', ['post_id', 'target_audience_id']);
        $this->addForeignKey('fk-post__target_audience-post_id', '{{%post__target_audience}}', 'post_id', '{{%post}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-post__target_audience-target_audience_id', '{{%post__target_audience}}', 'target_audience_id', '{{%target_audience}}', 'id', 'CASCADE');
        /** **************************** */

        /** create post__post_category */
        $this->createTable('{{%post__post_category}}', [
            'post_id' => $this->integer()->notNull(),
            'post_category_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey(null, '{{%post__post_category}}', ['post_id', 'post_category_id']);
        $this->addForeignKey('fk-post__post_category-post_id', '{{%post__post_category}}', 'post_id', '{{%post}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-post__post_category-post_category_id', '{{%post__post_category}}', 'post_category_id', '{{%post_category}}', 'id', 'CASCADE');
        /** ************************** */

        /** create tag */
        $this->createTable('{{%tag}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'frequency' => $this->integer()->notNull()->defaultValue(0),
        ]);
        /** ********** */

        /** create post__tag */
        $this->createTable('{{%post__tag}}', [
            'post_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey(null, '{{%post__tag}}', ['post_id', 'tag_id']);
    }

    public function down()
    {
        /** drop post__target_audience */
        $this->dropForeignKey('fk-post__target_audience-post_id', '{{%post__target_audience}}');
        $this->dropForeignKey('fk-post__target_audience-target_audience_id', '{{%post__target_audience}}');
        $this->dropTable('{{%post__target_audience}}');
        /** ************************** */

        /** drop post__post_category */
        $this->dropForeignKey('fk-post__post_category-post_id', '{{%post__post_category}}');
        $this->dropForeignKey('fk-post__post_category-post_category_id', '{{%post__post_category}}');
        $this->dropTable('{{%post__post_category}}');
        /** ************************ */

        /** drop post */
        $this->dropTable('{{%post}}');
        /** ********* */

        /** drop post_category */
        $this->dropTable('{{%post_category}}');
        $this->dropTable('{{%post_category_translation}}');
        /** ****************** */

        /** drop post__tag */
        $this->dropTable('{{%post__tag}}');
        /** ************** */

        /** drop tag */
        $this->dropTable('{{%tag}}');
        /** ************** */
    }
}
