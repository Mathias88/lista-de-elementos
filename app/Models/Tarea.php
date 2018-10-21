<?php

namespace App\Models;

use App\Scopes\OrderPositionScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Tarea extends Model
{
    protected $table = 'tareas';

    protected $fillable = [
        'image',
        'description',
        'position',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new OrderPositionScope());
    }

    public function getUrlImageAttribute(): string
    {
        $value = str_replace('public/', '', $this->image);

        return asset("storage/" . $value);
    }

    public function uploadImage($image): string
    {
        return $image->store('public');
    }

    public function getNewPosition(): int
    {
        $tarea = $this->select('position')->orderBy('position', 'DESC')->first();

        return $tarea ? $tarea->position + 1 : 1;
    }

    public function deleteImage(): void
    {
        Storage::delete($this->image);
    }
}
