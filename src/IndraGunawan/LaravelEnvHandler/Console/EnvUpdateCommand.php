<?php

namespace IndraGunawan\LaravelEnvHandler\Console;

use Dotenv;
use InvalidArgumentException;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;

class EnvUpdateCommand extends Command
{
    /**
     * .env file name
     * @var string
     */
    const ENV = '.env';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'env:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Update .env file from .env.example";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $actualEnv = $this->loadDotEnv();
        $expectedEnv = $this->loadDotEnv(true);

        $realFile = rtrim(base_path(), '/').'/'.self::ENV;

        $action = empty($actualEnv) ? 'Creating' : 'Updating';
        $this->info(sprintf('%s the "%s" file', $action, self::ENV));

        $actualValue = $this->getEnvValue($expectedEnv, $actualEnv);

        //save the new env value to .env file if only has changed
        if (!empty($actualValue)) {
            file_put_contents($realFile, "# This file is auto-generated.\n".$actualValue);
        }

        // load back the new env
        $this->loadDotEnv();
    }

    /**
     * Command options
     *
     * @return [][]
     */
    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force ask all environment parameters.', null]
        ];
    }

    /**
     * get the env value if not exists
     * @param  array  $expectedEnv
     * @param  array  $actualEnv
     * @return string
     */
    public function getEnvValue(array $expectedEnv, array $actualEnv)
    {
        $actualValue = '';
        $isStarted = false;
        foreach ($expectedEnv as $key => $defaultValue) {
            if (array_key_exists($key, $actualEnv)) {
                if ($this->option('force')) {
                    $defaultValue = $actualEnv[$key];
                } else {
                    $actualValue .= sprintf("%s=%s\n", $key, $actualEnv[$key]);
                    continue;
                }
            }

            if (!$isStarted) {
                $isStarted = true;
                if ($this->option('force')) {
                    $this->comment('Update all parameters. Please provide them.');
                } else {
                    $this->comment('Some parameters are missing. Please provide them.');
                }
            }

            $value = $this->ask($key, $defaultValue);
            // set the prompt value to env
            $actualValue .= sprintf("%s=%s\n", $key, $value);
        }
        return $actualValue;
    }

    /**
     * Override super function
     *
     * @param  String $question
     * @param  String $default
     * @return String
     */
    public function ask($question, $default = '')
    {
        $helper = $this->getHelperSet()->get('question');

        $question = new Question(sprintf("<question>%s</question> (<comment>%s</comment>): ", $question, $default), $default);

        return $helper->ask($this->input, $this->output, $question);
    }

    /**
     * Load the .env(.example) file
     * @param  boolean $isExample
     * @return array
     */
    private function loadDotEnv($isExample = false)
    {
        // make sure all environment clear.
        $this->emptyEnvironment();

        // load the .env(.example) file to environment
        $postfix = $isExample ? '.example' : '';
        try {
            Dotenv::load(base_path(), self::ENV . $postfix);
        } catch (InvalidArgumentException $e) {
            // if the .env file not found then return empty array
            if (!$isExample) {
                return [];
            }
            throw $e;
        }
        return $_ENV;
    }

    /**
     * Empty current environment
     * @return void
     */
    private function emptyEnvironment()
    {
        foreach (array_keys($_ENV) as $key) {
            putenv($key);
            unset($_ENV[$key]);
            unset($_SERVER[$key]);
        }
    }
}
