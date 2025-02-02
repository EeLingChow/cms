<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuditLog extends JsonResource
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
            'admin' => null,
            'action' => $this->action,
            'module' => $this->module,
            'ip' => $this->ip,
            'uri' => $this->uri,
            'postparams' => json_decode($this->postparams, true),
            'meta' => [
                'created'   => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
                'updated'   => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
            ],
        ];

        if ($this->admin) {
            $data['admin'] = new Admin($this->admin);
        }

        return $data;
    }
}
