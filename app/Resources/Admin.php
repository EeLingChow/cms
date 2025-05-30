<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Admin extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'username' => $this->username,
            'fullname' => $this->fullname,
            'is_superadmin' => $this->is_superadmin,
            'profile' => null,
            'meta' => [
                'created'   => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
                'updated'   => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
            ],
        ];

        if ($this->profile) {
            $data['profile'] = new Profile($this->profile);
        }

        return $data;
    }
}
