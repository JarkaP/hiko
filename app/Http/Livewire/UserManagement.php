<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Notifications\NewUserPasswordCreate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserManagement extends Component
{
    use AuthorizesRequests, WithPagination;

    public $name = '';
    public $email = '';
    public $role = 'editor';
    public $message = '';

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

        $this->message = __('Účet byl úspěšně vytvořen.');
        $this->dispatchBrowserEvent('user-created');
    }

    public function edit($id, $formData)
    {
        $validatedData = Validator::make(
            $formData,
            [
                'name' => ['required', 'string'],
                'role' => ['required', Rule::in(['administrator', 'editor', 'guest'])],
            ],
            [],
            $this->validationAttributes()
        )->validate();

        $user = User::find($id);
        $user->name = $validatedData['name'];
        $user->role = $validatedData['role'];
        $user->save();

        $this->message = __('Účet byl úspěšně upraven.');
        $this->dispatchBrowserEvent('user-edited');
    }

    public function delete($userId)
    {
        $user = User::find($userId);
        $user->delete();

        $this->message = __('Účet byl úspěšně odstraněn.');
        $this->dispatchBrowserEvent('user-deleted');
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
