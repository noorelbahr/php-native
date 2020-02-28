<?php

class WelcomeController extends Controller
{
    public function index()
    {
        echo $this->success('Withdrawal API v1', 200, true);
    }

}
