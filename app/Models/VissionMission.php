<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['visi', 'misi'])]
class VissionMission extends Model
{
    use HasFactory;

    protected $table = 'tb_visi_misi';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'visi',
        'misi',
    ];
}
