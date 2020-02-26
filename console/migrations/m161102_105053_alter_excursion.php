<?php

use console\models\db\Migration;

class m161102_105053_alter_excursion extends Migration
{
    public function up()
    {
        /** create excursion__language */
        $this->createTable('{{%excursion__language}}', [
            'excursion_id' => $this->integer()->notNull(),
            'language_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey(null, '{{%excursion__language}}', ['excursion_id', 'language_id']);
        $this->addForeignKey('fk-excursion__language-excursion_id', '{{%excursion__language}}', 'excursion_id', '{{%excursion}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-excursion__language-language_id', '{{%excursion__language}}', 'language_id', '{{%language}}', 'id', 'CASCADE');
        /** ************************** */

        /** create excursion__sight */
        $this->createTable('{{%excursion__sight}}', [
            'excursion_id' => $this->integer()->notNull(),
            'sight_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey(null, '{{%excursion__sight}}', ['excursion_id', 'sight_id']);
        $this->addForeignKey('fk-excursion__sight-excursion_id', '{{%excursion__sight}}', 'excursion_id', '{{%excursion}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-excursion__sight-sight_id', '{{%excursion__sight}}', 'sight_id', '{{%sight}}', 'id', 'CASCADE');
        /** ************************** */

        /** create excursion_type */
        $this->createTable('{{%excursion_type}}', [
            'id' => $this->primaryKey(),
        ]);
        $this->createTable('{{%excursion_type_translation}}', [
            'excursion_type_id' => $this->integer()->notNull(),
            'language_code' => $this->string(16)->notNull(),
            'name' => $this->string()->notNull()->unique(),
        ]);
        $this->addPrimaryKey(null, '{{%excursion_type_translation}}', ['excursion_type_id', 'language_code']);
        /** ********************* */

        /** create excursion__excursion_type */
        $this->createTable('{{%excursion__excursion_type}}', [
            'excursion_id' => $this->integer()->notNull(),
            'excursion_type_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey(null, '{{%excursion__excursion_type}}', ['excursion_id', 'excursion_type_id']);
        $this->addForeignKey('fk-excursion__excursion_type-excursion_id', '{{%excursion__excursion_type}}', 'excursion_id', '{{%excursion}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-excursion__excursion_type-excursion_type_id', '{{%excursion__excursion_type}}', 'excursion_type_id', '{{%excursion_type}}', 'id', 'CASCADE');
        /** ************************** */

        /** create excursion_theme */
        $this->createTable('{{%excursion_theme}}', [
            'id' => $this->primaryKey(),
        ]);
        $this->createTable('{{%excursion_theme_translation}}', [
            'excursion_theme_id' => $this->integer()->notNull(),
            'language_code' => $this->string(16)->notNull(),
            'name' => $this->string()->notNull()->unique(),
        ]);
        $this->addPrimaryKey(null, '{{%excursion_theme_translation}}', ['excursion_theme_id', 'language_code']);
        /** ********************* */

        /** create excursion__excursion_theme */
        $this->createTable('{{%excursion__excursion_theme}}', [
            'excursion_id' => $this->integer()->notNull(),
            'excursion_theme_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey(null, '{{%excursion__excursion_theme}}', ['excursion_id', 'excursion_theme_id']);
        $this->addForeignKey('fk-excursion__excursion_theme-excursion_id', '{{%excursion__excursion_theme}}', 'excursion_id', '{{%excursion}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-excursion__excursion_theme-excursion_theme_id', '{{%excursion__excursion_theme}}', 'excursion_theme_id', '{{%excursion_theme}}', 'id', 'CASCADE');
        /** ************************** */
    }

    public function down()
    {
        /** drop excursion__language */
        $this->dropForeignKey('fk-excursion__language-excursion_id', '{{%excursion__language}}');
        $this->dropForeignKey('fk-excursion__language-language_id', '{{%excursion__language}}');
        $this->dropTable('{{%excursion__language}}');
        /** ******************** */

        /** drop excursion__sight */
        $this->dropForeignKey('fk-excursion__sight-excursion_id', '{{%excursion__sight}}');
        $this->dropForeignKey('fk-excursion__sight-sight_id', '{{%excursion__sight}}');
        $this->dropTable('{{%excursion__sight}}');
        /** ******************** */

        /** drop excursion__excursion_type */
        $this->dropForeignKey('fk-excursion__excursion_type-excursion_id', '{{%excursion__excursion_type}}');
        $this->dropForeignKey('fk-excursion__excursion_type-excursion_type_id', '{{%excursion__excursion_type}}');
        $this->dropTable('{{%excursion__excursion_type}}');
        /** ************************ */

        /** drop excursion_type */
        $this->dropTable('{{%excursion_type}}');
        $this->dropTable('{{%excursion_type_translation}}');
        /** ****************** */

        /** drop excursion__excursion_theme */
        $this->dropForeignKey('fk-excursion__excursion_theme-excursion_id', '{{%excursion__excursion_theme}}');
        $this->dropForeignKey('fk-excursion__excursion_theme-excursion_theme_id', '{{%excursion__excursion_theme}}');
        $this->dropTable('{{%excursion__excursion_theme}}');
        /** ************************ */

        /** drop excursion_theme */
        $this->dropTable('{{%excursion_theme}}');
        $this->dropTable('{{%excursion_theme_translation}}');
        /** ****************** */
    }
}
