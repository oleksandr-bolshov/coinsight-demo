<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Http\Exceptions\InvalidToken;
use Carbon\CarbonImmutable;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\ValidAt;

final class TokenService
{
    const ACCESS_TOKEN_AUDIENCE = "auth:access";
    const REFRESH_TOKEN_AUDIENCE = "auth:refresh";

    private Configuration $config;

    public function __construct()
    {
        $this->config = Configuration::forSymmetricSigner(
            new Sha256(),
            new Key((string) config('token.token_secret'))
        );
    }

    public function generateAccessToken(int $sessionId): string
    {
        $issuedAt = CarbonImmutable::now();
        $expireAt = $issuedAt->addDays(config("token.access_expiration_time")["days"]);

        return (string) $this->config
            ->createBuilder()
            ->issuedAt($issuedAt)
            ->expiresAt($expireAt)
            ->withClaim("sid", $sessionId)
            ->permittedFor(self::ACCESS_TOKEN_AUDIENCE)
            ->getToken($this->config->getSigner(), $this->config->getSigningKey());
    }

    public function getSessionIdFromAccessToken(string $token): int
    {
        $token = $this->config->getParser()->parse($token);

        $this->config->setValidationConstraints(
            new PermittedFor(self::ACCESS_TOKEN_AUDIENCE),
            new ValidAt(new FrozenClock(CarbonImmutable::now()))
        );

        $constraints = $this->config->getValidationConstraints();

        try {
            $this->config->getValidator()->assert($token, ...$constraints);
        } catch (\Lcobucci\JWT\Validation\InvalidToken $exception) {
            throw new InvalidToken($exception->getMessage());
        }

        if (!$token->claims()->has('sid')) {
            throw new InvalidToken("Claim sid is missing.");
        }

        return $token->claims()->get('sid');
    }

    public function generateRefreshToken(int $sessionId): string
    {
        $issuedAt = CarbonImmutable::now();

        return (string) $this->config
            ->createBuilder()
            ->issuedAt($issuedAt)
            ->withClaim("sid", $sessionId)
            ->permittedFor(self::REFRESH_TOKEN_AUDIENCE)
            ->getToken($this->config->getSigner(), $this->config->getSigningKey());
    }

    public function getSessionIdFromRefreshToken(string $token): int
    {
        $token = $this->config->getParser()->parse($token);

        $this->config->setValidationConstraints(
            new PermittedFor(self::REFRESH_TOKEN_AUDIENCE),
        );

        $constraints = $this->config->getValidationConstraints();

        try {
            $this->config->getValidator()->assert($token, ...$constraints);
        } catch (\Lcobucci\JWT\Validation\InvalidToken $exception) {
            throw new InvalidToken($exception->getMessage());
        }

        if (!$token->claims()->has('sid')) {
            throw new InvalidToken("Claim sid is missing.");
        }

        return $token->claims()->get('sid');
    }
}
