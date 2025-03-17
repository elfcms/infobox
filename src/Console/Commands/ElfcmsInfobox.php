<?php

namespace Elfcms\Infobox\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ElfcmsInfobox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elfcms:infobox {action=install}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Infobox Module for ELF CMS';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            \DB::connection()->getPDO();
            // go
        } catch (\Exception $e) {
            $this->error('No connection to the database');
            $this->line('Check connection parameters in .env file and repeat command');
            return false;
        }

        $this->line('Publishing the module ELF CMS: Infobox');
        $resultCode = Artisan::call('elfcms:publish infobox');
        if ($resultCode == 0) {
            $this->info('OK');
        } else {
            $this->error('Publishing completed with error ' . $resultCode);
            return false;
        }
        $resultCode = false;

        $this->line('Creating database tables');
        $resultCode = Artisan::call('migrate');
        if ($resultCode == 0) {
            $this->info('OK');
        } else {
            $this->error('Table creating completed with error ' . $resultCode);
            return false;
        }
        $resultCode = false;

        $this->info('Installation completed successfully');
    }
}
