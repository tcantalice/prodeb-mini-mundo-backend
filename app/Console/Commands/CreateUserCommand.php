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
    protected $description = 'Cadastra um novo usuário';

    public function __construct(private AuthService $authService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $login = $this->argument('login');

        if ($this->authService->isUser($login)) {
            $this->warn("Já existe um usuário $login");
            return 1;
        }

        try {
            $this->authService->register(new RegisterData(
                $login, $this->argument('senha'), $this->argument('nome'), $this->argument('email')
            ));

            $this->info('Usuário criado com sucesso!');

            return 0;
        } catch(\Exception $e) {
            $this->error('Não foi possível criar o usuário');

            return 1;
        }
    }
}
