<?php

namespace Database\Seeders;

use App\Domain\Enum\Situation;
use Illuminate\Database\Seeder;

class create_situation_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Situation::values() as $situation) {
            if (empty($situation->getName())) {
                continue;
            }

            \App\Models\Situation::create([
                'id' => $situation->getValue(),
                'name' => $situation->getName()
            ]);
        }
    }
}
