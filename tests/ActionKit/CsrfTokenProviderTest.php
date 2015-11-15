<?php
use ActionKit\CsrfTokenProvider;

class CsrfTokenProviderTest extends PHPUnit_Framework_TestCase 
{
    protected $token;

    protected $tokenWithKey;

    protected $provider;

    public function setUp()
    {
        $this->provider = new CsrfTokenProvider;
        $this->token = $this->provider->generateToken();
        $this->tokenWithKey = $this->provider->generateToken('test_token', 500);
    }

    public function testGenerateToken() 
    {
        $token = $this->token;
        ok($token); 
        
        is('_csrf_token', $token->sessionKey);
        is('300', $token->ttl);
        ok($token->hash);
        ok($_SESSION['_csrf_token']);

        $tokenWithKey = $this->tokenWithKey;
        ok($tokenWithKey); 
        
        is('test_token', $tokenWithKey->sessionKey);
        is('500', $tokenWithKey->ttl);
        ok($tokenWithKey->hash);
        ok($_SESSION['test_token']);
    }

    public function testLoadTokenWithSessionKey()
    {
        $token = $this->provider->loadTokenWithSessionKey();
        ok($token);
        ok(! isset($token->hash));
        is($this->token->salt, $token->salt);

        $tokenWithHash = $this->provider->loadTokenWithSessionKey('_csrf_token', true);
        ok(isset($tokenWithHash->hash));
        is($this->token->hash, $tokenWithHash->hash);

        $tokenWithKey = $this->provider->loadTokenWithSessionKey('test_token');
        ok($tokenWithKey);
        is('test_token', $tokenWithKey->sessionKey);
        is($this->tokenWithKey->salt, $tokenWithKey->salt);
    }

    public function testVerifyToken()
    {
        $token = $this->provider->loadTokenWithSessionKey();
        $result = $this->provider->verifyToken($token, $this->token->hash);
        is(true, $result);
    }
}
