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
		// It assignes the input value for the day range converting it to an int.
		$interval = intval($this->option('days'));

		/* It gives feedback about:
		*	- If it's going to send the emails.
		*	- The ammount of days condidered specified via input or by default value. 
		*/ 
		if (!$this->option('lazy')) {
			echo "I'm about to send a reminder to all open tickets more than ". $interval." ".
			($interval == 1 ? "day" : "days").
			" old.\n";
		} else {echo "Searching for open tickets over ". $interval." ".
			($interval == 1 ? "day" : "days").
			" old.\n";
		}

		// This is the database query, for tickets older than $interval and still open.
		$tickets = Models\Ticket::where('created_at', '<', Carbon::now()->subDays($interval))
								->where('open', '=', 1)
								->get();

		// It gives feedback about the status of the old and open tickets.
		$ammount = count($tickets);
		if ($this->option('list') OR $this->option('lazy')) {
			echo "I found ".$ammount." ".($ammount == 1 ? "ticket" : "tickets").":\n";
			foreach ($tickets as $ticket) {
				echo "ID: ".$ticket->id.
				", Code: ".$ticket->code.
				", Subject: ".$ticket->subject.
				", Assigned to: ".$ticket->assigned_to.
				", Days passed: ".Carbon::now()->diffInDays($ticket->created_at).
				"\n";
			}
		}

		// This will send an email for each old open ticket.
		if (!$this->option('lazy')) {
			foreach ($tickets as $ticket) {

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
			array('days', 'd', InputOption::VALUE_OPTIONAL, 'How many days must have passed to fire a notificantion email.', 7),
			array('list', 'l', InputOption::VALUE_NONE, 'Prints the ids of the ticket that are being mailed.', null),
			array('lazy', 'L', InputOption::VALUE_NONE, 'Prints the infos, but doesn\'t send the mails.', null),
		);
	}

}
