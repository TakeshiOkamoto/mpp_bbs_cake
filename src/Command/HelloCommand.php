<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

// コンソールコマンド
// https://book.cakephp.org/3/ja/console-and-shells/commands.html

class HelloCommand extends Command
{
    protected function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser->addArgument('name');
        $parser->addArgument('email');
        $parser->addArgument('password');
        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io)
    {
        // ユーザーの登録
        $this->loadModel('Users');
        $param =[
            'name'     => $args->getArgument('name'),
            'email'    => $args->getArgument('email'),
            'password' => $args->getArgument('password'),
        ];
        
        // パスワードはsrc\Model\Entity\User.phpの_setPassword()で自動でハッシュ化される
        $users = $this->Users->newEntity();
        $users = $this->Users->patchEntity($users, $param);
        $this->Users->saveOrFail($users);
              
        $io->out("ユーザーを登録しました( ﾟДﾟ)");
    }
}