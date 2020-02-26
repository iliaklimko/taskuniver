<?php

use console\models\db\Migration;

class m161116_053836_add_person_number_to_excursion extends Migration
{
    public function up()
    {
        $this->addColumn('{{%excursion}}', 'person_number', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%excursion}}', 'person_number');
    }
}
