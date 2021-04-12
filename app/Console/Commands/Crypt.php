<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Encryption\Encrypter;
use Illuminate\Console\ConfirmableTrait;

class Crypt extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:crypt {value : The value to encrypt or decrypt}
        {--decrypt : false: encrypt, true:decrypt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '加密解密处理, 参数：value, --decrypt
                        { example : php ./artisan command:crypt --decrypt password }';

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
        try {
            $this->initKey();
            $decrypt = $this->option('decrypt');
            $value = $this->argument('value');
            is_file($value) && $value = trim(file_get_contents($value));

            if ($decrypt) {
                $this->info('DECRYPT: ' . decrypt($value));
            } else {
                $this->info('ENCRYPT: ' . encrypt($value));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date   2021年1月12日
     * @return boolean
     */
    protected function initKey()
    {
        if (strlen($this->laravel['config']['app.key']) === 0) {
            $key = $this->generateRandomKey();
            if (!$this->setKeyInEnvironmentFile($key)) {
                return false;
            }
            $this->laravel['config']['app.key'] = $key;
        }
        return true;
    }


    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function generateRandomKey()
    {
        return 'base64:'.base64_encode(
            Encrypter::generateKey($this->laravel['config']['app.cipher'])
        );
    }


    /**
     * Set the application key in the environment file.
     *
     * @param  string  $key
     * @return bool
     */
    protected function setKeyInEnvironmentFile($key)
    {
        $currentKey = $this->laravel['config']['app.key'];

        if (strlen($currentKey) !== 0 && (! $this->confirmToProceed())) {
            return false;
        }

        $this->writeNewEnvironmentFileWith($key);

        return true;
    }

    /**
     * Write a new environment file with the given key.
     *
     * @param  string  $key
     * @return void
     */
    protected function writeNewEnvironmentFileWith($key)
    {
        file_put_contents($this->envPath(), preg_replace(
            $this->keyReplacementPattern(),
            'APP_KEY='.$key,
            file_get_contents($this->envPath())
        ));
    }

    /**
     * Get a regex pattern that will match env APP_KEY with any random key.
     *
     * @return string
     */
    protected function keyReplacementPattern()
    {
        $escaped = preg_quote('='.$this->laravel['config']['app.key'], '/');

        return "/^APP_KEY{$escaped}/m";
    }

    /**
     *
     * @author zxf
     * @date   2021年1月12日
     * @return string
     */
    protected function envPath()
    {
        if (method_exists($this->laravel, 'environmentFilePath')) {
            return $this->laravel->environmentFilePath();
        }

        return $this->laravel->basePath('.env');
    }
}
