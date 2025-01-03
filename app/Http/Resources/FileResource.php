<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
                'id' => $this->id,
                'file_name' => isset($this->file_name) ? $this->file_name : 'default_file_name.txt',
                'file_path' => isset($this->file_path) ? basename($this->file_path) : null,
                'file_type' => $this->file_type, 
                'file_size' => isset($this->file_size) ? $this->file_size . ' bytes' : 'N/A',
                'uploaded_by' => new UserResource($this->uploadedBy), 
                'uploaded_by_user_id' => $this->uploadedBy->id ?? null, 
                'related_info' => new InfoResource($this->relatedInfo), 
                'related_info_id' => $this->related_info_id ?? null,
         ];
    }
}
