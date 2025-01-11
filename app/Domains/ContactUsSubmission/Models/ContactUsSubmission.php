<?php

namespace App\Domains\ContactUsSubmission\Models;
use App\Domains\Auth\Models\User;
use App\Models\BaseModel;
use App\Models\ContactBaseModel;
use App\Models\Traits\CreatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
//use App\Mail\ContactMail;
use Mail;
/**
 * @property integer $id
 * @property string $name
 * @property string $phone_number
 * @property string $email
 * @property string $subject
 * @property string $message
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class ContactUsSubmission extends ContactBaseModel
{

    use SoftDeletes,HasFactory ,
        LogsActivity;


    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['message','name','email','subject','phone_number', 'created_at', 'updated_at', 'deleted_at'];


//    public static function boot() {
//
//        parent::boot();
//
//        static::created(function ($item) {
//
//            $adminEmail = Setting::first()->admin_email;
//            Mail::to($adminEmail)
//                ->cc(['omar.vibessolutions@gmail.com'])
//                ->send(new ContactMail($item));
//        });
//    }
}
