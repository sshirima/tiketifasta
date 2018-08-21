<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Admin;

class AddDefaultAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insert default admin account
        DB::table(Admin::TABLE)->insert(
            array(
                Admin::COLUMN_FIRST_NAME => 'Root',
                Admin::COLUMN_LAST_NAME => 'User',
                Admin::COLUMN_EMAIL => 'admin@tiketifasta.com',
                Admin::COLUMN_PASSWORD => bcrypt('password')
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
