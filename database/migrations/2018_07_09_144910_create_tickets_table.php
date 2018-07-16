<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Ticket;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\User;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists(Ticket::TABLE);

        Schema::create(Ticket::TABLE, function (Blueprint $table) {
            $table->increments(Ticket::COLUMN_ID);
            $table->uuid(Ticket::COLUMN_TICKET_REFERENCE);
            $table->integer(Ticket::COLUMN_PRICE);
            $table->unsignedInteger(Ticket::COLUMN_PAYMENT_ID);
            $table->unsignedInteger(Ticket::COLUMN_BOOKING_ID);
            $table->enum(Ticket::COLUMN_STATUS,Ticket::STATUSES);
            $table->unsignedInteger(Ticket::COLUMN_USER_ID)->nullable();
            $table->timestamps();

            $table->foreign(Ticket::COLUMN_USER_ID)->references(User::COLUMN_ID)->on(User::TABLE)->onDelete('cascade');
            $table->foreign(Ticket::COLUMN_PAYMENT_ID)->references(BookingPayment::COLUMN_ID)->on(BookingPayment::TABLE)->onDelete('cascade');
            $table->foreign(Ticket::COLUMN_BOOKING_ID)->references(Booking::COLUMN_ID)->on(Booking::TABLE)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Ticket::TABLE);

        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('traveller_name');
            $table->unsignedInteger('timetable_id');
            $table->enum('ticket_type',['Child','Adult'])->default('Adult');
            $table->integer('price')->default(0);
            $table->boolean('status')->default(1);
            $table->unsignedInteger('user_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
