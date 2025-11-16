<?php

namespace Cli\Enumerations;

enum ArgumentType: int
{
    case Required = 0;
    case Optional = 1;
}
