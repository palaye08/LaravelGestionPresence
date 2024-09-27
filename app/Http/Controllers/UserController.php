<?php
namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\UserServiceMysql;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected $service;
    protected $serviceMysql;

    public function __construct(UserService $service, UserServiceMysql $serviceMysql)
    {
        $this->service = $service;
        $this->serviceMysql = $serviceMysql;
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // Commencer une transaction
        DB::beginTransaction();

        try {
            // Créer l'utilisateur dans Firebase

            $data['password'] = bcrypt($data['password']); 
            
            $mysqlUser = $this->serviceMysql->store($data);
            
            
            $firebaseUser = $this->service->store($data);
           

            // Si les deux insertions réussissent, valider la transaction
            DB::commit();

            return response()->json([
                'firebase_user' => $firebaseUser,
                'mysql_user' => $mysqlUser,
            ], 201);
            
        } catch (\Exception $e) {
            // Si une erreur se produit, annuler la transaction
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function index(Request $request)
    {
        $role = $request->query('role');

        if ($role) {
            $users = $this->service->getUsersByRole($role);
        } else {
            $users = $this->service->getAllUsers();
        }

        return response()->json($users);
    }

    public function update(Request $request, $id)
    {
        // dd($request, $id);
        $data = $request->all();
        

        try {
            $user = $this->service->updateUser($id, $data);
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


}
