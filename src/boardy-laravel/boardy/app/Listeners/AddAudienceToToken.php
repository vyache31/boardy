<?php

namespace App\Listeners;

use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\Token;

class AddAudienceToToken
{
    public function handle(AccessTokenCreated $event): void
    {
        $token = Token::find($event->tokenId);
        if ($token) {
        }
    }
}
