<?php

use App\Domain\Authentication\Enums\Role;
use App\Domain\User\Entities\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique()->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('phone_e164')->unique()->nullable();
            $table->string('password');

            $table->foreignUuid('avatar_id')->nullable()->constrained('images')->nullOnDelete();
            $table->foreignId('language_id')->nullable()->constrained('languages')->nullOnDelete();

            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('position')->nullable();
            $table->string('status');

            $table->rememberToken();

            $table->timestamp('born_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('password_changed_at')->nullable();
            $table->timestamp('banned_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $this->createUsers();
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

    private function createUsers()
    {
        // root user
        $user = User::create([
            'username'       => 'root',
            'email'          => 'root@goldcarrot.ru',
            'password'       => Hash::make('root'),
            'remember_token' => Str::random(60),
            'status'         => 'active',
        ]);
        $user->assignRole(Role::ROOT);

        // admin user
        $user = User::create([
            'username'       => 'admin',
            'email'          => 'admin@goldcarrot.ru',
            'password'       => Hash::make('admin'),
            'remember_token' => Str::random(60),
            'status'         => 'active',
        ]);
        $user->assignRole(Role::ADMIN);
    }
}
