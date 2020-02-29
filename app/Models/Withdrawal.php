<?php

class Withdrawal extends Model
{

    protected $table = 'withdrawals';

    // Status
    const PENDING = 'pending';
    const SUCCESS = 'success';
    const FAILED = 'failed';

}
