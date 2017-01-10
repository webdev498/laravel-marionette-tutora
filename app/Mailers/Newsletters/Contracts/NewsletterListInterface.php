<?php namespace App\Mailers\Newsletters\Contracts;

use App\User;

interface NewsletterListInterface
{
	public function subscribeTo($listName, User $user);

	public function unsubscribeFrom($listName, User $user);
}
