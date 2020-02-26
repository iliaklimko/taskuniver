<?php

use console\models\db\Migration;

class m160902_122246_country_city_init extends Migration
{
    public function up()
    {
        /** create country */
        $this->createTable('{{%country}}', [
            'id' => $this->primaryKey(),
        ]);
        $this->createTable('{{%country_translation}}', [
            'country_id' => $this->integer()->notNull(),
            'language_code' => $this->string(16)->notNull(),
            'name' => $this->string()->notNull()->unique(),
        ]);
        $this->addPrimaryKey(null, '{{%country_translation}}', ['country_id', 'language_code']);
        /** ************** */

        /** create city */
        $this->createTable('{{%city}}', [
            'id' => $this->primaryKey(),
            'country_id' => $this->integer()
        ]);
        $this->addForeignKey('fk_city_country', '{{%city}}', 'country_id', '{{%country}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%city_translation}}', [
            'city_id' => $this->integer()->notNull(),
            'language_code' => $this->string(16)->notNull(),
            'name' => $this->string()->notNull()->unique(),
        ]);
        $this->addPrimaryKey(null, '{{%city_translation}}', ['city_id', 'language_code']);
        /** *********** */
    }

    public function down()
    {
        /** drop city */
        $this->dropTable('{{%city}}');
        $this->dropTable('{{%city_translation}}');
        /** ********* */

        /** drop country */
        $this->dropTable('{{%country}}');
        $this->dropTable('{{%country_translation}}');
        /** ************ */
    }
}
