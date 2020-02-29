<?php

class UserController extends Controller
{
    private $userModel;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Show all users
     */
    public function index()
    {
        $users = $this->userModel->all();
        echo $this->success($users);
    }

    /**
     * Get user detail
     * - - -
     * @param $id
     */
    public function show($id)
    {
        try {
            // Check user data
            $user = $this->userModel->find($id);
            if (!$user)
                throw new Exception('User not found.');

            echo $this->success($user);

        } catch (Exception $e) {
            echo $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Create new user
     * - - -
     * @param Request $request
     */
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

            // Set user data
            $userData = [
                'fullname'  => $request->input('fullname'),
                'email'     => $request->input('email'),
                'gender'    => $request->input('gender'),
                'password'  => md5($request->input('password')),
                'balance'   => $request->input('balance'),
                'bank_code' => $request->input('bank_code'),
                'account_number' => $request->input('account_number')
            ];

            // Create user data
            if (!$this->userModel->create($userData))
                throw new Exception('Failed to save user data.');

            // Get latest data
            $user = $this->userModel->findBy('email', $request->input('email'));

            echo $this->success($user);

        } catch (Exception $e) {
            echo $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update user data
     * - - -
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate request, only support required for now
            $validator = new Validator($request->all(), [
                'fullname'  => 'required',
                'email'     => 'required',
                'gender'    => 'nullable',
                'password'  => 'nullable',
                'balance'   => 'required',
                'bank_code' => 'required',
                'account_number' => 'required'
            ]);

            if (!$validator->validate())
                throw new Exception($validator->getError(true), 422);

            // Check user data
            $userExists = $this->userModel->find($id);
            if (!$userExists)
                throw new Exception('User not found.', 400);

            // Set user data
            $userData = [
                'fullname'  => $request->input('fullname'),
                'email'     => $request->input('email'),
                'gender'    => $request->input('gender'),
                'balance'   => $request->input('balance'),
                'bank_code' => $request->input('bank_code'),
                'account_number' => $request->input('account_number')
            ];

            // Set password if exists in the request
            if ($request->input('password'))
                $userData['password'] = md5($request->input('password'));

            // Update user data
            if (!$this->userModel->update($id, $userData))
                throw new Exception('Failed to update user data.');

            // Get latest data
            $user = $this->userModel->find($id);

            echo $this->success($user);

        } catch (Exception $e) {
            echo $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete user
     * - - -
     * @param $id
     */
    public function destroy($id)
    {
        try {
            // Check user data
            $userExists = $this->userModel->find($id);
            if (!$userExists)
                throw new Exception('User not found.', 400);

            // Delete user data
            if (!$this->userModel->destroy($id))
                throw new Exception('Failed to remove user data.');

            echo $this->success('User data has been removed successfully', 200, true);

        } catch (Exception $e) {
            echo $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get user's balance histories
     * - - -
     * @param $id
     */
    public function balanceHistory($id)
    {
        try {
            // Check user data
            $user = $this->userModel->find($id);
            if (!$user)
                throw new Exception('User not found.');

            // Get data
            $balanceHistoryModel = new BalanceHistory();
            $balanceHistories = $balanceHistoryModel->findWhere([
                'user_id' => $user->id
            ], true);

            echo $this->success($balanceHistories);

        } catch (Exception $e) {
            echo $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get user's withdrawal histories
     * - - -
     * @param $id
     */
    public function withdrawalHistory($id)
    {
        try {
            // Check user data
            $user = $this->userModel->find($id);
            if (!$user)
                throw new Exception('User not found.');

            // Get data
            $withdrawalModel = new Withdrawal();
            $withdrawals = $withdrawalModel->findWhere([
                'user_id' => $user->id
            ], true);

            echo $this->success($withdrawals);

        } catch (Exception $e) {
            echo $this->error($e->getMessage(), $e->getCode());
        }
    }

}
