<?php

namespace App\Repositories;

use App\Models\FirebaseModel;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(FirebaseModel $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function all()
    {
        return $this->model->all();
    }

        public function update($id, array $data)
        {
            return $this->model->update($id, $data);
        }

    public function delete($id)
    {
        return $this->model->delete($id);
    }

    public function findByTelephone($telephone)
    {
        $users = $this->model->all();
        foreach ($users as $id => $user) {
            if ($user['telephone'] === $telephone) {
                return $user;
            }
        }
        return null;
    }

    public function findByEmail($email)
    {
        $users = $this->model->all();
        foreach ($users as $id => $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }
        return null;
    }
    public function findByRole($role)
    {
        $users = $this->model->all();
        $filteredUsers = [];

        foreach ($users as $id => $user) {
            if ($user['role'] === $role) {
                $filteredUsers[] = $user;
            }
        }

        return $filteredUsers;
    }

}