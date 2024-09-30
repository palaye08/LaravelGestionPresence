<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth as FirebaseAuth;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Auth;

class AuthServiceFirebase implements AuthServiceInterface
{

    protected $auth;

    public function __construct()
    {
        try {
            $credentiels=json_decode(base64_decode("ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAiZmlyLTg0NjNm
IiwKICAicHJpdmF0ZV9rZXlfaWQiOiAiMjlkOWYwZjYwMDFkZTNlMWU4MzA1NGZkZTVhMTMyMTFk
OTQ4ODUyNCIsCiAgInByaXZhdGVfa2V5IjogIi0tLS0tQkVHSU4gUFJJVkFURSBLRVktLS0tLVxu
TUlJRXZRSUJBREFOQmdrcWhraUc5dzBCQVFFRkFBU0NCS2N3Z2dTakFnRUFBb0lCQVFDYnhSK3B4
QkVZbGdmeVxuNlZCMlRJOWMvR3FEVUhuY29xRWw1dGIzQUM3bHNIL3AwUCtSSG1FL2xWVEEwTnJY
Q0dtWXE3RStnaXJKYVZCZFxuNnVBZlAzWEFNWCt6OUhJM0RYQXR4YWdXNTF2K0NZTUNaQlYxR05V
d1ZNUDN0ZmswSzVEZ1g5MUxWWFJCVHovR1xuVDJrR0ZJQjNLSTV4OWdjL3BiUUF0dmxpcnd4aFVF
N1lHR1dKem5RYVFseXJuSXNGQXdYSzh6ZlJmTW04RjVreVxuNlU3QzVhUUdhY0NtRGVSbG9qNk9i
OE9haFZvbWcvT1B4bURZR01JRlljMDlydXA0bElrMnNwbjhtVVFMYjdmd1xuZHhvN2ZZc042TUZO
OUJpRjAvdklsZVYwQnFkN0krOGx3bkU4RXR1aHhKSjNEQ3gwRzkyN0EyVERTK3VSc2Q4T1xuR1V6
T0RHMkxBZ01CQUFFQ2dnRUFUUnI4QytST1ZCMjc3STE2TXp5OWdGay8vaVZCVlNvNVc1SVRHV3dC
U3RnZFxuMFNjUHdvMUh0Um9kdkY0RjNZaEFBUDhIK3ZtaTVWVVluNHlxaVQwMzg3MXN5YTY0TkxF
VnRNcVE1Rmw4cTFpWVxuL1g5K01acnJ1SU5WQjlLUGV6Z1BmRWxudUtraHBVeHR0S1BOU0dHd240
czNTNGp0Mko4VTVYK3RIYUNwbjZkRFxuZlFMV2V4NW93aUhUTjc0YVNxZzhGRWo1NjZGSnpGb05i
RjJnY29qbVRvbjl4SjMxckpxR0VYeFFqTXVMOWtqbFxuVWM1SHNQczVORE9Td25aMHBLZmlTT2R4
dVQydXdFQjl6YW8ySWw0c2JMdFBRQVFPWVlVMlNjNUEyTlZUVTJVOVxuZ3dqMmF5ZmRkdWFxR1hD
dStEK1grMFc3MHEvMk1paTRkT09sNkoxeHlRS0JnUURUMi9uek8rNlVqWDBTNUxGTVxuKzc2SFIz
Uml2ZUdPdVNxYkwrSDlOQTRNRGNBeG9FM2xmSWhKMEI1MlZTWTVsYmFMQ1krYTNiOTJxNGdGZnJX
MVxuTkdGS3l0NEVwV2Vva0ZUdXJlVUhYL25sYTB6elVtNUpOQlR4MkYxc0EvYUwxdkhucXA2TUNC
SWdyU0JqdTNiVlxuUzduaE5qV1VTYURJWDBKcUxVS2M1Nmc4Y3dLQmdRQzhPWDNTV0xIRzJuLy95
MzVPUGpicFNhVzJKRDVobDFzUVxuRm5EakNiNWF0Z3VFQ20rSGNsQ2lURnJ6NUhmL3NBU1BiVmp6
Ymg2cy85ZnZHWmVpWnpiR2s1N3UwTURRNlBCU1xuZ0djL3NBN1lNeXczZ3VDRG1keGRXcHlhVWdY
bk5weUVIMzk0bWVYZS9sQ3RFcUI3MldYRXZGaCtHeE9Ma1R3dFxuY2RtQWdacWNpUUtCZ0VIZzVu
Ri9VbW41TVVwZHVOQmllOVdmSDQ3UTBzSEp6SGpqUTllemh5YXlZL0JLazg1blxua2cyNTNLOFpQ
VUNzMEUyZDFIem90Vy9XV1A2MGJ4YUF5and5cHlVMHRlNFJxNGRvcFk1TWh1MlRGeU9XbjFxK1xu
Q2tGRjhZNzBFSWRkZUcxOGZiNjNoOE9IY2tudzhqdlRSMkxEczVkLzZiSkZCT2IxV2dCbWZNY0hB
b0dBVGw2Rlxubzhvb0l3cWcxc2xNaXBsZTFaMURjT1lBVHpQc3gzTUFoNWRyK1dyb3VvNTV4MlJ5
YkZEc1liWVR2SkwySXgyOVxuTm9YNEljSFlqNFlSVzU0cjhWeFBoVkhIcG5RR2MrTmdtZVRkR1dt
ZEZIUGF0UkNmN3dLbVI1NjFOSUxKZ2oxM1xuSHFpOVRDNkpPcmFSdVY5WC95VUlsMkVBcjFER1JJ
bXRyTkxKcERrQ2dZRUF0NmczTmk0cE9xVjg1bkVBU1FRc1xuS2ZmbEZBbHFKNTFuditjcUEyVCtD
dXFtLy93cGdQc2VTRVhnTyt4WGRxV3g4N0huQ0JTelUvRjVUOGQvN2tGU1xuK3liWW05K0JFcDZM
U1pNWTR6L0ZxVER1UmQ1TGhSMUFFUDJvUXNlU3pVWFRKcG0vNWwzUlg0M0dibTZHQlI3T1xuOUNP
VDRPTnBmUnY4WGQ3eHMwZW54cGM9XG4tLS0tLUVORCBQUklWQVRFIEtFWS0tLS0tXG4iLAogICJj
bGllbnRfZW1haWwiOiAiZmlyZWJhc2UtYWRtaW5zZGstbHl2bDRAZmlyLTg0NjNmLmlhbS5nc2Vy
dmljZWFjY291bnQuY29tIiwKICAiY2xpZW50X2lkIjogIjExMzY0MzgwMDA2ODg1MTA4MDQ4MiIs
CiAgImF1dGhfdXJpIjogImh0dHBzOi8vYWNjb3VudHMuZ29vZ2xlLmNvbS9vL29hdXRoMi9hdXRo
IiwKICAidG9rZW5fdXJpIjogImh0dHBzOi8vb2F1dGgyLmdvb2dsZWFwaXMuY29tL3Rva2VuIiwK
ICAiYXV0aF9wcm92aWRlcl94NTA5X2NlcnRfdXJsIjogImh0dHBzOi8vd3d3Lmdvb2dsZWFwaXMu
Y29tL29hdXRoMi92MS9jZXJ0cyIsCiAgImNsaWVudF94NTA5X2NlcnRfdXJsIjogImh0dHBzOi8v
d3d3Lmdvb2dsZWFwaXMuY29tL3JvYm90L3YxL21ldGFkYXRhL3g1MDkvZmlyZWJhc2UtYWRtaW5z
ZGstbHl2bDQlNDBmaXItODQ2M2YuaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJ1bml2ZXJz
ZV9kb21haW4iOiAiZ29vZ2xlYXBpcy5jb20iCn0="),true);
            $factory = (new Factory)
                        ->withServiceAccount($credentiels)
                        ->withDatabaseUri('https://fir-8463f-default-rtdb.firebaseio.com/');
    
            $this->auth = $factory->createAuth();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la configuration Firebase: ' . $e->getMessage());
            throw new \Exception('Erreur lors de la configuration Firebase');
        }
    }
    


    public function authenticate($credentials)
    {
        try {
            // Récupérer l'utilisateur par email

            $user = $this->auth->getUserByEmail($credentials['email']);
            // Vérifier le mot de passe
            // Note: Firebase Admin SDK ne fournit pas de méthode directe pour vérifier le mot de passe
            // Vous devrez implémenter votre propre logique de vérification ou utiliser une autre approche
    
            // Si l'authentification réussit, créer un token personnalisé
            $customToken = $this->auth->createCustomToken($user->uid);
    
            return [
                'access_token' => $customToken->toString(),
                'token_type' => 'Bearer',
                'expires_at' => time() + 3600, // Expire dans 1 heure
                'custom_claims' => [
                    'user_id' => $user->uid,
                    'email' => $user->email,
                    'role' => $user->customClaims['role'] ?? 'user',
                ],
            ];
        } catch (\Exception $e) {
            Log::error('Erreur d\'authentification Firebase: ' . $e->getMessage());
            return null;
        }
    }
}