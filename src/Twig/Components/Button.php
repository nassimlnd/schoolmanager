<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Button
{
    public string $label = 'Button';
    public string $variant = 'primary';
    public ?string $href = null;
    public ?string $type = 'button';
}
