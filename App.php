<?php

class App extends Controller
{
    /**
     * Run app
     */
    public function run()
    {
        try {
            Route::exec();

        } catch (Exception $e) {
            echo $this->error($e->getMessage(), $e->getCode());
        }
    }
}
