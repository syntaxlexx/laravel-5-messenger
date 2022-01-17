<?php

namespace Lexx\ChatMessenger\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => (int) $this->id,
            'body'              => $this->body,
            'user_id'           => $this->user_id,
            'thread_id'         => $this->thread_id,
            
            'name'              => optional($this->user)->name ?? null,
            'user_avatar'       => optional($this->user)->avatar_url,

            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
            'deleted_at'        => $this->deleted_at,
        ];
    }
}
