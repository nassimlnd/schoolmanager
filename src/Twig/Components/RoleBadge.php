<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class RoleBadge
{
    public string $role;
    public string $roleName;
    public string $color;

    public function mount(array $roles): void
    {
        $this->role = $roles[0] ?? 'ROLE_USER';
        $this->roleName = match ($roles[0] ?? 'ROLE_USER') {
            'ROLE_ADMIN' => 'Admin',
            'ROLE_USER' => 'User',
            'ROLE_STUDENT' => 'Student',
            default => 'Unknown',
        };

        $this->color = $this->getColor();
    }

    public function getColor(): string
    {
        return match ($this->role) {
            'ROLE_ADMIN' => 'blue',
            'ROLE_USER' => 'orange',
            'ROLE_STUDENT' => 'green',
            default => 'gray',
        };
    }

    public function getRoleName(): string
    {
        return $this->roleName;
    }
}
