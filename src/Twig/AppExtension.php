<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('leading_zeros', [$this, 'leadingZeros']),
        ];
    }

    public function leadingZeros($week, $increment = 0): string
    {
        $newWeek = (int)$week + $increment;
        if ($newWeek > 52) $newWeek = 1;
        if ($newWeek < 1) $newWeek = 52;
        return sprintf("%02d", $newWeek);
    }
}
