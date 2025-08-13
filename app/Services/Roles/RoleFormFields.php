<?php

namespace App\Services\Roles;

use App\Traits\Roles\RolesAndPermissionsHelpersTrait;
use Illuminate\Support\Arr;

class RoleFormFields
{
    use RolesAndPermissionsHelpersTrait;
    /**
     * List of fields and default value for each field.
     *
     * @var array
     */
    protected $fieldList = [
        'name'          => '',
        'permissions'   => [],
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
        // dd($this->id);
        $fields = $this->fieldList;
        $rolePermissionsIds = [];

        if ($this->id) {

            $fields = $this->fieldsFromModel($this->id, $fields);

            $rolePermissionsIds = $this->getRolePermissionsIds($this->id);
        }

        foreach ($fields as $fieldName => $fieldValue) {
            $fields[$fieldName] = old($fieldName, $fieldValue);
        }

        // Get the additional data for the form fields
        $roleFormFieldData = $this->roleFormFieldData();

        return array_merge(
            $fields,
            [
                'allPermissions'     => config('permission.models.permission')::get(),

                'rolePermissionsIds' => $rolePermissionsIds,
            ],
            $roleFormFieldData
        );
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

        $role = config('permission.models.role')::findOrFail($id);
        // return dd($role);
        $fieldNames = array_keys(Arr::except($fields, ['permissions']));

        $fields = [
            'id' => $id,
        ];
        foreach ($fieldNames as $field) {
            $fields[$field] = $role->{$field};
        }

        $fields['permissions'] = $role->permissions();

        return $fields;
    }

    /**
     * Get the additonal form fields data.
     *
     * @return array
     */
    protected function roleFormFieldData()
    {
        $allAvailablePermissions = config('permission.models.permission')::all();

        return [
            'allAvailablePermissions'   => $allAvailablePermissions,
        ];
    }
}
