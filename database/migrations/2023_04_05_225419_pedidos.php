<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            
            $table->engine="InnoDB";

            $table->bigIncrements('id');
            
            $table->bigInteger('numero_pedido');
            $table->bigInteger('id_producto')->unsigned();
            $table->integer('cantidad');
            $table->decimal('precio_unitario');
            $table->decimal('precio_total');
            $table->string('status');

            $table->timestamps();
            
            $table->foreign('id_producto')->references('id')->on('productos')->onDelete("cascade");
        });
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
};
