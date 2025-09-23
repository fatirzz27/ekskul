<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PhpParser\Node\NullableType;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Data dasar user
            $table->string('name');
            $table->string('email')->unique();

            // Email verification bawaan Laravel
            $table->timestamp('email_verified_at')->nullable();

            // Password
            $table->string('password');

            // Foto profil user
            $table->string('profile_photo')->nullable();

            // Role user (admin, pembina, siswa)
            $table->enum('role', ['admin', 'pembina', 'siswa'])->default('siswa');

            // Token untuk remember me
            $table->rememberToken();

            // created_at & updated_at
            $table->timestamps();
            
        });
    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
