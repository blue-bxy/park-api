<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRefundsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('user_refunds', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id');
			$table->foreign('user_id')
				->on('users')
				->references('id')
				->onDelete('cascade');
			$table->unsignedBigInteger('order_id')->comment('订单ID');
            $table->foreign('order_id')->on('user_orders')->references('id')->onDelete('cascade');
			$table->unsignedInteger('amount')->default(0)->comment('订单金额');
			$table->unsignedInteger('refunded_amount')->default(0)->comment('退款金额');
			$table->string('transfer_account')->nullable()->comment('转账账户');
			$table->integer('type')->default(1)->comment('退款类型：1-原路退还 2-转账');
			$table->string('refund_no')->nullable()->index()->comment('退款订单号');
			$table->string('refund_id')->nullable()->index()->comment('服务商退款单号');
			$table->text('reason')->nullable()->comment('退款原因');
			$table->text('remarks')->nullable()->comment('备注');
			$table->timestamp('refunded_at')->nullable();
			$table->timestamp('failed_at')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('user_refunds');
	}
}
