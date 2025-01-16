<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class OnboardingSidebar
{
    public int $step;

    public function mount(int $step): void
    {
        $this->step = $step;
    }

    public function getStep(): int
    {
        return $this->step;
    }
}
