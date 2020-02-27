<?php

class Seeder
{
    /**
     * Seed all data
     */
    public function seed()
    {
        $this->seedUserData();
    }

    /**
     * PRIVATE
     * Seed user data
     */
    private function seedUserData()
    {
        $user = new User();
        $user->create([
            [
                'fullname'  => 'John Doe',
                'email'     => 'john@gmail.com',
                'gender'    => 'male',
                'password'  => md5('john123'),
                'balance'   => 500000000,
                'bank_code' => 'bni',
                'account_number' => '1234567890'
            ], [
                'fullname'  => 'Jane Doe',
                'email'     => 'jane@gmail.com',
                'gender'    => 'female',
                'password'  => md5('jane123'),
                'balance'   => 50000,
                'bank_code' => 'bni',
                'account_number' => '0987654321'
            ]
        ]);
    }
}
