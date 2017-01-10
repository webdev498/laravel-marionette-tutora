<?php namespace App\Validators;

use App\Search\SubjectSearcher;
use App\Support\StringParser;
use App\Subject;
use App\Repositories\Contracts\MessageRepositoryInterface;

class CustomValidationRules extends \Illuminate\Validation\Validator {

    public function validateNoPhone($attribute, $value, $parameters)
    {
        $parser = app(StringParser::class);
        
        if ( $parser->mightContainPhoneNumber($value) ) return false;

        return true;

    }

    public function validateNoEmail($attribute, $value, $parameters)
    {
        $parser = app(StringParser::class);
        
        if ( $parser->mightContainEmailAddress($value) ) return false;

        return true;

    }

    public function validateMessageNoContact($attribute, $value, $parameters)
    {
        $parser     = app(StringParser::class);

        if (!$parser->mightContainContactDetails($value)) return true;

        $messages   = app(MessageRepositoryInterface::class);
        $message    = $messages->findByUuid($this->data['uuid']);

        if($message && $message->relationship->is_confirmed) return true;

        return false;

    }

    public function validateNoContact($attribute, $value, $parameters)
    {
        $parser = app(StringParser::class);

        if ( $parser->mightContainContactDetails($value) ) return false;

        return true;

    }

    public function validateSubjectWithNameExists($attribute, $value, $parameters)
    {
        $subjectSearcher = app(SubjectSearcher::class);

        try {
            $subjects = $subjectSearcher->search($value);
            $result = $subjects->count() > 0;
        } catch (\Exception $e) {
            $result = false;
        }

        return $result;

    }

}