<?php

namespace DMT\Test\WebservicesNl\Client;

use Symfony\Component\Dotenv\Dotenv;

/**
 * Trait LoadCredentialsTrait
 *
 * @package DMT\WebservicesNl\Client
 */
trait LoadCredentialsTrait
{
    public function loadCredentials(): array
    {
        (new Dotenv())->load(dirname(__DIR__, 1) . '/.env');

        return [
            'username' => getenv('WS_USER'),
            'password' => getenv('WS_PASS')
        ];
    }
}
