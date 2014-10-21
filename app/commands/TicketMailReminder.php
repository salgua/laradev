<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Carbon\Carbon;
use Tickets\Models as Models;

class TicketMailReminder extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'mail:ticket-reminder';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sends a mail as a reminder for each open ticket older than a certain ammount of days.';

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
		$interval = intval($this->option('days'), 10);
		echo "I'm about to send a reminder for all tickets more than ".$interval." days old.\n";
		$tickets = Models\Ticket::where('created_at', '<', Carbon::now()->subDays($interval))
								->where('open', '=', 1)
								->get();
		if ($this->option('list')) {
			echo "I found ".count($tickets)." tickets:\n";
			foreach ($tickets as $ticket) {
				echo "ID: ".$ticket->id.", Code: ".$ticket->code.", Subject: ".$ticket->subject.", Assigned to: ".$ticket->assigned_to.", Days passed: ".Carbon::now()->diffInDays($ticket->created_at)."\n";
			}
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
			//array('days', InputArgument::REQUIRED, 'How many days must have passed to fire a notificantion email.', 7),
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
			array('days', 'd', InputOption::VALUE_OPTIONAL, 'How many days must have passed to fire a notificantion email.', 7),
			array('list', 'l', InputOption::VALUE_NONE, 'Prints the ids of the ticket that are being mailed.', null),
		);
	}

}
