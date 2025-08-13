<?php

namespace App\Livewire;

use PDO;
use App\Models\User;
use Livewire\Component;
use App\Models\Websystem;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Livewire\Attributes\Layout;
use App\Models\Admins\UserAdmin;
use Illuminate\Validation\Rules;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;


class Install extends Component
{

    public $currentStep = 1;
    public $installationStep = 1;
    public $existingDatabase = false;
    public $name, $description, $status = 1;
    public $successMessage = '';

    private $userEmail, $userPassword;

    public $input = [];
    //step 1
    public function firstStepSubmit()
    {

        $this->input['database_host'] = env('DB_HOST');
        $this->input['database_username'] = env('DB_USERNAME');
        $this->input['database_password'] = env('DB_PASSWORD');
        $this->input['database_name'] = env('DB_DATABASE');
        $this->existingDatabase = false;
        $this->currentStep = 2;
    }

    //step 2
    public function secondStepSubmit()
    {
        Validator::make($this->input, [
            'database_host' => ['required', 'string', 'max:255'],
            'database_username' => ['required', 'string', 'max:255'],
            'database_password' => ['nullable', 'string', 'max:255'],
            'database_name' => ['required', 'string', 'max:255'],
        ])->validate();

        $error_message = null;
        try {
            $db = new \mysqli(
                $this->input['database_host'],
                $this->input['database_username'],
                $this->input['database_password'] ?? null,
            );
            $error_message = $db->connect_errno
                ? 'Connection Failed .' . $db->connect_error
                : $error_message;
        } catch (\Throwable $th) {
            $error_message = 'Connection failed';
        }

        if (is_null($error_message)) {
            setEnv('DB_HOST', $this->input['database_host']);
            setEnv('DB_DATABASE', $this->input['database_name']);
            setEnv('DB_USERNAME', $this->input['database_username']);
            setEnv('DB_PASSWORD',  $this->input['database_password'] ?? '');
            Artisan::call('optimize:clear');
            try {
                $db = new \mysqli(
                    $this->input['database_host'],
                    $this->input['database_username'],
                    $this->input['database_password'] ?? null,
                    $this->input['database_name'],
                );

                $this->existingDatabase = true;
                $this->currentStep = 2;
            } catch (\Exception $e) {
                $this->existingDatabase = false;
                $this->currentStep = 3;
                $this->createNewDatabaseForm();
                // $this->notification('Error', $e->getMessage(), 'error');
            }
        } else {
            $this->notification('Error', $error_message, 'error');
        }
    }

    public function updatingInputDatabaseName()
    {
        $this->existingDatabase = false;
    }

    //step 3
    public function replaceExistingDatabase()
    {
        $this->replaceExistingDatabaseForm();
        $this->currentStep = 3;
    }

    //step 4
    public function installApp()
    {
        Artisan::call('db:seed');
        $this->currentStep = 4;
    }

    //step 5
    public function createAdmin(UserService $userService)
    {
        Validator::make($this->input, [
            'name' => ['required', 'string', 'min:2', 'max:15'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' =>  ['required', 'string', 'confirmed', Rules\Password::defaults()]
        ])->validate();

        try {
            $this->userEmail = $this->input['email'];
            $this->userPassword = $this->input['password'];

            $user=  User::create([
                'first_name' => $this->input['name'],
                'email' => $this->input['email'],
                'password' => Hash::make($this->input['password']),
                'disabled' => false,
            ]);
            $userAddress = new UserAddress();
            $user->user_address()->save($userAddress);
             $userAdmin = new UserAdmin();
            $user->user_admin()->save($userAdmin);
            $user->syncRoles('admin');
            $this->notification('Success', trans('user.alert.user-created-successfully', ['user' => $user->full_name]), 'success');
            $this->currentStep = 5;
        } catch (\Exception $e) {
            $this->notification('Failed', 'Creating admin user failed with error: ' . $e->getMessage(), 'error');
        }
    }

    //step 6
    public function updateCompany()
    {
        Validator::make($this->input, [
            'title' => 'required',
            'app_url' => 'required',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'address' => 'required',
            'city' => 'required',
            'postal_code' => ['required', 'numeric'],
            'phone' => ['required', 'numeric', 'min_digits:8', 'max_digits:15'],
            'tax_rate' => ['nullable', 'numeric'],
        ],[
            'title.required' => 'Please enter your company!'
        ])->validate();
        
        try {
            $websystem = Websystem::first();
            $websystem->forceFill([
                'title' => $this->input['title'] ?? 'Customer Management',
                'email' => $this->input['email']?? null,
                'address' => $this->input['address']?? null,
                'city' => $this->input['city']?? null,
                'postal_code' => $this->input['postal_code']?? null,
                'phone' => $this->input['phone']?? null,
                'tax_rate' => $this->input['tax_rate'] ?? 0,
                // 'app_url' => $this->state['app_url'],
            ])->save();
            setEnv('APP_URL', $this->input['app_url'] ?? 'http://localhost');
            setEnv('APP_NAME', $this->input['title'] ? "'".$this->input['title']."'" : "'Customer Management 2'");
            $this->notification(trans('websystem.alert.updated'), trans('websystem.alert.updated-message-successfully'), 'success');
            $this->currentStep = 6;
             $this->input['email'] = $this->userEmail;
            $this->input['password'] = $this->userPassword;
            setEnv('APP_INSTALLED', 'true');
            
        } catch (\Exception $e) {
            $this->notification('Failed', 'Update company failed with error: ' . $e->getMessage(), 'success');
        }
    }

    //finish
    public function finish()
    {
		
        setEnv('CACHE_STORE', 'file');
        setEnv('SESSION_DRIVER', 'file');
        setEnv('QUEUE_CONNECTION', 'sync');
        
        Artisan::call('key:generate');
        Artisan::call('optimize:clear');
        return redirect()->route('login');
    }
    public function createNewDatabaseForm()
    {
        $this->notification('Waiting...', 'Please wait, we will create new database to your application..', 'success');
        try {
            $dbName = $this->input['database_name'];
            $pdo = new PDO(
                'mysql:' . 'host=' .  $this->input['database_host'],
                $this->input['database_username'],
                $this->input['database_password'] ?? ''
            );
            $pdo->query('CREATE DATABASE ' . $dbName);
            Artisan::call('migrate');
            $this->notification('Success', 'Creating database successfully', 'success');
        } catch (\Exception $e) {
            $this->notification('Failed', 'Creating database failed with error: ' . $e->getMessage(), 'success');
        }
    }

    public function replaceExistingDatabaseForm()
    {
        $this->notification('Waiting...', 'Please wait, we will delete and create new database to your application..', 'success');
        try {
            Artisan::call('migrate:fresh');
            $this->notification('Success', 'Creating database successfully', 'success');
        } catch (\Exception $e) {
            $this->notification('Failed', 'Creating database failed with error: ' . $e->getMessage(), 'success');
        }
    }



    public function back($step)
    {
        $this->currentStep = $step;
    }


    /* public function test_database_connection(Request $request)
    {
        $data = json_decode(json_encode($request->database));
        $error_message = null;
        try {
            $db = new \mysqli(
                $data->host,
                $data->username,
                $data->password,
                $data->database
            );
            $error_message = $db->connect_errno
                ? 'Connection Failed .' . $db->connect_error
                : $error_message;
        } catch (\Throwable $th) {
            $error_message = 'Connection failed';
        }
        return response()->json([
            'status' => $error_message ?? 'Success',
            'error' => $error_message === null ? false : true,
        ]);
    }
*/

    private function notification($title, $text, $status)
    {
        LivewireAlert::title($title)
            ->text($text)
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }

    #[Layout('components.layouts.install')]
    public function render(Request $request)
    {
        if (env('APP_INSTALLED') === true) {
            $this->redirectRoute('home');
        }
        $mysql_user_version = [
            'distrib' => '',
            'version' => null,
            'compatible' => false,
        ];

        if (function_exists('exec') || function_exists('shell_exec')) {
            $mysqldump_v = function_exists('exec')
                ? exec('mysqldump --version')
                : shell_exec('mysqldump --version');

            if ($mysqld = str_extract(
                $mysqldump_v,
                '/Distrib (?P<destrib>.+),/i'
            )) {
                $destrib = $mysqld['destrib'] ?? null;

                $mysqld = explode('-', mb_strtolower($destrib), 2);

                $mysql_user_version['distrib'] = $mysqld[1] ?? 'mysql';
                $mysql_user_version['version'] = $mysqld[0];

                if (
                    $mysql_user_version['distrib'] == 'mysql' &&
                    $mysql_user_version['version'] >= 5.6
                ) {
                    $mysql_user_version['compatible'] = true;
                } elseif (
                    $mysql_user_version['distrib'] == 'mariadb' &&
                    $mysql_user_version['version'] >= 10
                ) {
                    $mysql_user_version['compatible'] = true;
                }
            }
        }

        $requirements = [
            'php' => ['version' => "8.2", 'current' => phpversion()],
            'mysql' => ['version' => 5.6, 'current' => $mysql_user_version],
            'php_extensions' => [
                'curl' => false,
                'fileinfo' => false,
                'intl' => false,
                'json' => false,
                'mbstring' => false,
                'openssl' => false,
                'mysqli' => false,
                'zip' => false,
                'ctype' => false,
                'dom' => false,
            ],
        ];

        $php_loaded_extensions = get_loaded_extensions();


        foreach ($requirements['php_extensions'] as $name => &$enabled) {
            $enabled = in_array($name, $php_loaded_extensions);
        }


        return view('livewire.install', [
            'requirements' => $requirements,
        ]);
    }
}
