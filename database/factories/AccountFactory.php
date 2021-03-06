<?php

use App\Account;
use App\User;
use Faker\Generator as Faker;

$factory->define(Account::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'balance' => $faker->numberBetween(-1000, 1000),
        'user_id' => factory(User::class),
    ];
});
