<?php

class Seeder extends Database
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
        $sql = '
            INSERT INTO users (fullname, email, gender, password, balance, bank_code, account_number)
            VALUES (?, ?, ?, ?, ?, ?, ?)';

        $this->prepareExecute($sql, [
            'John Doe', 'john@gmail.com', 'male', md5('john123'), 500000000, 'bni', '123123123'
        ]);

        $this->prepareExecute($sql, [
            'Jane Doe', 'jane@gmail.com', 'female', md5('jane123'), 50000, 'bni', '321321321'
        ]);
    }
}
