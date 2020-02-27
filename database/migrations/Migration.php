<?php

class Migration extends Database
{
    /**
     * Migrate All
     */
    public function migrate()
    {
        $this->migrateUser();
        $this->migrateBalanceHistory();
        $this->migrateWithdrawal();
        $this->migrateThirdPartyLog();
    }

    /**
     * PRIVATE
     * Migrate user table
     */
    private function migrateUser()
    {
        $sql = '
            CREATE TABLE IF NOT EXISTS `users` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `fullname` varchar(70) NOT NULL,
                `email` varchar(70) NOT NULL,
                `gender` varchar(10) NULL,
                `password` varchar(70) NOT NULL,
                `balance` int(11) NOT NULL,
                `bank_code` varchar(10) NOT NULL,
                `account_number` varchar(30) NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB';
        $this->prepareExecute($sql);
    }

    /**
     * PRIVATE
     * Migrate balance history table
     */
    private function migrateBalanceHistory()
    {
        $sql = '
            CREATE TABLE IF NOT EXISTS `balance_histories` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `reference_id` int(11) NOT NULL,
                `type` varchar(50) NOT NULL,
                `amount` int(11) NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB';
        $this->prepareExecute($sql);
    }

    /**
     * PRIVATE
     * Migrate withdrawal table
     */
    private function migrateWithdrawal()
    {
        $sql = '
            CREATE TABLE IF NOT EXISTS `withdrawals` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `bank_code` varchar(10) NOT NULL,
                `account_number` varchar(30) NOT NULL,
                `amount` int(11) NOT NULL,
                `fee` int(11) NOT NULL DEFAULT 0,
                `remark` text NULL,
                `receipt` varchar(255) NULL,
                `time_served` varchar(30) NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB';
        $this->prepareExecute($sql);
    }

    /**
     * PRIVATE
     * Migrate third party log table
     */
    private function migrateThirdPartyLog()
    {
        $sql = '
            CREATE TABLE IF NOT EXISTS `third_party_logs` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `reference_id` int(11) NOT NULL,
                `type` varchar(10) NOT NULL,
                `payload` text NULL,
                `response` text NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB';
        $this->prepareExecute($sql);
    }
}
