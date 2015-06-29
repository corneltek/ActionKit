<?php

class CsrfTokenProviderTest extends PHPUnit_Framework_TestCase 
{
    protected $token;
    protected $tokenWithKey;

    protected function setUp()
    {
        $this->token = ActionKit\CsrfTokenProvider::generateToken();
        $this->tokenWithKey = ActionKit\CsrfTokenProvider::generateToken('test_token', 500);
    }

    function testGenerateToken() 
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

    function testLoadTokenWithSessionKey()
    {
        $token = ActionKit\CsrfTokenProvider::loadTokenWithSessionKey();
        ok($token);
        ok( ! isset($token->hash));
        is($this->token->salt, $token->salt);

        $tokenWithHash = ActionKit\CsrfTokenProvider::loadTokenWithSessionKey('_csrf_token', true);
        ok(isset($tokenWithHash->hash));
        is($this->token->hash, $tokenWithHash->hash);

        $tokenWithKey = ActionKit\CsrfTokenProvider::loadTokenWithSessionKey('test_token');
        ok($tokenWithKey);
        is('test_token', $tokenWithKey->sessionKey);
        is($this->tokenWithKey->salt, $tokenWithKey->salt);
    }

    function testVerifyToken()
    {
        $token = ActionKit\CsrfTokenProvider::loadTokenWithSessionKey();
        $result = ActionKit\CsrfTokenProvider::verifyToken($token, $this->token->hash);
        is(true, $result);
    }
}