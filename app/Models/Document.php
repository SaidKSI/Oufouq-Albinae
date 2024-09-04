<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;
    protected $table = 'documents';
    protected $fillable = ['path'];
    public function documentable()
    {
        return $this->morphTo();
    }
    protected static function booted()
    {
        self::deleted(function (Document $song) {
            Storage::disk('public')->delete($song->path);
        });
    }
}