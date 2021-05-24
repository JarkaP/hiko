<?php

namespace App\Traits;

trait HasRolesAndAbilities
{
    public function roles()
    {
        return [
            'administrator' => [
                'label' => 'Administrátor',
                'abilities' => [
                    'manage-metadata',
                    'view-metadata',
                    'manage-users',
                ],
            ],
            'editor' => [
                'label' => 'editor',
                'abilities' => [
                    'manage-metadata',
                    'view-metadata',
                ],
            ],
            'guest' => [
                'label' => 'Divák',
                'abilities' => [
                    'view-metadata',
                ],
            ],
        ];
    }

    public function roleHasAbility($role, $ability)
    {
        if (!isset($this->roles()[$role])) {
            return false;
        }

        return in_array($ability, $this->roles()[$role]['abilities']);
    }
}
