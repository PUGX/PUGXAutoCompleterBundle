<?php

namespace PUGX\AutocompleterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

final class PUGXAutocompleterBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
