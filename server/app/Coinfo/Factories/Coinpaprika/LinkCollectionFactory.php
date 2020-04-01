<?php

declare(strict_types=1);

namespace App\Coinfo\Factories\Coinpaprika;

use App\Coinfo\Types\Link;
use App\Coinfo\Types\LinkCollection;

final class LinkCollectionFactory
{
    public static function create(array $data): LinkCollection
    {
        $arrayOfLinks = array_map(
            fn ($link) => new Link([
                'url' => $link['url'],
                'type' => $link['type']
            ]),
            $data
        );
        return new LinkCollection($arrayOfLinks);
    }
}
