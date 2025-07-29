<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite doesn't support modifying columns directly, so we need to recreate the table
        // This approach works for both SQLite and other databases
        
        // Get the connection
        $connection = Schema::getConnection();
        
        // First, create a new temporary table with the updated schema
        if ($connection->getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=off');
            
            // Create a new temporary table with the updated schema
            Schema::create('users_new', function (Blueprint $table) {
                // Copy all columns from original users table
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
                // The updated enum with all roles
                $table->enum('role', ['admin', 'chief_editor', 'editor', 'benchmark_editor', 'reviewer'])->default('editor');
                // Include any other columns that have been added to users table
                $table->json('allowed_categories')->nullable();
            });
            
            // Copy all data from the old table to the new table
            $users = DB::table('users')->get();
            foreach ($users as $user) {
                $data = (array) $user;
                // If the user has an invalid role, set it to 'editor'
                if (!in_array($data['role'], ['admin', 'chief_editor', 'editor', 'benchmark_editor', 'reviewer'])) {
                    $data['role'] = 'editor';
                }
                DB::table('users_new')->insert($data);
            }
            
            // Drop the old table and rename the new one
            Schema::drop('users');
            Schema::rename('users_new', 'users');
            
            DB::statement('PRAGMA foreign_keys=on');
        } else {
            // For databases that support modifying columns (MySQL, PostgreSQL)
            // We can modify the enum directly
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'chief_editor', 'editor', 'benchmark_editor', 'reviewer') NOT NULL DEFAULT 'editor'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get the connection
        $connection = Schema::getConnection();
        
        // Revert to original enum values
        if ($connection->getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=off');
            
            // Create a new temporary table with the original schema
            Schema::create('users_old', function (Blueprint $table) {
                // Copy all columns from current users table
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
                // The original enum with limited roles
                $table->enum('role', ['admin', 'editor', 'reviewer'])->default('editor');
                // Include any other columns
                $table->json('allowed_categories')->nullable();
            });
            
            // Copy all data from the current table to the old table structure
            $users = DB::table('users')->get();
            foreach ($users as $user) {
                $data = (array) $user;
                // Convert any new roles to the closest matching old role
                if ($data['role'] === 'chief_editor' || $data['role'] === 'benchmark_editor') {
                    $data['role'] = 'editor';
                }
                DB::table('users_old')->insert($data);
            }
            
            // Drop the current table and rename the old one
            Schema::drop('users');
            Schema::rename('users_old', 'users');
            
            DB::statement('PRAGMA foreign_keys=on');
        } else {
            // For databases that support modifying columns
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'editor', 'reviewer') NOT NULL DEFAULT 'editor'");
        }
    }
};
