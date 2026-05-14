<?php

namespace App\Console\Commands;

use App\Models\Cart;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

#[Signature('carts:cleanup
{--dry-run : Show what would be deleted without actually deleting}
{--chunk=500 : Number of carts to delete per batch}')]
#[Description('Delete expired guest carts and their items from the database')]
class CleanupExpiredCarts extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $chunkSize = (int) $this->option('chunk');

        $this->info($isDryRun ? '🔍 Dry run mode — no data will be deleted' : '🗑️  Starting cleanup...');

        $query = Cart::whereNotNull('guest_token')
            ->WhereNull('user_id')
            ->where('expires_at', '<', now());

        $totalCount = $query->count();

         if ($totalCount === 0) {
            $this->info('✅ No expired carts found. Database is clean.');
            return self::SUCCESS;
        }

        $this->info("Found {$totalCount} expired cart(s).");

        if ($isDryRun) {
            $this->table(
                ['ID', 'TOKEN (first 10 chars)', 'Expired At'],
                $query->take(10)->get()->map(fn($cart) => [
                    $cart->id,
                    substr($cart->guest_token, 0, 10) . '...',
                    $cart->expires_at->diffForHumans() ?? 'No expiry',
                ])->toArray()
            );

            if ($totalCount > 10) {
                $this->info("... and " . ($totalCount - 10) . " more");
            }

            return self::SUCCESS;
        }

        $deletedCount = 0;
        $progressBar = $this->output->createProgressBar($totalCount);
        $progressBar->start();

        $query->chunkById($chunkSize, function($carts) use (&$deletedCount, $progressBar) {
            foreach($carts as $cart) {
                $cart->delete();
                $deletedCount++;
                $progressBar->advance();
            }
        });

        $progressBar->finish();
        $this->newLine(2);

        $this->info("✅ Successfully deleted {$deletedCount} expired cart(s).");

         Log::info('Expired carts cleanup completed', [
            'deleted_count' => $deletedCount,
            'executed_at' => now()->toIso8601String(),
        ]);

        return self::SUCCESS;
    }
}
