<?php

use App\IdentityDocument;

class IdentityDocumentSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getTutors() as $tutor) {
            $tutor->identityDocument()->save(
                factory(IdentityDocument::class)->make()
            );
        }
    }

}
