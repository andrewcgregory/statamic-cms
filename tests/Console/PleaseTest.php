<?php

namespace Tests\Console;

use Tests\TestCase;
use Statamic\Console\Please\Kernel;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class PleaseTest extends TestCase
{
    /** @test */
    function it_can_run_an_artisan_command_with_statamic_prefix()
    {
        $this->artisan('statamic:static:clear');

        $this->expectException(CommandNotFoundException::class);
        $this->artisan('static:clear');
    }

    /** @test */
    function it_can_run_a_please_command_without_statamic_prefix()
    {
        $this->please('static:clear');

        $this->expectException(CommandNotFoundException::class);
        $this->please('statamic:static:clear');
    }

    public function please($command, $parameters = [])
    {
        return $this->app[Kernel::class]->call($command, $parameters);
    }
}
