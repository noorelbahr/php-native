<?php

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        $users = $this->userModel->all();
        echo $this->success($users);
    }

    public function show($id)
    {
        try {
            $user = $this->userModel->find($id);
            if (!$user)
                throw new Exception('User not found.');

            echo $this->success($user);

        } catch (Exception $e) {
            echo $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(Request $request)
    {
        try {
            // Validate request, only support required for now
            $validator = new Validator($request->all(), [
                'fullname'  => 'required',
                'email'     => 'required',
                'gender'    => 'nullable',
                'password'  => 'required',
                'balance'   => 'required',
                'bank_code' => 'required',
                'account_number' => 'required'
            ]);

            if (!$validator->validate())
                throw new Exception($validator->getError(true), 422);

            die();

            $userData = [
                'fullname'  => $request->input('fullname'),
                'email'     => $request->input('email'),
                'gender'    => $request->input('gender'),
                'password'  => md5($request->input('password')),
                'balance'   => $request->input('balance'),
                'bank_code' => $request->input('bank_code'),
                'account_number' => $request->input('account_number')
            ];

            die(json_encode($userData));

            if (!$this->userModel->create($userData))
                throw new Exception('Failed to save user data.');

            $user = $this->userModel->findBy('email', $request->input('email'));

            echo $this->success($user);

        } catch (Exception $e) {
            echo $this->error($e->getMessage(), $e->getCode());
        }
    }

}
