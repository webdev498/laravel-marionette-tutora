<?php namespace App\Repositories\Contracts;

use App\User;
use App\UserBackgroundCheck;

interface BackgroundCheckRepositoryInterface
{

    /**
     * Persist to the database
     *
     * @param  UserBackgroundCheck $backgroundCheck
     *
     * @return UserBackgroundCheck|null
     */
    public function save(
        UserBackgroundCheck $backgroundCheck
    );

    /**
     * @param UserBackgroundCheck $background
     *
     * @return bool
     */
    public function delete(UserBackgroundCheck $background);

    /**
     * Count the number of actual checks by a given user
     *
     * @param  User $user
     *
     * @return integer
     */
    public function countActualBackgroundChecksForUser(User $user);

    /**
     * @param \DateTime $expirationDate
     *
     * @return mixed
     */
    public function getExpiringOnDateBackgrounds(\DateTime $expirationDate);

    /**
     * @param User $user
     * @param $type
     *
     * @return UserBackgroundCheck
     */
    public function getUserBackgroundCheckByType(User $user, $type);

    /**
     * Generate a uuid, ensuring it is in fact unique to the entity
     *
     * @return string
     */
    function generateUuid();

    /**
     * Return the number of entities that have a given uuid
     *
     * @param string $uuid
     *
     * @return integer
     */
    public function countByUuid($uuid);

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param $uuid
     *
     * @return mixed
     */
    public function findByUuid($uuid);
}
