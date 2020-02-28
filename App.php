<?php

class App extends Controller
{
    public function init()
    {
        try {

            Route::exec();

        } catch (Exception $e) {
            echo $this->error($e->getMessage(), $e->getCode());
        }
    }
}
