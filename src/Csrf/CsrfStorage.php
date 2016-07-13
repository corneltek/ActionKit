<?php
namespace ActionKit\Csrf;

interface CsrfStorage {

    public function store(CsrfToken $token, $key = null);

    public function load($key = null);

    public function drop($key = null);

    public function exists($key = null);
}
