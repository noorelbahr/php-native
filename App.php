<?php

class App extends Controller
{
    public function init()
    {
        try {
//            $migration = new Migration();
//            $migration->migrate();

            $seeder = new Seeder();
            $seeder->seed();

//            echo $this->success($result);

        } catch (Exception $e) {
            echo $this->error($e->getMessage(), $e->getCode());
        }
    }
}
