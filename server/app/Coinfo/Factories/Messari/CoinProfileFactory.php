<?php

declare(strict_types=1);

namespace App\Coinfo\Factories\Messari;

use App\Coinfo\Types\CoinProfile;
use App\Coinfo\Types\Link;
use App\Coinfo\Types\LinkCollection;
use Carbon\Carbon;

final class CoinProfileFactory
{
    public static function create(array $data): CoinProfile
    {
        $data = $data['data'];
        $overviewData = $data['profile']['general']['overview'];
        $economicsData = $data['profile']['economics'];
        $consensusData = $data['profile']['economics']['consensus_and_emission']['consensus'];

        return new CoinProfile([
            'name' => $data['name'],
            'symbol' => $data['symbol'],
            'tagline' => $overviewData['tagline'],
            'description' => strip_tags($overviewData['project_details']),
            'type' => $economicsData['token']['token_type'],
            'genesisDate' => self::getGenesisDate($data['profile']),
            'consensusMechanism' => $consensusData['general_consensus_mechanism'],
            'hashingAlgorithm' => $consensusData['mining_algorithm'],
            'links' => self::getLinks($data['profile']),
            'blockExplorers' => self::getBlockExplorers($data['profile']),
        ]);
    }

    private static function getGenesisDate(array $profile): ?Carbon
    {
        $genesisBlockDate = $profile['economics']['launch']['initial_distribution']['genesis_block_date'];
        return $genesisBlockDate === null ? $genesisBlockDate : Carbon::parse($genesisBlockDate);
    }

    private static function getLinks(array $profile): LinkCollection
    {
        $links = new LinkCollection();

        foreach ($profile['general']['overview']['official_links'] as $officialLink) {
            if ($officialLink['link'] === null) {
                continue;
            }

            $links[] = new Link([
                'type' => $officialLink['name'],
                'link' => $officialLink['link'],
            ]);
        }

        return $links;
    }

    private static function getBlockExplorers(array $profile): ?LinkCollection
    {
        if (!$profile['economics']['token']['block_explorers']) {
            return null;
        }

        $blockExplorers = new LinkCollection();

        foreach ($profile['economics']['token']['block_explorers'] as $blockExplorer) {
            $blockExplorers[] = new Link([
                'type' => $blockExplorer['name'],
                'link' => $blockExplorer['link'],
            ]);
        }

        return $blockExplorers;
    }
}
