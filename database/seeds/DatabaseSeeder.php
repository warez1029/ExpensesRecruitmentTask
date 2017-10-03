<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');
        $role = Role::create(['name' => 'admin']);

        $user = new App\User();
        $user->password = Hash::make('admin');
        $user->email = 'admin@expenses.com';
        $user->name = 'Admin';
        $user->save();
        $user->assignRole('admin');
        $this->createUserExpenses($user, 'Admin', 2, 4);

        $user = new App\User();
        $user->password = Hash::make('user');
        $user->email = 'user@expenses.com';
        $user->name = 'User';
        $user->save();
        $this->createUserExpenses($user, 'User', 3, 5);
    }

    private function createUserExpenses($user, $prefix, $ex_cnt, $pay_cnt){
        for($j=0; $j<$ex_cnt; $j++){
            $expense = new App\Expense();
            $expense->name = $prefix.' expense '.$j;
            $expense = $user->expenses()->save($expense);

            for($i=0; $i<$pay_cnt; $i++){
                $payment = new App\Payment();
                $payment->name = $prefix.' payment '.$i;
                $payment->value = rand(100, 50000)/100.0;
                $payment->status = 'Un-approved';
                $expense->payments()->save($payment);
            }

            $payment = $expense->payments()->first();
            $payment->status = 'Approved';
            $expense->payments()->save($payment);
        }
    }
}
