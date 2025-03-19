<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class BaseApiTestCase extends TestCase
{
    use DatabaseTransactions;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->withHeaders([
            'Accept' => 'application/json',
        ]);

        parent::setUp();
    }
}
