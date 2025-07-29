<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use App\Models\Tag;
use App\Models\MonlamMelongFinetuning;

class MigrateExistingCategoryTagDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Step 1: Migrate Categories
        $this->migrateCategories();
        
        // Step 2: Migrate Tags
        $this->migrateTags();
        
        // Step 3: Update Entry References
        $this->updateEntryReferences();
    }
    
    /**
     * Migrate categories from placeholder entries to categories table
     */
    private function migrateCategories()
    {
        // Get list of predefined categories
        $predefinedCategories = [
            'ཚིག་མཛོད།',
            'ཞེ་ས།',
            'བོད་སྐད།',
            'དམངས་སྐད།',
            'གཏམ་དཔེ།',
            'ལེགས་སྦྱར།'
        ];
        
        // Get unique categories from entries
        $uniqueCategories = DB::table('monlam_melong_finetuning')
                            ->select('category')
                            ->whereNotNull('category')
                            ->distinct()
                            ->pluck('category')
                            ->toArray();
        
        $this->command->info('Migrating ' . count($uniqueCategories) . ' categories...');
        
        // Create category records
        foreach ($uniqueCategories as $categoryName) {
            // Skip empty categories
            if (empty($categoryName)) {
                continue;
            }
            
            // Check if predefined
            $isPredefined = in_array($categoryName, $predefinedCategories);
            
            // Create category
            Category::create([
                'name' => $categoryName,
                'tibetan_name' => $categoryName, // Both are the same for now
                'is_predefined' => $isPredefined,
                'description' => null
            ]);
        }
        
        $this->command->info('Categories migrated successfully!');
    }
    
    /**
     * Migrate tags from placeholder entries to tags table
     */
    private function migrateTags()
    {
        // Get all entries with tags
        $entries = DB::table('monlam_melong_finetuning')
                    ->whereNotNull('tags')
                    ->where('tags', '!=', '')
                    ->get(['id', 'tags']);
        
        $uniqueTags = [];
        
        // Extract all unique tags
        foreach ($entries as $entry) {
            $tagsList = explode(',', $entry->tags);
            $tagsList = array_map('trim', $tagsList);
            
            foreach ($tagsList as $tag) {
                if (!empty($tag) && !in_array($tag, $uniqueTags)) {
                    $uniqueTags[] = $tag;
                }
            }
        }
        
        $this->command->info('Migrating ' . count($uniqueTags) . ' tags...');
        
        // Create tag records
        foreach ($uniqueTags as $tagName) {
            Tag::create([
                'name' => $tagName,
                'description' => null
            ]);
        }
        
        $this->command->info('Tags migrated successfully!');
    }
    
    /**
     * Update entries with foreign keys to categories and tags
     */
    private function updateEntryReferences()
    {
        $this->command->info('Updating entries with category references...');
        
        // Update category references
        $categories = Category::all()->keyBy('name');
        
        // Loop through each entry and update its category_id
        $entries = MonlamMelongFinetuning::whereNotNull('category')->get();
        
        foreach ($entries as $entry) {
            if (!empty($entry->category) && isset($categories[$entry->category])) {
                $entry->category_id = $categories[$entry->category]->id;
                $entry->save();
            }
        }
        
        $this->command->info('Creating tag relationships...');
        
        // Create tag relationships
        $tags = Tag::all()->keyBy('name');
        $entries = MonlamMelongFinetuning::whereNotNull('tags')->where('tags', '!=', '')->get();
        
        foreach ($entries as $entry) {
            $tagsList = explode(',', $entry->tags);
            $tagsList = array_map('trim', $tagsList);
            
            foreach ($tagsList as $tagName) {
                if (!empty($tagName) && isset($tags[$tagName])) {
                    // Add to pivot table
                    DB::table('entry_tag')->insert([
                        'entry_id' => $entry->id,
                        'tag_id' => $tags[$tagName]->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }
        
        $this->command->info('Entry references updated successfully!');
    }
}
