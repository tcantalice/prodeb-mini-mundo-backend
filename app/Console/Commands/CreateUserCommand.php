<?php

namespace App\Console\Commands;

use App\Auth\RegisterData;
use App\Auth\Service as AuthService;
use Illuminate\Console\Command;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:usuario {nome} {login} {senha} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(private AuthService $authService)
    {
        //
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $login = $this->argument('login');

        if ($this->authService->isUser($login)) {
            $this->warn("Já existe um usuário $login");
        }

        try {
            $this->authService->register(new RegisterData(
                $login, $this->argument('senha'), $this->argument('nome'), $this->argument('email')
            ));

            return 0;
        } catch(\Exception $e) {
            $this->error('Não foi possível criar o usuário');

            return 1;
        }
    }
}
