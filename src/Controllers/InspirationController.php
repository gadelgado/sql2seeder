<?php
namespace Gadelgado\Sql2Seeder\Controllers;

use Illuminate\Http\Request;
use Gadelgado\Sql2Seeder\Inspire;

class InspirationController
{
    public function __invoke(Inspire $inspire) {
        $quote = $inspire->justDoIt();

        return $quote;
    }
}
