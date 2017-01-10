<?php 

namespace App\Messaging;

use App\MessageLine;
use App\Relationship;

class Flagger
{
	/**
	* @var Messageline $line
	*/
	protected $line;

	/**
	* @var Relationship $relationship
	*/
	protected $relationship;

	/**
	 * @var Array $flaggedWords
	 */
	protected $flaggedWordsNotConfirmed = [
		'cash',
		'directly',
		'commission',
		'comission',
		'z.?.?e.?.?r.?.?o',  // z e r o
		'seven',   // S e v e n
		'@',
		'\sdot\s',
		'c\so\sm',
		'\scom\s',
		'\scom[^a-z|1-9]',
		'g[^a-z]{0,2}m[^a-z]{0,2}a[^a-z]{0,2}i[^a-z]{0,2}l',  						// gmail
		'h[^a-z]{0,2}o[^a-z]{0,2}t[^a-z]{0,2}m[^a-z]{0,2}a[^a-z]{0,2}i[^a-z]{0,2}l',  // hotmail
		'y[^a-z]{0,2}a[^a-z]{0,2}h[^a-z]{0,2}o[^a-z]{0,2}o',  						// yahoo
		'a[^a-z]?o[^a-z]?l',    													// aol
		'i[^a-z]{0,2}c[^a-z]{0,2}l[^a-z]{0,2}o[^a-z]{0,2}u[^a-z]{0,2}d',
		'o[^a-z]{0,1}u[^a-z]{0,1}t[^a-z]{0,1}l[^a-z]{0,1}o[^a-z]{0,1}o[^a-z]{0,1}k', // outlook
		'skype',
		'facebook',
		'instagram',
		'snapchat',
		'whatsapp',

	];

	protected $flaggedWordsWithoutBooking = [
		'see\syou',							// see you
		'\ssee\su\s',    					// see u

		'seeing\syou',						// seeing you

		'\sc\su\s',							// c u
		'catch\syou',						// catch you
		'catch\su\s',						// catch u
		'meet\syou\stomorrow',				// meet you tomorrow
		'add\sme',							// add me
		'added\syou', 						// added you
		'added\su\s',						// added u
		'look\sforward',					// look forward
	];

	/**
	* Create the instance
	* @var $line
	* @return void
	*/

	public function __construct(MessageLine $line, Relationship $relationship)
	{
		$this->line = $line;
		$this->relationship = $relationship;

	}

	public function flagged() 
	{

		if($this->containsFlaggedWordsWithoutConfirmed()) {
			return true;
		}

		if($this->containsFlaggedWordsWithoutBooking()) {
			return true;
		}
		return false;
	}

	public function highlightFlagged()
	{
		foreach($this->flaggedWordsNotConfirmed as $word) {
			if (preg_match('/' . $word .'/i', $this->line->body)) {
			    return preg_replace('/' . $word .'/i', '|| $0 ||', $this->line->body);
			}
		}
		
		foreach($this->flaggedWordsWithoutBooking as $word) {
			if (preg_match('/' . $word .'/i', $this->line->body)) {
			    return preg_replace('/' . $word .'/i', '|| $0 ||', $this->line->body);
			}
		}

		return $this->line->body;
	}

	protected function containsFlaggedWordsWithoutConfirmed()
	{

		foreach($this->flaggedWordsNotConfirmed as $word) {
			if (preg_match('/' . $word .'/i', $this->line->body)) {
			    if (! $this->relationship->is_confirmed) {
			    	return true;
			    }
			}
		}

		return false;
	}

	protected function containsFlaggedWordsWithoutBooking()
	{
		foreach($this->flaggedWordsWithoutBooking as $word) {
			if (preg_match('/' . $word .'/i', $this->line->body)) {
				if ($this->relationship->lessons->count() == 0) {
					return true;
				}
			}
		}

		return false;

	}
}