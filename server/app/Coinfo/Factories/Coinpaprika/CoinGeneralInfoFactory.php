<?php

declare(strict_types=1);

namespace App\Coinfo\Factories\Coinpaprika;

use App\Coinfo\Types\CoinGeneralInfo;
use App\Coinfo\Types\Whitepaper;
use Carbon\Carbon;

final class CoinGeneralInfoFactory
{
    public static function create(array $data): CoinGeneralInfo
    {
        return new CoinGeneralInfo([
            'name' => $data['name'],
            'symbol' => $data['symbol'],
            'rank' => $data['rank'],
            'isNew' => $data['is_new'],
            'isActive' => $data['is_active'],
            'type' => $data['type'],
            'description' => $data['description'],
            'isOpenSource' => $data['open_source'],
            'startedAt' => Carbon::parse($data['started_at']),
            'developmentStatus' => $data['development_status'],
            'hasHardwareWallet' => $data['hardware_wallet'],
            'proofType' => $data['proof_type'],
            'orgStructure' => $data['org_structure'],
            'hashAlgorithm' => $data['hash_algorithm'],
            'links' => LinkCollectionFactory::create($data['links_extended']),
            'whitepaper' => new Whitepaper([
                'link' => $data['whitepaper']['link'],
                'thumbnail' => $data['whitepaper']['thumbnail'],
            ])
        ]);
    }
}
