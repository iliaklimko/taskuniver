<?php

use console\models\db\Migration;

class m160921_135958_add_publication_date extends Migration
{
    public function up()
    {
        $this->addColumn('{{%post}}', 'publication_date', $this->date());
    }

    public function down()
    {
        $this->dropColumn('{{%post}}', 'publication_date');
    }
}
