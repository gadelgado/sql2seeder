<?php

namespace Gadelgado\Sql2Seeder\Commands;

use Gadelgado\Sql2Seeder\Sql2SeederConverter;
use Illuminate\Console\Command;

class Sql2SeederCommand extends Command
{
    protected $signature = 'sql:convert';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        new Sql2SeederConverter();
    }
}

