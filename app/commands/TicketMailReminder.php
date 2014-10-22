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
		// Error message to check that the user input does not contain both 'report' and 'situation'.
		if ($this->option('report') AND $this->option('situation')) {
			echo "Impossible! \"--report|-r\" and \"--situation|-s\" are mutually exclusive.\n";
			return null;
		}

		$message = "";
		$now = Carbon::now();
		$interval = intval($this->option('days'));

		// This is the database query, for tickets older than $interval and still open.
		$tickets = Models\Ticket::where('created_at', '<', $now->subDays($interval))
								->where('open', '=', 1)
								->get();

		$ammount = count($tickets);

		/* It gives feedback about:
		*	- If it's going to send the emails.
		*	- The ammount of days condidered specified via input or by default value. 
		*/
		if ($this->option('confirm')) {
			if ($this->option('test') OR $this->option('situation')) {
				$message = "[".$now."]: ";
				$message .= "Searching for open tickets over ";
				$message .= $interval." ".($interval == 1 ? "day" : "days")." old.\n";
			} else {
				$message = "[".$now."]: ";
				$message .= "I'm about to send a reminder to all open tickets more than ";
				$message .= $interval." ".($interval == 1 ? "day" : "days")." old.\n";
			}
			$message .= "- I found ".$ammount." ".($ammount == 1 ? "ticket" : "tickets");
			$message .= ($this->option('list') ? ":" : ".")."\n";
			echo $message;
		}

		// It gives feedback about the status of the old and open tickets.
		if ($this->option('list')) {
			foreach ($tickets as $ticket) {
				$message = "-- ID: ".$ticket->id;
				$message .= ", Code: ".$ticket->code;
				$message .= ", Subject: ".$ticket->subject;
				$message .= ", Assigned to: ".$ticket->assigned_to;
				$message .= ", Days passed: ".$now->diffInDays($ticket->created_at)."\n";
			}
			echo $message;
		}

		// This will send an email for each old open ticket.
		if (!$this->option('test') AND !$this->option('situation')) {
			foreach ($tickets as $ticket) {
				echo "mails sent\n";
			}
		}

		// This will send an email for each old open ticket.
		foreach ($this->option('report') as $address) {
			echo $address."\n";
		}

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('test', 't', InputOption::VALUE_NONE, 'Prevents any email from being sent.', null),
			array('confirm', 'c', InputOption::VALUE_NONE, 'Outputs confirm of execution.', null),
			array('list', 'l', InputOption::VALUE_NONE, 'Prints the ids of the ticket that match.', null),
			array('days', 'd', InputOption::VALUE_OPTIONAL, 'How many days must have passed to fire a notificantion email.', 7),
			array('report', 'r', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'It will ALSO send an email with the log to the specified email address.', null),
			array('situation', 's', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'It will INSTEAD send an email with the log to the specified email address.', null),
		);
	}

}
