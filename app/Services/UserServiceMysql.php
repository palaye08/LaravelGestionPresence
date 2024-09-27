<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Validator;


class UserServiceMysql
{
    public function store(array $data)
    {
        // dd($data);
        $this->validate($data);

        // Créer l'utilisateur dans MySQL
        return User::create($data);
    }

    protected function validate(array $data)
    {
        $rules = [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string',
            'role' => 'required|in:Admin,Coach,CM,Manager,Apprenant',
            'email' => 'required|email',
            'password' => 'required',
            'photo' => 'nullable|file|image|max:5120', // 5MB max
            'statu' => 'required|in:bloquer,actif',
        ];

    
        $validator = Validator::make($data, $rules);
    
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
    }
    
}
