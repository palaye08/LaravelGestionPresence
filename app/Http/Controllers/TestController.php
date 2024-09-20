<?php
namespace App\Http\Controllers;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function TestMethod()
    {
        try {
            $database = $this->firebaseService->connect();
            $database->getReference('GestionPresence/users')->set([
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
            ]);
            return 'Test réussi';
        } catch (\Exception $e) {
            Log::error('Error in TestMethod: ' . $e->getMessage());
            return 'Erreur: ' . $e->getMessage();
        }
    }
}