<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Coinfo\Client;
use App\Coinfo\Types\CoinProfile;
use App\Domain\Markets\Entities\CoinProfile as CoinProfileEntity;
use App\Domain\Markets\Models\CoinProfile as CoinProfileModel;
use App\Domain\Markets\Models\CoinLink;
use App\Domain\Markets\Services\CoinService;

final class GetProfileInteractor
{
    private Client $client;
    private CoinService $coinService;

    public function __construct(Client $client, CoinService $coinService)
    {
        $this->client = $client;
        $this->coinService = $coinService;
    }

    public function execute(GetProfileRequest $request): GetProfileResponse
    {
        $coin = $this->coinService->getById($request->id, ['profile', 'links']);

        if ($coin->profile === null) {
            $coinProfile = $this->client->coinProfile($coin->name);
            $coin->profile()->save($this->makeCoinProfile($coinProfile));
            $coin->links()->saveMany($this->makeCoinLinks($coinProfile));
            $coin->refresh();
        }

        return new GetProfileResponse([
            'profile' => CoinProfileEntity::fromModel($coin)
        ]);
    }

    private function makeCoinProfile(CoinProfile $coinProfile): CoinProfileModel
    {
        $coinProfileModel = new CoinProfileModel();

        $coinProfileModel->tagline = $coinProfile->tagline;
        $coinProfileModel->description = $coinProfile->description;
        $coinProfileModel->type = $coinProfile->type;
        $coinProfileModel->genesis_date = $coinProfile->genesisDate;
        $coinProfileModel->consensus_mechanism = $coinProfile->consensusMechanism;
        $coinProfileModel->hashing_algorithm = $coinProfile->hashingAlgorithm;

        return $coinProfileModel;
    }

    private function makeCoinLinks(CoinProfile $coinProfile): array
    {
        $coinLinks = [];

        foreach ($coinProfile->links as $link) {
            $coinLink = new CoinLink();
            $coinLink->type = $link->type;
            $coinLink->link = $link->link;
            $coinLinks[] = $coinLink;
        }

        if (!$coinProfile->blockExplorers) {
            return $coinLinks;
        }

        foreach ($coinProfile->blockExplorers as $blockExplorer) {
            $coinLink = new CoinLink();
            $coinLink->type = $blockExplorer->type;
            $coinLink->link = $blockExplorer->link;
            $coinLinks[] = $coinLink;
        }

        return $coinLinks;
    }
}
