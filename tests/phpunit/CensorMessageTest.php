<?php

use App\MessageLine;
use App\Messaging\Parser;

class CensorMessageTest extends TestCase
{

    protected function parse($body)
    {
        $line = new MessageLine();
        $line->body = $body;

        return Parser::make($line);
    }

    /**
     * Test that messages that don't need censoring, aren't.
     *
     * @return void
     */
    public function test_messages_arent_censored()
    {
        return;

        $parsed = $this->parse('Hello, world!');
        $this->assertEquals('<p>Hello, world!</p>', $parsed->getBody());
    }

    /**
     * Test that a simple email address is censored
     *
     * e.g. hire@aaron.codes
     *
     * @return void
     */
    public function test_simple_email_addresses()
    {
        $parsed = $this->parse('My email address is hire@aaron.codes.');
        $this->assertEquals('<p>My email address is ******.</p>', $parsed->getBody());
    }

    /**
     * Test that a square bracketed email address is censored
     *
     * e.g. hire [ at ] aaron [ dot ] codes
     *
     * @return void
     */
    public function test_square_bracketed_email_addresses()
    {
        $parsed = $this->parse('My email address is hire [ at ] aaron [ dot ] codes.');
        $this->assertEquals('<p>My email address is ******.</p>', $parsed->getBody());
    }

    /**
     * Test that a round bracketed email address is censored
     *
     * e.g. hire(at)aaron(dot)codes
     *
     * @return void
     */
    public function test_round_bracketed_email_addresses()
    {
        $parsed = $this->parse('My email address is hire(at)aaron(dot)codes.');
        $this->assertEquals('<p>My email address is ******.</p>', $parsed->getBody());
    }

    /**
     * Test that a curly bracketed email address is censored
     *
     * e.g. hire{@}aaron{.}codes
     *
     * @return void
     */
    public function test_curly_bracketed_email_addresses()
    {
        $parsed = $this->parse('My email address is hire{@}aaron{.}co{.}uk.');
        $this->assertEquals('<p>My email address is ******.</p>', $parsed->getBody());
    }

    /**
     * Test that regular sentences aren't seen as emails
     *
     * e.g. meet you at somewhere
     *
     * @return void
     */
    public function test_regular_sentences_arent_seen_as_emails()
    {
        $parsed = $this->parse('Meet you at somewhere.');
        $this->assertEquals('<p>Meet you at somewhere.</p>', $parsed->getBody());
    }

    /**
     * Test that simple mobile phone numbers are censored
     *
     * e.g. 07123456789
     *
     * @return void
     */
    public function test_simple_mobile_phone_numbers_are_censored()
    {
        $parsed = $this->parse('My mobile number is 07123456789.');
        $this->assertEquals('<p>My mobile number is ***********.</p>', $parsed->getBody());
    }

    /**
     * Test that spaced mobile phone numbers are censored
     *
     * e.g. 0 7 1 2 3 4 5 6 7 8 9
     *
     * @return void
     */
    public function test_spaced_mobile_phone_numbers_are_censored()
    {
        $parsed = $this->parse('My mobile number is 0 7 1 2 3 4 5 6 7 8 9.');
        $this->assertEquals('<p>My mobile number is ***********.</p>', $parsed->getBody());
    }

    /**
     * Test that multiple numbers are censored
     *
     * e.g. 07123456789/01226 123456
     *
     * @return void
     */
    public function test_multiple_mobile_phone_numbers_are_censored()
    {
        $parsed = $this->parse('My mobile number is 07123456789/01226 123456.');
        $this->assertEquals('<p>My mobile number is ***********/***********.</p>', $parsed->getBody());
    }


    /**
     * Test that +44 mobile phone numbers are censored
     *
     * e.g. +447123456789
     *
     * @return void
     */
    public function test_plus_44_mobile_phone_numbers_are_censored()
    {
        $parsed = $this->parse('My mobile number is +447123456789.');
        $this->assertEquals('<p>My mobile number is ***********.</p>', $parsed->getBody());
    }

    /**
     * Test that (bracketed) phone numbers are censored
     *
     * e.g. (01226) 123456
     *
     * @return void
     */
    public function test_bracketed_numbers_are_censored()
    {
        $parsed = $this->parse('My number is (01226) 123456.');
        $this->assertEquals('<p>My number is ***********.</p>', $parsed->getBody());
    }

    /**
     * Test that hyphenated-phone-numbers are censored
     *
     * e.g. 01234-123-123
     *
     * @return void
     */
    public function test_hyphentated_numbers_are_censored()
    {
        $parsed = $this->parse('My number is 01234-123-123.');
        $this->assertEquals('<p>My number is ***********.</p>', $parsed->getBody());
    }

    /**
     * Test that generic numbers aren't censored
     *
     * e.g. 123
     *
     * @return void
     */
    public function test_generic_numbers_arent_censored()
    {
        $parsed = $this->parse('I can count to 123.');
        $this->assertEquals('<p>I can count to 123.</p>', $parsed->getBody());
    }

    /**
     * Test that dates aren't censored
     *
     * e.g. 01/12/2015
     */
    public function test_dates_arent_censored()
    {
        $parsed = $this->parse('Lets book a lesson for 01/12/2015 ok?');
        $this->assertEquals('<p>Lets book a lesson for 01/12/2015 ok?</p>', $parsed->getBody());

        $parsed = $this->parse('Lets book a lesson for 02-02-2015 ok?');
        $this->assertEquals('<p>Lets book a lesson for 02-02-2015 ok?</p>', $parsed->getBody());

        $parsed = $this->parse('Lets book a lesson for 03.07.2015 ok?');
        $this->assertEquals('<p>Lets book a lesson for 03.07.2015 ok?</p>', $parsed->getBody());
    }

}
