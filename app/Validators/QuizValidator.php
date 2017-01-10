<?php namespace App\Validators;

use App\Validators\Exceptions\WrongQuizAnswersException;

class QuizValidator extends Validator
{

    /**
     * Validate the given data against $this->rules().
     *
     * @throws WrongQuizAnswersException
     *
     * @param  Array $data
     * @return boolean
     */
    public function validate(array $data)
    {
        $rules     = $this->rules($data);
        $validator = $this->validator->make($data, $rules);

        if ($validator->fails()) {
            throw new WrongQuizAnswersException($validator);
        }

        return true;
    }

    /**
     * Get the validation rules
     *
     * @param  Array $data
     * @return Array
     */
    public function rules(Array $data)
    {
        $rightAnswers = $this->getRightAnswers();

        $rules = [];

        foreach ($rightAnswers as $key => $answer) {
            $rules['answers.'.$key] = ['required', 'in:'.$answer];
        }

        return $rules;
    }

    /**
     * Get right quiz answers
     *
     * @return Array
     */
    private function getRightAnswers()
    {
        $questions = config('quiz');

        $rightAnswers = [];
        foreach($questions as $question) {
            $key = $question['key'];
            $answers = $question['answers'];

            foreach($answers as $answer) {
                if($answer['is_correct']) {
                    $rightAnswers[$key] = $answer['key'];
                }
            }
        }

        return $rightAnswers;
    }

}
