<?php

use console\models\db\Migration;

class m161016_234442_add_similar_posts extends Migration
{
    public function up()
    {
        $this->createTable('{{%post__similar}}', [
            'post_id' => $this->integer()->notNull(),
            'similar_post_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey(null, '{{%post__similar}}', ['post_id', 'similar_post_id']);
        $this->addForeignKey('fk-post__similar-post_id', '{{%post__similar}}', 'post_id', '{{%post}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-post__similar-similar_post_id', '{{%post__similar}}', 'similar_post_id', '{{%post}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-post__similar-post_id', '{{%post__similar}}');
        $this->dropForeignKey('fk-post__similar-similar_post_id', '{{%post__similar}}');
        $this->dropTable('{{%post__similar}}');
    }
}
