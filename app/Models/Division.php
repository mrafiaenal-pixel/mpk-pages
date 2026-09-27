<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_sekbid'])]
class Division extends Model
{
    use HasFactory;

    protected $table = 'tb_sekbid';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_sekbid',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(DivisionMember::class, 'id_sekbid', 'id');
    }

    public function anggota(): HasMany
    {
        return $this->members();
    }
}
