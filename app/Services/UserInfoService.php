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
        $documentsQuery = Documents::where('user_id', $userId)
            ->where('deleted', 0);

        $documentsCount = $documentsQuery->count();

        if ($documentsCount > 1) {
            $documents = $documentsQuery->get();
        } else {
            $documents = $documentsQuery->first();
        }

        $rolesQuery = UserRoles::where('user_id', $userId)
            ->where('user_roles.deleted', 0)
            ->leftJoin('roles', 'user_roles.role_id', '=', 'roles.id')
            ->select('user_roles.*', 'roles.role_name', 'roles.role_desc');

        $rolesCount = $rolesQuery->count();

        if ($rolesCount > 1) {
            $roles = $rolesQuery->get();
        } else {
            $roles = $rolesQuery->first();
        }

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
    public function updateDocuments($request)
    {
        $doc_type = $request->input('doc_type');
        $doc_desc = $request->input('doc_desc');
        $file_path = $request->input('file_path');
        $user_id = $request->input('user_id');

        $document = Documents::where('user_id', $user_id)
            ->where('doc_type', $doc_type)
            ->where('deleted', 0)
            ->first();

        if ($document) {
            $document->doc_desc = $doc_desc;
            $document->doc_url = $file_path;
            $document->save();
        } else {
            Documents::create([
                'user_id' => $user_id,
                'doc_type' => $doc_type,
                'doc_desc' => $doc_desc,
                'doc_url' => $file_path,
            ]);
        }

        return $this->getUserDetails($user_id);
    }
}
