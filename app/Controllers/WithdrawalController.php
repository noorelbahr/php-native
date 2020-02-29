<?php

class WithdrawalController extends Controller
{
    private $withdrawalModel;
    private $userModel;
    private $balanceHistoryModel;

    /**
     * WithdrawalController constructor.
     */
    public function __construct()
    {
        $this->withdrawalModel = new Withdrawal();
        $this->userModel = new User();
        $this->balanceHistoryModel = new BalanceHistory();
    }

    /**
     * Get withdrawal detail
     * - - -
     * @param $id
     */
    public function show($id)
    {
        try {
            // Check withdrawal data
            $withdrawal = $this->withdrawalModel->find($id);
            if (!$withdrawal)
                throw new Exception('Data not found.');

            echo $this->success($withdrawal);

        } catch (Exception $e) {
            echo $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Create withdrawal request
     * - - -
     * @param Request $request
     */
    public function withdraw(Request $request)
    {
        try {
            // Validate request, only support required for now
            $validator = new Validator($request->all(), [
                'user_id'           => 'required',
                'amount'            => 'required',
                'remark'            => 'required'
            ]);

            if (!$validator->validate())
                throw new Exception($validator->getError(true), 422);

            // Check user data
            $user = $this->userModel->find($request->input('user_id'));
            if (!$user)
                throw new Exception('User not found.', 422);

            // Check user balance
            $currentBalance     = (int) $user->balance;
            $requestedAmount    = (int) $request->input('amount');
            if ($currentBalance < $requestedAmount)
                throw new Exception('Insufficient balance.', 400);

            // Set withdrawal data
            $withdrawalData = [
                'user_id'           => $request->input('user_id'),
                'bank_code'         => $user->bank_code,
                'account_number'    => $user->account_number,
                'amount'            => $requestedAmount,
                'remark'            => $request->input('remark'),
                'time_served'       => time(),
                'status'            => Withdrawal::PENDING
            ];

            // Create withdrawal data
            if (!$this->withdrawalModel->create($withdrawalData))
                throw new Exception('Failed to save data.');

            // Get latest data
            $withdrawal = $this->withdrawalModel->findWhere($withdrawalData);

            // Update user balance
            $this->userModel->update($user->id, [
                'balance' => ($currentBalance - $requestedAmount)
            ]);

            // Create balance history
            $this->balanceHistoryModel->create([
                'user_id'       => $user->id,
                'reference_id'  => $withdrawal->id,
                'type'          => BalanceHistory::WITHDRAWAL,
                'amount'        => ($requestedAmount * -1)
            ]);

            // Hit to SBFlip
            $SBFlip = new SlightlyBigFlip();
            $SBFlip->requestDisbursement($requestedAmount, $withdrawal->bank_code, $withdrawal->account_number, $withdrawal->remark);

            echo $this->success($withdrawal);

        } catch (Exception $e) {
            echo $this->error($e->getMessage(), $e->getCode());
        }
    }

}
