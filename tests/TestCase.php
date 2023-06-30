<?php

namespace Tests;

use function array_rand;
use function floor;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use function mt_rand;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
