<?php namespace App\Mailers\Newsletters;

use App\Mailers\Newsletters\Newsletterlist;
use App\Mailers\Newsletters\Contracts\NewsletterListInterface;
use Mailchimp;
use App\User;

class MailchimpNewsletterList implements NewsletterListInterface
{
	protected $mailchimp;

	protected $lists;

	public function __construct(Mailchimp $mailchimp)
	{
		$this->mailchimp = $mailchimp;
		$this->lists = config('mail.newsletterLists');
	}

	

	public function subscribeTo($listName, User $user)
	{
		return $this->mailchimp->lists->subscribe(
			$this->lists[$listName],
			['email' => $user->email],
			['FNAME' => $user->first_name, 'LNAME'=> $user->last_name],
			'html', // email type
			false, //double opt in?
			true,  // update existing member?
			true, //replace interests
			false


		);
	}

	public function unsubscribeFrom($listName, User $user)
	{
		return $this->mailchimp->lists->unsubscribe(
			$this->lists[$listName],
			['email' => $user->email],
			false,   // delete member?
			false,   // send goodbye?
			true	  // notify?

		);
	}

	public function updateMember($listName, $groupName, $groupType, User $user) 
	{
		$merge_vars = [
			    'GROUPINGS' => [
			        0 => [
			            'name' => strtoupper($groupName), 
			            'groups' => [
			            	$groupType
			            ]
			        ]
		        ]
	    ];
	    return $this->mailchimp->lists->updateMember(
	    	$this->lists[$listName],
	    	['email' => $user->email],
	    	$merge_vars,
	    	'html',
	    	true
	    );

	}

	public function isSubscribedTo($listName, User $user)
	{

		$listsSimple = [];
		$lists = $this->getLists($user);
		
		foreach ($lists as $list) {
			$listsSimple[] = $list['id'];
		}

		if (in_array($this->lists[$listName] , $listsSimple)){
			return true;
		} 
		return false;
	}

	public function getLists(User $user)
	{	
		try { 
			return $this->mailchimp->helper->listsForEmail(
				['email' => $user->email]
			);
		} catch (\Mailchimp_List_NotSubscribed $e) {
			return [];
		}
	}

	public function getAllSubscribers($listName)
	{
		return $this->mailchimp->lists->members(
			$this->lists[$listName]
		);

	}




}