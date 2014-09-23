<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateUsers extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'users:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Custom command to create users';

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
	public function fire()
	{
		$this->info('With this command you can create users');
		$user = new User;
		$user->email = $this->ask('What is the email address?');
		$user->password = Hash::make($this->secret('What is the password?'));
		$user->active = ($this->confirm('Do you want to activate this user? [yes|no]')) ? true : false;
		if ($user->save()) {
			$this->info('New user saved!');
		} else {
			$this->error('Something went wrong!');
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			//array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			//array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
