<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 1/23/2019
 * Time: 3:07 PM
 */

namespace App\Traits;

use Laravel\Passport\ClientRepository;
use Laravel\Passport\Token;
use Laravel\Passport\TokenRepository;

trait PersonalAccessTokenTrait
{
    /**
     * Get personal access token for user.
     *
     * @return \Laravel\Passport\Token|null
     */
    public function getLatestToken(): ?Token
    {
        return app(TokenRepository::class)->findValidToken(
            $this,
            app(ClientRepository::class)->personalAccessClient()
        );
    }
}
