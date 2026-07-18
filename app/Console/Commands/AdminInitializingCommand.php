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
        $existingCompany = Company::query()
            ->where('name', 'Administration')
            ->first();

        if ($existingCompany) {
            $this->warn('Компания администрации уже существует.');
            $this->line('Код доступа: ' . $existingCompany->code);
            return self::SUCCESS;
        }

        $company = Company::create([
            'name' => 'Administration',
        ]);

        $this->info('Создана компания администрации.');
        $this->line('Код доступа: ' . $company->code);

        return self::SUCCESS;
    }
}
