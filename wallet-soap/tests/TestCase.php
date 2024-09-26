<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Managers\CustomerManager;
use Tests\Managers\FileManager;
use Tests\Managers\TransactionManager;
use Tests\Managers\WalletManager;

abstract class TestCase extends BaseTestCase
{
    use DatabaseMigrations;

    use CustomerManager;
    use TransactionManager;
    use WalletManager;
    use FileManager;
}
