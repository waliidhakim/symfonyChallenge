<?php

namespace App\TwigExtensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MyCustomTwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('defaultImage', [$this, 'defaultImage']),
            new TwigFilter('readableRoles', [$this, 'readableRoles'])
        ];
    }

    public function defaultImage(string $path) : string
    {
        if(strlen(trim($path)) == 0)
        {
            return 'jira.PNG';
        }

        return $path;
    }

    public function readableRoles(array $roles) : string
    {
        $readableRoles = [];
        foreach ($roles as $role) {
            if ($role === 'ROLE_ADMIN') {
                $readableRoles[] = 'Admin';
            } elseif ($role === 'ROLE_USER') {
                $readableRoles[] = 'User';
            }
            // Ajoutez des conditions supplémentaires pour d'autres rôles ici, si nécessaire
        }

        return implode(', ', $readableRoles);
    }
}