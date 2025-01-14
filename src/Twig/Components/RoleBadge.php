<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class RoleBadge
{
    public string $role;
    public string $roleName;

    public function mount(array $roles): void
    {
        $this->role = $roles[0] ?? 'ROLE_USER';
        $this->roleName = match ($roles[0] ?? 'ROLE_USER') {
            'ROLE_ADMIN' => 'Admin',
            'ROLE_USER' => 'User',
            default => 'Unknown',
        };
    }

    public function getRoleName(): string
    {
        return $this->roleName;
    }
}
