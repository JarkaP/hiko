<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserManagement extends Component
{
    use AuthorizesRequests;

    public function mount()
    {
        $this->authorize('manage-users');
    }

    public function render()
    {
        return view('livewire.user-management', [
            'users' => User::orderBy('name', 'asc')
                ->paginate(15, [
                    'id',
                    'name',
                    'email',
                    'role',
                    'deactivated_at',
                ]),
        ]);
    }
}
