<?php

namespace App\Domains\Highlight\Services;
use App\Services\BaseService;
use App\Services\StorageManagerService;
use App\Exceptions\GeneralException;
use App\Domains\Highlight\Models\Highlight;

class HighlightService extends BaseService
{
    /**
     * @var string $entityName
     */
    protected $entityName = 'Highlight';

    /**
     * @var StorageManagerService $storageManagerService
     */
    protected $storageManagerService;

    /**
     * @param Highlight $highlight
     * @param StorageManagerService $storageManagerService
     */
    public function __construct(Highlight $highlight, StorageManagerService $storageManagerService)
    {
        $this->model = $highlight;
        $this->storageManagerService = $storageManagerService;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws GeneralException
     * @throws \Throwable
     */
    public function store(array $data = [])
    {
        if (!empty($data['image']) && request()->hasFile('image')) {
            try {
                $this->upload($data, 'image');
            } catch (\Exception $e) {
                throw $e;
            }
        }

        return parent::store($data);
    }

    /**
     * @param $entity
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     * @throws GeneralException
     * @throws \Throwable
     */
    public function update($entity, array $data = [])
    {
        $data = array_filter($data);
        $highlight = $this->getById($entity);

        if (!empty($data['image']) && request()->hasFile('image')) {
            try {
                $this->storageManagerService->deletePublicFile($highlight->image, 'highlight/images');

                $this->upload($data, 'image', 'highlight/images', $highlight->image);
            } catch (\Exception $e) {
                throw $e;
            }
        }
        return parent::update($entity, $data);
    }

    /**
     * @param array $data
     * @param $fileColumn
     * @param string $directory
     * @return array
     * @throws \Exception
     */
    private function upload(array &$data, $fileColumn, string $directory = 'highlight/images', $old_file_name = null): array
    {
        try {
            $data[$fileColumn] = $this->storageManagerService->uploadPublicFile($data[$fileColumn], $directory, $old_file_name);
            return $data;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
