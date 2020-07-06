<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test {user} {age=1} {sex?} {--debug} {--check=yes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '测试command, 参数：user(必须),age(可选),sex(可选)
                        { user: The name of the user. }
                        { age : The age of the user, default is 1. }
                        { sex : The sex of the user. Optional. }
                        { --debug : is debug. }
                        { --check=yes : is check. yes or no. default is yes. }
                        { example : php ./artisan command:test 张三 99 男 --debug --check=no }';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        var_dump(config('app.name'));exit;
        var_dump('测试：'. date('Y-m-d H:i:s'));
        var_dump('user: '. $this->argument('user'), 'age: '. $this->argument('age'), 'sex: '. $this->argument('sex'), 'debug: '. $this->option('debug'), 'check: '. $this->option('check'));
    }
}
