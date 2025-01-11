<?php

namespace App\Domains\Subscriber\Models;
use App\Models\BaseModel;
use App\Domains\Auth\Models\User;
use App\Models\ContactBaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mail;
/**
 * @property integer $id
 * @property integer $created_by_id
 * @property integer $updated_by_id
 * @property string $email
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 */
class Subscriber extends ContactBaseModel
{
    use SoftDeletes;
        /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['email', 'created_by_id', 'updated_by_id',  'deleted_at', 'created_at', 'updated_at'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */


}
