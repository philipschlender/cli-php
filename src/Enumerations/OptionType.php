<?php

namespace Cli\Enumerations;

enum OptionType: int
{
    case RequiredValue = 0;
    case OptionalValue = 1;
    case NoValue = 2;
}
