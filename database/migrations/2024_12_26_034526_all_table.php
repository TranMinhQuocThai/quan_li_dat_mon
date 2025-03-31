<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    // Schema::create('departments', function (Blueprint $table) {
    //   $table->id();
    //   $table->string('name');
    //   $table->timestamps();
    // });
    // Schema::create('employees', function (Blueprint $table) {
    //   $table->id();
    //   $table->string('name');
    //   $table->string('email')->unique();
    //   $table->string('phone');
    //   $table->foreignId('department_id')->constrained('departments');
    //   $table->timestamps();
    // });
    // Schema::create('customers', function (Blueprint $table) {
    //   $table->id();
    //   $table->string('name');
    //   $table->string('phone');
    //   $table->string('email')->unique();
    //   $table->timestamps();
    // });
    Schema::create('tables', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->integer('seats')->default(4);
      $table->enum('status', ['trống', 'có khách'])->default('trống');
      $table->timestamps();
    });
    Schema::create('food_types', function (Blueprint $table) {
      $table->id();
      $table->string('name')->unique();
      $table->timestamps();
    });
    Schema::create('food_items', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('image')->nullable();
      $table->foreignId('food_type_id')->constrained('food_types');
      $table->bigInteger('price');
      $table->text('description')->nullable();
      $table->timestamps();
    });
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->nullable()->constrained('users');
      $table->foreignId('table_id')->constrained('tables');
      $table->boolean('paid')->default(false);
      // $table->enum('status', ['đang ăn', 'đã ăn', 'đã thanh toán'])->default('đang ăn');
      $table->decimal('discount')->default(0);
      $table->timestamps();
    });
    Schema::create('order_details', function (Blueprint $table) {
      $table->id();
      $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
      $table->foreignId('food_item_id')->constrained('food_items');
      $table->integer('quantity');
      $table->bigInteger('price');
      $table->enum('status', ['chuẩn bị', 'đã nấu', 'đã ra'])->default('chuẩn bị');
      $table->timestamps();
    });
    Schema::create('ingredients', function (Blueprint $table) {
      $table->id();
      $table->string('name')->unique();
      $table->decimal('quantity', 10, 2)->default(0);
      $table->string('unit')->nullable();
      $table->timestamps();
    });
    Schema::create('food_ingredients', function (Blueprint $table) {
      $table->id();
      $table->foreignId('food_item_id')->constrained('food_items')->onDelete('cascade');
      $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('cascade');
      $table->decimal('quantity', 10, 2);
      $table->timestamps();
    });
    Schema::create('online_orders', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Liên kết với bảng users
      $table->string('phone');
      $table->string('address'); // Thêm cột địa chỉ
      $table->enum('status', ['chờ xác nhận', 'đã xác nhận', 'không nhận', 'đã giao', 'đã hủy'])->default('chờ xác nhận');
      $table->text('reason')->nullable();
      $table->boolean('paid')->default(false);
      $table->timestamps();
    });
    Schema::create('online_orders_items', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('order_id');
      $table->unsignedBigInteger('food_item_id');
      $table->integer('quantity');
      $table->decimal('price', 10, 2);
      $table->timestamps();

      $table->foreign('order_id')->references('id')->on('online_orders')->onDelete('cascade');
      $table->foreign('food_item_id')->references('id')->on('food_items')->onDelete('cascade');
    });


  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    //
  }
};
