<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage;

class UserService
{
    protected $repository;
    protected $storage;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
        
        // Initialiser Firebase Storage
        $firebaseCredentialsPath = storage_path('app/firebase/firebase_credentials.json');
        
        if (!file_exists($firebaseCredentialsPath)) {
            throw new \Exception('Le fichier de configuration Firebase n\'existe pas.');
        }

        $factory = (new Factory)->withServiceAccount($firebaseCredentialsPath);
        $this->storage = $factory->createStorage();
    }

    // Le reste du code reste inchangé...

    public function store(array $data)
    {
        $this->validate($data);
        
        // Hash du mot de passe (optionnel si tu l'utilises pour d'autres logiques)
         $data['password'] = bcrypt($data['password']);
    
        // Traiter et uploader la photo si elle existe
        if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
            $photoUrl = $this->uploadPhotoToFirebase($data['photo'], $data['email']);
            $data['photo_url'] = $photoUrl;
        }
    
        // Supprimer l'objet UploadedFile de $data avant de créer l'utilisateur
        unset($data['photo']);
    
        // Créer l'utilisateur dans Firebase Authentication
        $firebaseAuth = (new Factory)->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'))->createAuth();
    
        try {
            $firebaseUser = $firebaseAuth->createUser([
                'email' => $data['email'],
                'password' => $data['password'], // Mot de passe brut pour Firebase
                'displayName' => $data['nom'] . ' ' . $data['prenom'],
                'emailVerified' => false,
                'disabled' => false,
                'photoURL' => $data['photo_url'] ?? null,
                'customClaims' => [
                    'role' => $data['role'], // Optionnel : ajouter des claims personnalisés
                ],
            ]);
    
            // Ajouter l'utilisateur dans ta base de données
            $data['firebase_uid'] = $firebaseUser->uid; // Associer le UID Firebase à l'utilisateur
            return $this->repository->create($data);
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de la création de l\'utilisateur dans Firebase: ' . $e->getMessage());
        }
    }
    

    protected function uploadPhotoToFirebase($photo, $email)
    {
        $fileName = time() . '_' . $email . '.' . $photo->getClientOriginalExtension();
        $filePath = 'users/' . $fileName;
        $bucket = $this->storage->getBucket();
        // dd($bucket);
        $bucket->upload(
            file_get_contents($photo->getRealPath()),
            ['name' => $filePath]
        );

        return $bucket->object($filePath)->signedUrl(new \DateTime('+ 1000 years'));
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

        // Vérification manuelle de l'unicité
        if ($this->repository->findByTelephone($data['telephone'])) {
            throw new \Exception('Le numéro de téléphone est déjà utilisé.');
        }

        if ($this->repository->findByEmail($data['email'])) {
            throw new \Exception('L\'adresse email est déjà utilisée.');
        }
    }
    public function getAllUsers()
    {
        return $this->repository->all();
    }

    public function getUsersByRole($role)
    {
        return $this->repository->findByRole($role);
    }

    public function updateUser($id, array $data)
    { 
        // dd($this->validate($data));
        
        $this->validate($data);

        return $this->repository->update($id, $data);
    }
    
    

}