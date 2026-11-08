<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TestEnvironmentTest extends TestCase
{
    /**
     * Guard: the suite must never run against the development database.
     * Later tests will use RefreshDatabase, which drops and recreates tables.
     */
    public function test_tests_run_against_the_dedicated_mysql_test_database(): void
    {
        $this->assertSame('testing', app()->environment());
        $this->assertSame('mysql', config('database.default'));
        $this->assertSame('cc_sms_test', DB::connection()->getDatabaseName());
    }
}