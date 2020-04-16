<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Coinfo\Client;
use App\Coinfo\Types\CoinOverview;
use App\Domain\Markets\Models\Coin;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class UpdateCoins extends Command
{
    protected $signature = 'coinsight:update-coins';

    protected $description = 'Update coins list';

    private array $coinErrors = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(Client $client)
    {
        $retrievedCoins = collect($client->markets());
        $this->line("\n" . $retrievedCoins->count() . " coins loaded.");

        $missingCoins = $this->getMissingCoins($retrievedCoins);
        $missingCoinsCount = $missingCoins->count();

        $this->line("Found {$missingCoinsCount} new coin(s).");

        if ($missingCoinsCount === 0) {
            return;
        }

        $this->line("\nSaving new coin(s) to the database:");

        $bar = $this->output->createProgressBar($missingCoinsCount);
        $bar->start();

        $coinsRecords = [];

        foreach ($missingCoins as $missingCoin) {
            try {
                $coinsRecords[] = $this->makeCoinRecord($missingCoin);
            } catch (Exception $exception) {
                $this->addError($missingCoin, $exception);
            }

            sleep(1);

            $bar->advance();
        }

        DB::table('coins')->insert($coinsRecords);

        $bar->finish();
        $this->line("\n");

        if (count($this->coinErrors) === 0) {
            $this->info("All coins successfully stored to the database.");
            $this->info("No errors occurred.");
        } else {
            $savedCount = $missingCoinsCount - count($this->coinErrors);
            $this->info("{$savedCount} coin(s) stored to the database.");
            $this->line("\nOccurred error(s): ");
            $this->table(['Coin name', 'Error'], $this->coinErrors);
        }
    }

    private function getMissingCoins(Collection $retrievedCoins): Collection
    {
        $names = $retrievedCoins->pluck('name');
        $persistedCoins = Coin::whereIn('name', $names)->get()->toBase();

        return $retrievedCoins->filter(fn (CoinOverview $coin) =>
            !$persistedCoins->contains('name', $coin->name)
        );
    }

    private function makeCoinRecord(CoinOverview $coinOverview): array
    {
        try {
            $iconUrl = $this->saveIcon($coinOverview);
        } catch (Exception $exception) {
            $this->addError($coinOverview, $exception);
        }

        return [
            'name' => $coinOverview->name,
            'symbol' => $coinOverview->symbol,
            'icon' => $iconUrl ?? null,
            'created_at' => $now = Carbon::now(),
            'updated_at' => $now,
        ];
    }

    private function saveIcon(CoinOverview $coinOverview): string
    {
        $filename = Str::slug($coinOverview->name) . '.png';
        $filePath = 'public/' . $filename;

        if (Storage::missing($filePath)) {
            Storage::put($filePath, file_get_contents($coinOverview->icon));
        }

        return Storage::url($filename);
    }

    private function addError(CoinOverview $coin, Exception $e): void
    {
        $this->coinErrors[] = [
            'coin_name' => $coin->name,
            'error' => $e->getMessage(),
        ];
    }
}
