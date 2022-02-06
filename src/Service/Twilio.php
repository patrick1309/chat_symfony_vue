<?php

namespace Service;

use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\ChatGrant;

class Twilio
{
    /**
     * Retourne un token en fonction de l'adresse email connectée
     *
     * @param string $email
     * @return string
     */
    public function getToken(string $email): string
    {
        $twilioAccoundSid = getenv('ACCOUNT_SID');
        $twilioApiKey = getenv('API_KEY');
        $twilioApiSecret = getenv('API_SECRET');
        $twilioServiceSid = getenv('SERVICE_INSTANCE_SID');

        $token = new AccessToken(
            $twilioAccoundSid,
            $twilioApiKey,
            $twilioApiSecret,
            3600,
            $email
        );

        $chatGrant = new ChatGrant;
        $chatGrant->setServiceSid($twilioServiceSid);

        $token->addGrant($chatGrant);

        return $token->toJWT();
    }
}
