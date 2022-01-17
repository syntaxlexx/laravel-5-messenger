<?php

namespace Lexx\ChatMessenger\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThreadResource extends JsonResource
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
            'subject'           => $this->subject,
            'slug'              => $this->slug,
            'start_date'        => $this->start_date,
            'end_date'          => $this->end_date,
            'max_participants'  => $this->max_participants,
            'avatar'            => $this->avatar,

            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
            'deleted_at'        => $this->deleted_at,

            'unread_count'      => $this->userUnreadMessagesCount(auth()->id()),
            'latest_message'    => optional($this->latestMessage)->body ?? null,
            'creator'           => optional($this->creator())->name,
            'creator_avatar'    => optional($this->creator())->avatar_url,
            'participants'      => $this->participantsString(auth()->id()),

            'messages'          => MessageResource::collection($this->whenLoaded('messages')),
        ];
    }
}
