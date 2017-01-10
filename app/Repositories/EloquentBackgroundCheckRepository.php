<?php

namespace App\Repositories;

use App\User;
use App\UserBackgroundCheck;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\DatabaseManager as Database;
use App\Database\Exceptions\ResourceNotPersistedException;
use App\Repositories\Contracts\BackgroundCheckRepositoryInterface;

class EloquentBackgroundCheckRepository extends AbstractEloquentRepository implements BackgroundCheckRepositoryInterface
{
    /**
     * @var UserBackgroundCheck
     */
    protected $backgroundCheck;

    /**
     * @var Database
     */
    protected $database;

    /**
     * Create an instance of the repository
     *
     * @param  UserBackgroundCheck $backgroundCheck
     * @param  Database            $database
     */
    public function __construct(
        UserBackgroundCheck $backgroundCheck,
        Database            $database
    )
    {
        $this->backgroundCheck = $backgroundCheck;
        $this->database        = $database;
    }

    /**
     * Count the number of background checks.
     *
     * @return integer
     */
    public function count()
    {
        return $this->backgroundCheck
            ->count();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->backgroundCheck->find($id);
    }

    /**
     * @param $uuid
     *
     * @return mixed
     */
    public function findByUuid($uuid)
    {
        return $this->backgroundCheck->where('uuid', $uuid)->first();
    }

    /**
     * Get a page of background checks.
     *
     * @param  integer $page
     * @param  integer $perPage
     *
     * @return Collection
     */
    public function getPage($page, $perPage)
    {
        return $this->backgroundCheck
            ->with($this->with)
            ->takePage($page, $perPage)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Count the number of pending background checks.
     *
     * @return integer
     */
    public function countPending()
    {
        return $this->backgroundCheck
            ->where('admin_status', '=', UserBackgroundCheck::ADMIN_STATUS_PENDING)
            ->count();
    }

    /**
     * Get a page of pending background checks.
     *
     * @param  integer $page
     * @param  integer $perPage
     *
     * @return Collection
     */
    public function getPendingPage($page, $perPage)
    {
        return $this->backgroundCheck
            ->with($this->with)
            ->where('admin_status', '=', UserBackgroundCheck::ADMIN_STATUS_PENDING)
            ->takePage($page, $perPage)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Count the number of expired background checks.
     *
     * @return integer
     */
    public function countExpired()
    {
        $expiredDate = UserBackgroundCheck::getExpiredDate();

        return $this->backgroundCheck
            ->where('issued_at', '<', $expiredDate)
            ->count();
    }

    /**
     * @param \DateTime $expirationDate
     *
     * @return mixed
     */
    public function getExpiringOnDateBackgrounds(\DateTime $expirationDate)
    {
        $issuedAt = $expirationDate->modify('-3 year');

        return $this->backgroundCheck
            ->where('issued_at', '=', $issuedAt)
            ->where('admin_status', '=', UserBackgroundCheck::ADMIN_STATUS_APPROVED)
            ->get();
    }

    /**
     * Get a page of expired background checks.
     *
     * @param  integer $page
     * @param  integer $perPage
     *
     * @return Collection
     */
    public function getExpiredPage($page, $perPage)
    {
        $expiredDate = UserBackgroundCheck::getExpiredDate();

        return $this->backgroundCheck
            ->with($this->with)
            ->where('issued_at', '<', $expiredDate)
            ->takePage($page, $perPage)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Persist to the database
     *
     * @param  UserBackgroundCheck $backgroundCheck
     *
     * @return UserBackgroundCheck|null
     */
    public function save(
        UserBackgroundCheck $backgroundCheck
    ) {

        return $this->database->transaction(function () use (
            $backgroundCheck
        ) {
            // UserBackgroundCheck
            if ( ! $backgroundCheck->save()) {
                throw new ResourceNotPersistedException();
            }

            return $backgroundCheck;
        });
    }

    /**
     * @param UserBackgroundCheck $background
     *
     * @return bool
     */
    public function delete(UserBackgroundCheck $background)
    {
        return $background->delete();
    }

    /**
     * Count the number of actual checks by a given user
     *
     * @param  User $user
     *
     * @return integer
     */
    public function countActualBackgroundChecksForUser(User $user)
    {
        $expiredDate = UserBackgroundCheck::getExpiredDate();

        return $this->backgroundCheck
            ->newQuery()
            ->where('user_id', '=', $user->id)
            ->where('admin_status', '=', UserBackgroundCheck::ADMIN_STATUS_APPROVED)
            ->where('issued_at', '>=', $expiredDate)
            ->count();
    }

    /**
     * @param User $user
     * @param $type
     *
     * @return UserBackgroundCheck
     */
    public function getUserBackgroundCheckByType(User $user, $type)
    {
        return $this->backgroundCheck
            ->newQuery()
            ->hasUser($user)
            ->where('type', '=', $type)
            ->with('image')
            ->first();
    }

    /**
     * Generate a uuid, ensuring it is in fact unique to the entity
     *
     * @return string
     */
    public function generateUuid()
    {
        do {
            $uuid = str_uuid();
        } while ($this->countByUuid($uuid) > 0);

        return $uuid;
    }

    /**
     * Return the number of entities that have a given uuid
     *
     * @param string $uuid
     *
     * @return integer
     */
    public function countByUuid($uuid)
    {
        return $this->backgroundCheck
            ->whereUuid($uuid)
            ->count();
    }

}
