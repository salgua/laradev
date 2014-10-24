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
		$recipients = array_merge($this->option('report'),$this->option('situation'));
		$is_report = ($this->option('report') ? true : false);
		$is_situation = ($this->option('situation') ? true : false);

		// Error message to check that the user input does not contain both 'report' and 'situation'.
		if ($is_report AND $is_situation) {
			exit("Impossible! \"--report|-r\" and \"--situation|-s\" are mutually exclusive.\n");
		}
		
		$is_test = $this->option('test');
		$do_list = $this->option('list');
		$do_confirm = $this->option('confirm');
		$interval = intval($this->option('days'));


		// This is the database query, for tickets older than $interval and still open.
		$tickets = Models\Ticket::where('created_at', '<', Carbon::now()->subDays($interval))
								->where('open', '=', 1)
								->get();

		$ammount = count($tickets);

		// Elaborates who is the ticket admin.
		$admins = [];
		function whoIsAdmin(&$admins, $ticket)
		{
			/* First it will check if it already queried the DB for that admin ID.
			*  This is to reduce the number of queries to the DB.
			*/ 
			foreach ($admins as $admin) {
				if ($admin->id == $ticket->assigned_to) {
					return $admin;
				}
			}

			// Else it will ask the DB.
			$admin = User::where('id', '=', $ticket->assigned_to)->first();
			array_push($admins, $admin);
			return $admin;
		}

		/* It gives feedback about:
		*	- If it's going to send the emails.
		*	- The ammount of days condidered specified via input or by default value. 
		*/
		if ($do_confirm) {
			$message = "";
			if ($is_test OR $is_situation) {
				$message = "[".Carbon::now()."]: ";
				$message .= "Searching for open tickets over ";
				$message .= $interval." ".($interval == 1 ? "day" : "days")." old.\n";
			} else {
				$message = "[".Carbon::now()."]: ";
				$message .= "I'm about to send a reminder to all open tickets more than ";
				$message .= $interval." ".($interval == 1 ? "day" : "days")." old.\n";
			}
			$message .= "- I found ".$ammount." ".($ammount == 1 ? "ticket" : "tickets");
			$message .= ($do_list ? ":" : ".")."\n";
			echo $message;
		}

		// It gives feedback about the status of the old and open tickets.
		if ($do_list) {
			foreach ($tickets as $ticket) {
				$message = "-- ID: ".$ticket->id;
				$message .= ", Code: ".$ticket->code;
				$message .= ", Subject: ".$ticket->subject;
				$message .= ", Assigned to: ".whoIsAdmin($admins, $ticket)->screen_name;
				$message .= ", Days passed: ".Carbon::now()->diffInDays($ticket->created_at)."\n";
				echo $message;
			}
		}

		// If it's a test do not proceed further.
		if ($is_test) {
			exit("No email has been sent.\n");
		}

		// This will send an email for each old open ticket.
		if (!$is_situation) {
			foreach ($tickets as $ticket) {
				// First it will discover who is the admin for this ticket.
				$admin = whoIsAdmin($admins, $ticket);
				exit();
				// Now it has all the data to send the email.
				$delay = Carbon::now()->diffInDays($ticket->created_at);
				\Mail::send('emails.ticketMailReminder.reminder', array('ticket' => $ticket, 'admin' => $admin, 'delay' => $delay), function($message) use($admin, $ticket) {
				    $message
				    ->to($admin->email, $admin->screen_name)
				    ->subject(sprintf(trans('It is %s days that %s is waiting for your help!'), Carbon::now()->diffInDays($ticket->created_at), $ticket->author_email));
				});
			}
		}

		// This will send an email to each email address given with "report" or "situation".
		if ($recipients) {

			// Generating the report message
			$th = "<th style=\"padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal\">";
			$td = "<td style=\"text-align: center;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal\">";
			$report = '<table style="border-collapse:collapse;border-spacing:0">';
			$report .= "<tr>";
			$report .= $th."Ticket ID</th>";
			$report .= $th."User</th>";
			$report .= $th."Ticket</th>";
			$report .= $th."Subject</th>";
			$report .= $th."Assigned to</th>";
			$report .= $th."Days passed</th>";
			$report .= "</tr>";
			foreach ($tickets as $ticket) {
				$admin = whoIsAdmin($admins, $ticket);
				$report .= "<tr>";
				$report .= $td.$ticket->id."</td>";
				$report .= $td.$ticket->author_email."</td>";
				$report .= $td."<a href=".URL::to('tickets/code', array($ticket->code)).">".$ticket->code."</a></td>";
				$report .= $td.$ticket->subject."</td>";
				$report .= $td.$admin->screen_name."</td>";
				$report .= $td.Carbon::now()->diffInDays($ticket->created_at)."</td>";
				$report .= "</tr>";
			}
			$report .= "</table>";

			// Send the report to each recipient
			foreach ($recipients as $recipient) {
				\Mail::send('emails.ticketMailReminder.report', array('report' => $report), function($message) use($recipient) {
				    $message
				    ->to($recipient)
				    ->subject(sprintf(trans('[%s]: Ticket System Status Report'), Carbon::now()));
				});
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
			array('test', 't', InputOption::VALUE_NONE, 'Prevents any email from being sent.', null),
			array('confirm', 'c', InputOption::VALUE_NONE, 'Outputs confirm of execution.', null),
			array('list', 'l', InputOption::VALUE_NONE, 'Prints the ids of the ticket that match.', null),
			array('days', 'd', InputOption::VALUE_OPTIONAL, 'How many days must have passed to fire a notificantion email.', 7),
			array('report', 'r', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'It will ALSO send an email with the log to the specified email address.', null),
			array('situation', 's', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'It will INSTEAD send an email with the log to the specified email address.', null),
		);
	}

}