<?php

namespace App\Services\Roles;

use App\Traits\Roles\RolesAndPermissionsHelpersTrait;
use Illuminate\Support\Arr;

class PermissionFormFields
{
    use RolesAndPermissionsHelpersTrait;

    /**
     * List of fields and default value for each field.
     *
     * @var array
     */
    protected $fieldList = [
        'id'          => '',
        'name'          => '',
    ];

    /**
     * Create a new job instance.
     *
     * @param int $id
     *
     * @return void
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fields = $this->fieldList;

        if ($this->id) {
            $fields = $this->fieldsFromModel($this->id, $fields);
            $permissionRolesIds = $this->getPermissionsWithRoles($this->id);
            $permissionUsers = $this->getPermissionUsers($this->id);
            $permissionItemdData = $this->getPermissionItemData($this->id);
        }

        foreach ($fields as $fieldName => $fieldValue) {
            $fields[$fieldName] = old($fieldName, $fieldValue);
        }

        // Get the additional data for the form fields
        $permissionFormFieldData = $this->permissionFormFieldData();

        return array_merge(
            $fields,
            [
                'permissionUsers' => $permissionUsers,
                'permissionItemdData' => $permissionItemdData,
                'permissionRolesIds' => $permissionRolesIds,
            ],
            $permissionFormFieldData
        );

        return $fields;
    }

    /**
     * Return the field values from the model.
     *
     * @param int   $id
     * @param array $fields
     *
     * @return array
     */
    protected function fieldsFromModel($id, array $fields)
    {
        // return dd($id);
        $permission = config('permission.models.permission')::findOrFail($id);

        $fieldNames = array_keys(Arr::except($fields, ['permissions']));

        $fields = [
            'id' => $id,
        ];
        foreach ($fieldNames as $field) {
            $fields[$field] = $permission->{$field};
        }

        return $fields;
    }

    /**
     * Get the additonal form fields data.
     *
     * @return array
     */
    protected function permissionFormFieldData()
    {
        return [
            'permissionModels' => $this->getPermissionModels(),
        ];
    }
}
