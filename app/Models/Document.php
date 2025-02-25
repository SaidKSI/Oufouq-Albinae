<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;
    protected $table = 'documents';
    protected $fillable = ['path', 'name', 'documentable_id', 'documentable_type'];
    public function documentable()
    {
        return $this->morphTo();
    }
    protected static function booted()
    {
        static::deleting(function (Document $document) {
            Storage::disk('public')->delete($document->path);
        });
    }
}