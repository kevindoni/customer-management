<?php



namespace App\Traits;



use App\Models\Customers\CustomerPppPaket;

use Illuminate\Support\Str;



trait UsernamePpp

{

    /**

     * Write code on Method

     *

     * @return response()

     */

    public function generateUsername($string): string
    {
        $username = strtolower(str_replace(' ', '-', $string));
        $count = 1;

        while ($this->usernameExists($username)) {
            $username = strtolower(str_replace(' ', '-', $string)) . '-' . $count;
            $count++;
        }
        return $username;
    }



    public function generatePassword()

    {

        $password = strtolower(str(Str::random(6))->slug());

        return $password;
    }



    /**

     * Write code on Method

     *

     *

     */

    protected function usernameExists($username)

    {

        //  dd($username);

        // return CustomerPppPaket::where('username', $username)->withTrashed()->exists();

        return CustomerPppPaket::where('username', $username)->exists();
    }
}
