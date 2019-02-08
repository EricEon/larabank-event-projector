<?php

use App\Account;
use App\Events\AccountDeleted;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AccountsTableSeeder extends Seeder
{
    public function run()
    {
        User::all()->each(function (User $user) {
            $realNow = now();

            Carbon::setTestNow(now()->subDays(3));

            while ($realNow->isFuture()) {
                $this->createAccount($user);

                $this->createTransactions();

                $this->addRandomTime();
            }
        });
    }

    protected function createAccount(User $user)
    {
        if (faker()->boolean(20)) {
            return;
        }

        $accountName = faker()->randomElement(['Savings', 'Expenses', 'General', 'Company']);

        if (Account::where('name', $accountName)->where('user_id', $user->id)->first()) {
            return;
        }

        Account::createWithAttributes([
            'name' => $accountName,
            'user_id' => $user->id,
        ]);

        return;
    }

    protected function createTransactions()
    {
        Account::get()->each(function (Account $account) {
            if (faker()->boolean(50)) {
                $account->addMoney(faker()->numberBetween(1, 1000));
            }

            if (faker()->boolean(50)) {
                $account->subtractMoney(faker()->numberBetween(1, 900));
            }

            if (faker()->boolean(1)) {
                //event(new AccountDeleted($account->uuid));
            }
        });
    }

    protected function addRandomTime()
    {
        $now = now();

        $newNow = $now->addMinutes(faker()->numberBetween(45, 60 * 30));

        Carbon::setTestNow($newNow);
    }
}