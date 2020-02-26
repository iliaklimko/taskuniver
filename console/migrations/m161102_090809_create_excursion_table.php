<?php

use console\models\db\Migration;

/**
 * Handles the creation for table `excursion`.
 */
class m161102_090809_create_excursion_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        /**  create excursion */
        $this->createTable('{{%excursion}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'start_city_id' => $this->integer(),
            'current_price' => $this->money(),
            'old_price' => $this->money(),
            'duration' => $this->integer(),
            'pick_up_from_hotel' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey("fk_excursion_user", "{{%excursion}}",  "user_id", "{{%user}}", "id", "SET NULL", "CASCADE");
        $this->addForeignKey("fk_excursion_city", "{{%excursion}}",  "start_city_id", "{{%city}}", "id", "SET NULL", "CASCADE");
        $this->createTable('{{%excursion_translation}}', [
            'excursion_id' => $this->integer()->notNull(),
            'language_code' => $this->string(16)->notNull(),
            'title' => $this->string()->notNull()->unique(),
            'description' => $this->text(),
            'included_in_price' => $this->text(),
            'not_included_in_price' => $this->text(),
            'meeting_point' => $this->text(),
            'additional_info' => $this->text(),
        ]);
        $this->addPrimaryKey(null, '{{%excursion_translation}}', ['excursion_id', 'language_code']);
        $this->createTable('{{%excursion__city}}', [
            'excursion_id' => $this->integer()->notNull(),
            'city_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey(null, '{{%excursion__city}}', ['excursion_id', 'city_id']);
        $this->addForeignKey('fk-excursion__city-excursion_id', '{{%excursion__city}}', 'excursion_id', '{{%excursion}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-excursion__city-city_id', '{{%excursion__city}}', 'city_id', '{{%city}}', 'id', 'CASCADE');
        /** ***************** */

        /** create excursion__target_audience */
        $this->createTable('{{%excursion__target_audience}}', [
            'excursion_id' => $this->integer()->notNull(),
            'target_audience_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey(null, '{{%excursion__target_audience}}', ['excursion_id', 'target_audience_id']);
        $this->addForeignKey('fk-excursion__target_audience-excursion_id', '{{%excursion__target_audience}}', 'excursion_id', '{{%excursion}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-excursion__target_audience-target_audience_id', '{{%excursion__target_audience}}', 'target_audience_id', '{{%target_audience}}', 'id', 'CASCADE');
        /** ********************************* */
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        /** drop excursion__target_audience */
        $this->dropForeignKey('fk-excursion__target_audience-excursion_id', '{{%excursion__target_audience}}');
        $this->dropForeignKey('fk-excursion__target_audience-target_audience_id', '{{%excursion__target_audience}}');
        $this->dropTable('{{%excursion__target_audience}}');
        /** ******************************* */

        /** drop excursion__city */
        $this->dropForeignKey('fk-excursion__city-excursion_id', '{{%excursion__city}}');
        $this->dropForeignKey('fk-excursion__city-city_id', '{{%excursion__city}}');
        $this->dropTable('{{%excursion__city}}');
        /** ******************** */

        /** drop excursion */
        $this->dropForeignKey("fk_excursion_user", "{{%excursion}}");
        $this->dropTable('{{%excursion}}');
        $this->dropTable('{{%excursion_translation}}');
        /** ************** */
    }
}
