<?php

namespace App\Models;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Kreait\Firebase\Exception\DatabaseException;

abstract class FirebaseBaseModel
{
    protected $database;
    protected $tableName;

    public function __construct()
    {
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
        try {
            $factory = (new Factory)
            ->withServiceAccount($credentiels)
            ->withDatabaseUri('https://fir-8463f-default-rtdb.firebaseio.com/');
            
            $this->database = $factory->createDatabase();
            $this->tableName = $this->getTable();
            
            $this->database->getReference($this->tableName)->getValue();
        } catch (DatabaseException $e) {
            throw new \Exception("Impossible de se connecter à Firebase: " . $e->getMessage());
        }
    }

    abstract public function getTable();

    public function create(array $data)
    {
        $reference = $this->database->getReference($this->tableName)->push($data);
        return $reference->getKey();
    }

    public function find($id)
    {
        $referentiel = $this->database->getReference($this->tableName)->getChild($id)->getValue();
        
       
        if (!$referentiel) {
            return null;
        }
    
        return $referentiel;
    }
    
    public function updateReferentiel($id, $data)
    {
        $this->database->getReference($this->tableName)->getChild($id)->set($data);
    }
    


    public function all()
    {    
        $result = $this->database->getReference($this->tableName)->getValue();
        return $result ?: [];
    }

    public function update($id, array $data)
    { 
        $this->database->getReference($this->tableName)->getChild($id)->update($data);
        return $this->find($id);
    }   

    public function delete($id)
    {
        return $this->database->getReference($this->tableName)->getChild($id)->remove();
    }

    public function restore($id){
        $this->database->getReference($this->tableName)->getChild($id)->update(['statut' => 'actif']);
        return $this->find($id);
    }
    public function softDelete($id)
    {
        $this->database->getReference($this->tableName)->getChild($id)->update(['etat' => 'inactif']);
        
        return $this->find($id);
    }
    
}