<?php
namespace App\Domains\Highlight\Models;
use App\Domains\Lookups\Models\Category;
use App\Domains\Merchant\Models\Merchant;
use App\Domains\Service\Models\Service;
use App\Models\BaseModel;
use App\Domains\Auth\Models\User;

class Highlight extends BaseModel
{
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = [ 'type','category_id','service_id','merchant_id','status','created_by_id', 'updated_by_id', 'title', 'title_ar' ,'description','description_ar', 'image','link','created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }
}
