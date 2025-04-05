<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadWordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badWords = [
           'damn', 'hell', 'ass', 'fucking', 'kingina', 'bullshit', 'bitch', 'crap', 'dick', 'fuck', 'shit', 'bastard', 'nigga', 'slut', 'tangina',
                'yawa', 'kupal', 'buang', 'boang', 'taena', 'taenamo', 'pakshet', 'pakshit', 'gago', 'leche', 'punyeta', 'lintek', 'peste', 'tanga', 'gaga',
                'puta', 'putragis', 'lintik', 'shet','pucha', 'pota' 
        ];

        foreach ($badWords as $word) {
            DB::table('bad_words')->insert([
                'word' => $word,
            ]);
        }
    }
}
