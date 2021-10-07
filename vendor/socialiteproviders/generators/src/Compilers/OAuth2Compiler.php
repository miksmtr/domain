<?php

namespace SocialiteProviders\Generators\Compilers;

class OAuth2Compiler extends Compiler
{
    /**
     * Generate the Socialite Extender.
     */
    public function extendSocialite()
    {
        return $this->compile(
            'OAuth2/ExtendSocialite',
            $this->context->nameStudlyCase().'ExtendSocialite.php'
        );
    }

    /**
     * Generate the OAuth2 Provider.
     */
    public function provider()
    {
        return $this->compile('OAuth2/Provider', 'Provider.php');
    }
}