<?php
namespace App\Domains\ContactUsSubmission\Services;
use App\Domains\ContactUsSubmission\Models\ContactUsSubmission;
use App\Exceptions\GeneralException;
use App\Services\BaseService;
use App\Services\StorageManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ContactUsSubmissionService
 */
class ContactUsSubmissionService extends BaseService
{
    /**
     * @var $entityName
     */
    protected $entityName = 'ContactUsSubmission';


    /**
     * @param ContactUsSubmission $contactUsSubmission
     */
    public function __construct(ContactUsSubmission $contactUsSubmission)
    {
        $this->model = $contactUsSubmission;
    }
    public function store(array $data = []): ContactUsSubmission
    {
//        auth()->user()->sendContactEmail( $this->model);
        DB::beginTransaction();
        try {
            $contactUsSubmission = $this->model::create($data);
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            throw new GeneralException(__('There was a problem creating the Contact Us .'));
        }
        DB::commit();
        return $contactUsSubmission;
    }
}
