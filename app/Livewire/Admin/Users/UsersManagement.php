<?php



namespace App\Livewire\Admin\Users;



use App\Models\User;

use Livewire\Component;

use App\Models\UserAddress;

use Livewire\Attributes\On;

use App\Exports\UsersExport;

use Livewire\WithPagination;

use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;



class UsersManagement extends Component

{



    use WithPagination;

    //Short by

    public $sortField = 'full_name';

    public $sortDirection = 'asc';

    protected $queryString = ['sortField', 'sortDirection'];



    //Search

    public $search_name_or_email = '';

    public $search_address = '';

    public $search_gender = '';

    public $search_subdistrict = '';

    public $search_district = '';

    public $search_city = '';

    public $search_province = '';



    // Pagination

    public $perPage = 25;



    //dispatch

    public $alert_title, $alert_message;



    public $first_name, $last_name, $address, $email, $phone;

    //public $state = [];



    public function sortBy($field)

    {

        if ($this->sortField === $field) {

            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';

        } else {

            $this->sortDirection = 'asc';

        }

        $this->sortField = $field;

    }



    /**

     * Alert when user successfully disable or enable

     */

    #[On('user-disable')]

    public function alert(User $model)

    {

        if ($model->disabled) {

            $this->alert_title = trans('user.alert.disable-successfully');

            $this->alert_message = trans('user.alert.user-disable', ['user' => $model->full_name]);

        } else {

            $this->alert_title = trans('user.alert.enable-successfully');

            $this->alert_message = trans('user.alert.user-enable', ['user' => $model->full_name]);

        }

        $this->dispatch('updated');

    }



    /**

     * Export all user

     */

    public function exportUser()

    {

        //  return Excel::download(new UsersExport, 'users.xlsx');

    }



    #[On('refresh-user-list')]

    public function render()

    {

        $users = User::join('user_addresses', 'user_addresses.user_id', '=', 'users.id')

            ->join('user_admins', 'user_admins.user_id', '=', 'users.id')

            ->select(

                "*",

                "user_addresses.id as address_id",

                DB::raw("CONCAT(users.first_name,' ',COALESCE(users.last_name, '')) as full_name"),

            )

            ->when($this->search_name_or_email, function ($builder) {
                $builder->where(function ($builder) {
                    $builder->whereRaw("CONCAT(users.first_name,' ',COALESCE(users.last_name,'')) LIKE ?",  "%" . $this->search_name_or_email . "%")
                        ->orWhere('email', 'like', '%' . $this->search_name_or_email . '%');
                });
            })

            ->when($this->search_address, function ($builder) {

                $builder->where(function ($builder) {

                    $builder->where('address', 'like', '%' . $this->search_address . '%')

                        ->orWhere('phone', 'like', '%' . $this->search_address . '%');

                });

            })

            ->when($this->search_subdistrict, function ($builder) {

                $builder->where(function ($builder) {

                    $builder->where('subdistrict', 'like', '%' . $this->search_subdistrict . '%');

                });

            })

            ->when($this->search_district, function ($builder) {

                $builder->where(function ($builder) {

                    $builder->where('district', 'like', '%' . $this->search_district . '%');

                });

            })

            ->when($this->search_city, function ($builder) {

                $builder->where(function ($builder) {

                    $builder->where('city', 'like', '%' . $this->search_city . '%');

                });

            })

            ->when($this->search_province, function ($builder) {

                $builder->where(function ($builder) {

                    $builder->where('province', 'like', '%' . $this->search_province . '%');

                });

            })

            ->when($this->search_gender, function ($builder) {

                $builder->where(function ($builder) {

                    $builder->where('gender', $this->search_gender);

                });

            })

            // ->get();

            ->orderBy($this->sortField, $this->sortDirection)

            ->paginate($this->perPage);

          // dd( $this->search_city);

        //  dd($users->first());

        /*  $users = User::select("id", "first_name", "last_name", DB::raw("CONCAT(users.first_name,' ',COALESCE(users.last_name, '')) as full_name"), "email", "username", "disabled")

            ->when($this->search_name_or_email, function ($builder) {

                $builder->where(function ($builder) {

                    $sql = "CONCAT(users.first_name,' ',COALESCE(users.last_name,''))  like ?";

                    $builder->whereRaw($sql,  "%" . $this->search_name_or_email . "%")

                        ->orWhere('email', 'like', '%' . $this->search_name_or_email . '%');

                });

            })

            ->with('user_admin')

            ->whereHas('user_admin', function ($builder) {

                $builder->when($this->search_gender, function ($builder) {

                    $builder->where(function ($builder) {

                        $builder->where('gender', $this->search_gender);

                    });

                });

            })

            ->with('user_address')

            ->whereHas('user_address', function ($builder) {

                $builder->when($this->search_address, function ($builder) {

                    $builder->where(function ($builder) {

                        $builder->where('address', 'like', '%' . $this->search_address . '%')

                            ->orWhere('phone', 'like', '%' . $this->search_address . '%');

                    });

                });

            })



            ->orderBy($this->sortField, $this->sortDirection)

            ->paginate($this->perPage);

*/



          //  $city_name =$cities->first()->city;

         //  dd($city_name );

        return view('livewire.admin.users.users-management', [

            'users' => $users

        ]);

    }

}

