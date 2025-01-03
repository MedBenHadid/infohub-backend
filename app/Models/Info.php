<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Info extends Model
{

    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_id',
        'user_id',
        'keywords',

    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    static public function uploadFile($request)
    {

        $element = new File();

        $file = $request->file('file');
        $file_size = $file->getSize();
        $file_type = $file->getMimeType();
        $element->file_name = $request->file_name;
        $element->file_type = $file_type;
        $element->file_size = $file_size;
        $path = $file->store('public/file');
        $element->file_path = Storage::url($path);
        $element->save();

        return $element->id;
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_info_user', 'info_id', 'user_id');
    }
}
