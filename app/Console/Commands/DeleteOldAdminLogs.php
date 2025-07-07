<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteOldAdminLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adminlogs:delete-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Untuk menghapus log aktivitas admin yang sudah melebihi 30 hari';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deleted = DB::table('logs_admin')
            ->where('created_at', '<', now()->subDays(30))
            ->delete();

        $this->info("Deleted $deleted old admin logs.");
    }
}
