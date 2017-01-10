<?php

namespace App\Transformers\Traits;

use App\UserRequirement;

trait UserRequirementTrait
{
    /**
     * The order that the requirements should appear in.
     *
     * @var array
     */
    protected $order = [
        'tagline',
        'rate',
        'bio',
        'profile_picture',
        'subjects',
        'qualifications',
        'travel_policy',
        'quiz_questions',
        'personal_video',
        'qualified_teacher_status',
        'date_of_birth',
        'bank_account',
        'address_billing',
        'identity_document',
        'background_check'
    ];

    /**
     * Get the url for the given requirement
     *
     * @param  UserRequirement $requirement
     *
     * @return string|null
     */
    protected function getRequirementUrl(UserRequirement $requirement)
    {
        $method = camel_case("get_{$requirement->name}_requirement_url");

        if (method_exists(
            $this,
            $method
        )) {
            return $this->{$method}($requirement);
        }
    }

    /**
     * Get the url for the given requirement
     *
     * @param  UserRequirement $requirement
     *
     * @return string|null
     */
    protected function getTaglineRequirementUrl(UserRequirement $requirement)
    {
        return $this->getProfileRequirementUrl(
            $requirement,
            'tagline'
        );
    }

    /**
     * Get the url for the given requirement
     *
     * @param  UserRequirement $requirement
     *
     * @return string|null
     */
    protected function getRateRequirementUrl(UserRequirement $requirement)
    {
        return $this->getProfileRequirementUrl(
            $requirement,
            'rate'
        );
    }

    /**
     * Get the url for the given requirement
     *
     * @param  UserRequirement $requirement
     *
     * @return string|null
     */
    protected function getBioRequirementUrl(UserRequirement $requirement)
    {
        return $this->getProfileRequirementUrl(
            $requirement,
            'bio'
        );
    }

    /**
     * Get the url for the given requirement
     *
     * @param  UserRequirement $requirement
     *
     * @return string|null
     */
    protected function getProfilePictureRequirementUrl(UserRequirement $requirement)
    {
        return $this->getProfileRequirementUrl(
            $requirement,
            'pic'
        );
    }

    /**
     * Get the url for the given requirement
     *
     * @param  UserRequirement $requirement
     *
     * @return string|null
     */
    protected function getSubjectsRequirementUrl(UserRequirement $requirement)
    {
        return $this->getProfileRequirementUrl(
            $requirement,
            'subjects'
        );
    }

    /**
     * Get the url for the given requirement
     *
     * @param  UserRequirement $requirement
     *
     * @return string|null
     */
    protected function getQualificationsRequirementUrl(UserRequirement $requirement)
    {
        return $this->getProfileRequirementUrl(
            $requirement,
            'qualifications'
        );
    }

    /**
     * Get the url for the given requirement
     *
     * @param  UserRequirement $requirement
     *
     * @return string|null
     */
    protected function getTravelPolicyRequirementUrl(UserRequirement $requirement)
    {
        return $this->getProfileRequirementUrl(
            $requirement,
            'travel_policy'
        );
    }

    /**
     * Get the url for the given requirement
     *
     * @param  UserRequirement $requirement
     *
     * @return string|null
     */
    protected function getQualifiedTeacherStatusRequirementUrl(UserRequirement $requirement)
    {
        return $this->getProfileRequirementUrl(
            $requirement,
            'qts'
        );
    }

    /**
     * Get the url for the given requirement
     *
     * @param  UserRequirement $requirement
     *
     * @return string|null
     */
    protected function getQuizQuestionsRequirementUrl(UserRequirement $requirement)
    {
        return $this->getProfileRequirementUrl(
            $requirement,
            'quiz_questions'
        );
    }

    /**
     * Get the url for the given requirement and section
     *
     * @param  UserRequirement $requirement
     * @param  string          $section
     *
     * @return string|null
     */
    protected function getProfileRequirementUrl(UserRequirement $requirement, $section)
    {
        return relroute(
            'tutor.profile.show',
            [
                'uuid' => $requirement->tutor->uuid,
                'section' => $section,
            ]
        );
    }

    /**
     * Get the url for the given requirement
     *
     * @param  UserRequirement $requirement
     *
     * @return string|null
     */
    protected function getPaymentDetailsRequirementUrl(UserRequirement $requirement)
    {
        return relroute('tutor.account.payment.index');
    }

    /**
     * Get the url for the given requirement
     *
     * @param  UserRequirement $requirement
     *
     * @return string|null
     */
    protected function getIdentificationRequirementUrl(UserRequirement $requirement)
    {
        return relroute('tutor.account.identification.index');
    }

    protected function getBackgroundCheckRequirementUrl(UserRequirement $requirement)
    {
        return $this->getProfileRequirementUrl(
            $requirement,
            'background_check'
        );
    }

    protected function getPersonalVideoRequirementUrl(UserRequirement $requirement)
    {
        return $this->getProfileRequirementUrl(
            $requirement,
            'video'
        );
    }
}
