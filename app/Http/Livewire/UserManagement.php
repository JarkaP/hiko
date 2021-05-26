<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Notifications\NewUserPasswordCreate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserManagement extends Component
{
    use AuthorizesRequests;

    public $name = '';
    public $email = '';
    public $role = 'editor';

    public function mount()
    {
        $this->authorize('manage-users');
    }

    public function create()
    {
        $this->validate();

        $user = new User();
        $user->email = $this->email;
        $user->name = $this->name;
        $user->role = $this->role;
        $user->password = bcrypt(Str::random(10));
        $user->save();
        $user->notify(new NewUserPasswordCreate($user));

        session()->flash('success', 'Uživatel/ka  ' . $this->name . ' byl/a úspěšně vytvořen/a.');
        return redirect()->route('users');
    }

    public function delete($userId)
    {
        $user = User::find($userId);
        $user->delete();

        session()->flash('success', 'Uživatel/ka byl/a úspěšně odstraněn/a.');
        return redirect()->route('users');
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

    protected function rules()
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'role' => ['required', Rule::in(['administrator', 'editor', 'guest'])],
        ];
    }

    protected function validationAttributes()
    {
        return [
            'name' => __('Jméno'),
            'email' => __('E-mail'),
            'role' => __('Role'),
        ];
    }
}
