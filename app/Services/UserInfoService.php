<?php

namespace App\Services;

use App\Models\User;
use App\Models\Contacts;
use App\Models\Documents;
use App\Models\UserRoles;

class UserInfoService
{
    public function getUserDetails($userId)
    {
        $user = User::where('id', $userId)
            ->where('deleted', 0)
            ->select('name', 'email', 'profile_photo_path')
            ->first();
        $contacts = Contacts::where('user_id', $userId)
        ->where('deleted', 0)
        ->first();
        $documents = Documents::where('user_id', $userId)
        ->where('deleted', 0)
        ->first();
        $roles = UserRoles::where('user_id', $userId)
        ->where('deleted', 0)
        ->first();

        return [
            'user_id'=>$userId,
            'user_info' => $user,
            'contact_info' => $contacts,
            'documents_info' => $documents,
            'roles_info' => $roles,
        ];
    }

    public function updateContact($request)
    {
        $userId = isset($request['user_id']) ? (int)$request['user_id'] : null;
        if (!$userId) {
            return false;
        }

        $fields = [
            'country',
            'state',
            'city',
            'area',
            'locality',
            'landmark',
            'street',
            'house_no',
            'pincode',
            'contact_no',
            'emergency_contact_no'
        ];

        $data = [];
        foreach ($fields as $field) {
            if (!empty($request[$field])) {
                $data[$field] = $request[$field];
            }
        }

        if (empty($data)) {
            return false;
        }

        $contact = Contacts::where('user_id', $userId)->where('deleted', 0)->first();

        if ($contact) {
            // Update only non-empty fields
            $contact->fill($data);
            $contact->save();
        } else {
            $data['user_id'] = $userId;
            Contacts::create($data);
        }

        $data = $this->getUserDetails($userId);

        return $data;
    }
    public function updateDocuments($request, $files)
    {
        $userId = isset($request['user_id']) ? (int)$request['user_id'] : null;
        if (!$userId) {
            return false;
        }

        foreach ($files as $docType => $file) {
            if ($file instanceof \Illuminate\Http\UploadedFile) {
            $path = $file->store('documents', 'public');
            dd($path);
            $docDesc = isset($request[$docType . '_desc']) ? $request[$docType . '_desc'] : null;

            $document = Documents::where('user_id', $userId)
                ->where('doc_type', $docType)
                ->where('deleted', 0)
                ->first();

            if ($document) {
                $document->doc_desc = $docDesc;
                $document->doc_url = $path;
                $document->save();
            } else {
                Documents::create([
                'user_id' => $userId,
                'doc_type' => $docType,
                'doc_desc' => $docDesc,
                'doc_url' => $path,
                ]);
            }
            }
        }

        return $this->getUserDetails($userId);
    }
}
