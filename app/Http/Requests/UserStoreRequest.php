<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\UserRepository;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => [
                'required',
                'string',
                Rule::unique('users')->where(function ($query) {
                    return !$this->userRepository->findByTelephone($this->telephone);
                }),
            ],
            'role' => 'required|in:Admin,Coach,CM,Manager,Apprenant',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->where(function ($query) {
                    return !$this->userRepository->findByEmail($this->email);
                }),
            ],
            'photo' => 'nullable|string',
            'statut' => 'required|in:bloquer,actif',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est requis.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'prenom.required' => 'Le prénom est requis.',
            'prenom.string' => 'Le prénom doit être une chaîne de caractères.',
            'prenom.max' => 'Le prénom ne doit pas dépasser 255 caractères.',
            'adresse.required' => 'L\'adresse est requise.',
            'adresse.string' => 'L\'adresse doit être une chaîne de caractères.',
            'adresse.max' => 'L\'adresse ne doit pas dépasser 255 caractères.',
            'telephone.required' => 'Le numéro de téléphone est requis.',
            'telephone.string' => 'Le numéro de téléphone doit être une chaîne de caractères.',
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'role.required' => 'Le rôle est requis.',
            'role.in' => 'Le rôle doit être Admin, Coach, CM ou Manager.',
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'statut.required' => 'Le statut est requis.',
            'statut.in' => 'Le statut doit être bloquer ou actif.',
        ];
    }

    protected function prepareForValidation()
    {
        // Assurez-vous que le UserRepository est correctement injecté
        if (!$this->userRepository) {
            $this->userRepository = app(UserRepository::class);
        }
    }
}