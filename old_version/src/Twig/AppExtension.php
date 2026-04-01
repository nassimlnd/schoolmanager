<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('leading_zeros', [$this, 'leadingZeros']),
        ];
    }

    public function leadingZeros(int $number): string
    {
        if ($number > 9) {
            return (string) $number;
        } else {
            return str_pad($number, 2, '0', STR_PAD_LEFT);
        }
    }
}
