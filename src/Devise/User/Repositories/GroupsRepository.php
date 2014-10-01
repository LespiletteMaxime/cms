<?php namespace Devise\User\Repositories;

use Group, User;

class GroupsRepository
{
    protected $Group, $User;

    public function __construct(Group $Group, User $User)
    {
        $this->Group = $Group;
        $this->User = $User;
    }

    /**
     * Find group by id
     *
     * @param  int  $id
     * @return Group
    */
    public function findById($id)
    {
        return $this->Group->with('users')->findOrFail($id);
    }

    /**
     * Find group by name
     *
     * @param  string  $name
     * @return Group
    */
    public function findByName($name)
    {
        return $this->Group->with('users')->whereName($name)->first();
    }

    /**
     * List all the groups
     *
     * @return array
     */
    public function groupList()
    {
        return $this->Group->lists('name', 'id');
    }

    /**
     * Paginated list of groups
     *
     * @return Eloquent\Collection
     */
    public function groups()
    {
        return $this->Group->paginate();
    }

    /**
     * Get a list of groups for the given user id
     *
     * @param  integer $userId
     * @return array
     */
    public function groupListForUser($userId)
    {
        return $this->User->findOrFail($userId)->groups()->lists('name', 'id');
    }
}