<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\ThirdpartServer;

class CreateTableThirdPartServers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('third_part_servers', function (Blueprint $table) {
            $table->increments(ThirdpartServer::COLUMN_ID);
            $table->string(ThirdpartServer::COLUMN_IP_ADDRESS);
            $table->string(ThirdpartServer::COLUMN_SERVER_NAME);
            $table->string(ThirdpartServer::COLUMN_PORT);
            $table->boolean(ThirdpartServer::COLUMN_AVAILABILITY_STATUS);
            $table->timestamps();
        });

        DB::table(ThirdpartServer::TABLE)->insert(
            [
                ThirdpartServer::COLUMN_IP_ADDRESS => '41.217.203.61',
                ThirdpartServer::COLUMN_SERVER_NAME => 'Mpesa C2B server',
                ThirdpartServer::COLUMN_PORT => '30009',
                ThirdpartServer::COLUMN_AVAILABILITY_STATUS => 0
            ]
        );

        DB::table(ThirdpartServer::TABLE)->insert(
            [
                ThirdpartServer::COLUMN_IP_ADDRESS => '41.217.203.241',
                ThirdpartServer::COLUMN_SERVER_NAME => 'Mpesa B2C server',
                ThirdpartServer::COLUMN_PORT => '30009',
                ThirdpartServer::COLUMN_AVAILABILITY_STATUS => 0
            ]
        );

        DB::table(ThirdpartServer::TABLE)->insert(
            [
                ThirdpartServer::COLUMN_IP_ADDRESS => '41.223.4.174',
                ThirdpartServer::COLUMN_SERVER_NAME => 'Vodacom SMS Server',
                ThirdpartServer::COLUMN_PORT => '6691',
                ThirdpartServer::COLUMN_AVAILABILITY_STATUS => 0
            ]
        );

        DB::table(ThirdpartServer::TABLE)->insert(
            [
                ThirdpartServer::COLUMN_IP_ADDRESS => '41.222.176.143',
                ThirdpartServer::COLUMN_SERVER_NAME => 'Tigopesa B2C server production',
                ThirdpartServer::COLUMN_PORT => '8080',
                ThirdpartServer::COLUMN_AVAILABILITY_STATUS => 0
            ]
        );

        DB::table(ThirdpartServer::TABLE)->insert(
            [
                ThirdpartServer::COLUMN_IP_ADDRESS => '41.222.182.51',
                ThirdpartServer::COLUMN_SERVER_NAME => 'Tigo SMS Server',
                ThirdpartServer::COLUMN_PORT => '10501',
                ThirdpartServer::COLUMN_AVAILABILITY_STATUS => 0
            ]
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('third_part_servers');
    }
}
