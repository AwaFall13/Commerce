<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Ajouter les champs manquants seulement s'ils n'existent pas
            if (!Schema::hasColumn('orders', 'order_number')) {
                $table->string('order_number')->unique()->after('id');
            }
            if (!Schema::hasColumn('orders', 'status')) {
                $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending')->after('user_id');
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->enum('payment_method', ['online', 'cash_on_delivery'])->after('status');
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending')->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->after('payment_status');
            }
            
            // Adresse de livraison
            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->after('total_amount');
            }
            if (!Schema::hasColumn('orders', 'shipping_city')) {
                $table->string('shipping_city')->after('shipping_address');
            }
            if (!Schema::hasColumn('orders', 'shipping_postal_code')) {
                $table->string('shipping_postal_code')->after('shipping_city');
            }
            if (!Schema::hasColumn('orders', 'shipping_phone')) {
                $table->string('shipping_phone')->after('shipping_postal_code');
            }
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('shipping_phone');
            }
            
            // Dates de suivi
            if (!Schema::hasColumn('orders', 'shipped_at')) {
                $table->timestamp('shipped_at')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('orders', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            }
            
            // Supprimer les anciens champs si ils existent
            if (Schema::hasColumn('orders', 'is_paid')) {
                $table->dropColumn('is_paid');
            }
            if (Schema::hasColumn('orders', 'total')) {
                $table->dropColumn('total');
            }
            if (Schema::hasColumn('orders', 'address')) {
                $table->dropColumn('address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_number',
                'status',
                'payment_method',
                'payment_status',
                'total_amount',
                'shipping_address',
                'shipping_city',
                'shipping_postal_code',
                'shipping_phone',
                'notes',
                'shipped_at',
                'delivered_at',
            ]);
        });
    }
}; 