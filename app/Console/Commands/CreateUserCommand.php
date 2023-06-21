<?php

namespace App\Console\Commands;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new User';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user['name'] = $this->ask('Name of the new User');
        $user['email'] = $this->ask('Email of the new User');
        $user['password'] = $this->secret('Password of the new User');
        $roleName = $this->choice('Role of the new User', ['admin', 'editor'], 1);
        $role = Roles::where('name', $roleName)->first();

        if (!$role) {
            $this->error('Role not found.');
            return -1;
        }

        $validator = Validator::make($user, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', Password::defaults()],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return -1;
        }

        DB::transaction(function () use ($user, $role) {
            $newUser = User::create($user);
            $newUser->roles()->attach($role->id);
        });

        $this->info('User ' . $user['email'] . ' created successfully.');
        return 0;
    }
}
