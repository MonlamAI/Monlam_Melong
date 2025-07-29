<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixAllowedCategoriesEncoding extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-allowed-categories-encoding';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix encoding of allowed_categories values in the database to properly display Tibetan characters';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix allowed_categories encoding...');
        
        // Get all users with allowed_categories
        $users = User::whereNotNull('allowed_categories')->get();
        
        $this->info("Found {$users->count()} users with allowed_categories data.");
        
        $fixed = 0;
        
        foreach ($users as $user) {
            // Get raw value directly from database to see the actual stored value
            $rawValue = DB::table('users')->where('id', $user->id)->value('allowed_categories');
            
            // Skip if null
            if (is_null($rawValue)) {
                continue;
            }
            
            // Decode with regular JSON_DECODE to get the array with unicode escapes resolved
            $decoded = json_decode($rawValue, true);
            
            if (!is_array($decoded)) {
                $this->warn("Could not decode allowed_categories for user ID {$user->id}");
                continue;
            }
            
            // Encode back with JSON_UNESCAPED_UNICODE to store the actual Tibetan characters
            DB::table('users')->where('id', $user->id)->update([
                'allowed_categories' => json_encode($decoded, JSON_UNESCAPED_UNICODE)
            ]);
            
            // Display what was fixed
            $this->line("User ID {$user->id}: Changed from {$rawValue} to " . json_encode($decoded, JSON_UNESCAPED_UNICODE));
            
            $fixed++;
        }
        
        $this->info("Fixed encoding for {$fixed} users.");
        $this->info('Completed!');
        
        return Command::SUCCESS;
    }
}
