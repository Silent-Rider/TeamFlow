<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('admin:init')]
#[Description('Creating a special company for admin registration')]
class AdminInitializingCommand extends Command
{

    public function handle()
    {
        $company = Company::create([
            'name' => 'Administration'
        ]);

        $this->line('Создана компания администрации.');
        $this->line('Код доступа: ' . $company->code);
    }
}
