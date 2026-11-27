<?php

namespace App\Models;

use Database\Factories\SuaraPemiluFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id_users', 'id_calon'])]
class SuaraPemilu extends Model
{
    /** @use HasFactory<SuaraPemiluFactory> */
    use HasFactory;

    protected $table = 'tb_suara_pemilu';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_users',
        'id_calon',
    ];

    /**
     * @return BelongsTo<CalonPemilu, $this>
     */
    public function calon(): BelongsTo
    {
        return $this->belongsTo(CalonPemilu::class, 'id_calon');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }
}
