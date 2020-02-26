<?php

use console\models\db\Migration;

class m161006_120728_alter_user extends Migration
{
    public function up()
    {
        /** create user__language */
        $this->createTable('{{%user__language}}', [
            'user_id' => $this->integer()->notNull(),
            'language_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey(null, '{{%user__language}}', ['user_id', 'language_id']);
        $this->addForeignKey('fk-user__language-user_id', '{{%user__language}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-user__language-language_id', '{{%user__language}}', 'language_id', '{{%language}}', 'id', 'CASCADE');
        /** ********************* */

        /** create user__city */
        $this->createTable('{{%user__city}}', [
            'user_id' => $this->integer()->notNull(),
            'city_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey(null, '{{%user__city}}', ['user_id', 'city_id']);
        $this->addForeignKey('fk-user__city-user_id', '{{%user__city}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-user__city-city_id', '{{%user__city}}', 'city_id', '{{%city}}', 'id', 'CASCADE');
        /** ***************** */

        $this->addColumn('{{%user}}', 'phone', $this->string());
        $this->addColumn('{{%user}}', 'bio', $this->text());
        $this->addColumn('{{%user}}', 'avatar', $this->string());
    }

    public function down()
    {
        /** drop user__language */
        $this->dropForeignKey('fk-user__language-user_id', '{{%user__language}}');
        $this->dropForeignKey('fk-user__language-language_id', '{{%user__language}}');
        $this->dropTable('{{%user__language}}');
        /** ******************* */

        /** drop user__city */
        $this->dropForeignKey('fk-user__city-user_id', '{{%user__city}}');
        $this->dropForeignKey('fk-user__city-city_id', '{{%user__city}}');
        $this->dropTable('{{%user__city}}');
        /** ***************** */

        $this->dropColumn('{{%user}}', 'phone');
        $this->dropColumn('{{%user}}', 'bio');
        $this->dropColumn('{{%user}}', 'avatar');
    }
}
