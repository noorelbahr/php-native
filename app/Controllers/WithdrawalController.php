<?php

class WithdrawalController extends Controller
{
    private $withdrawalModel;
    private $userModel;
    private $balanceHistoryModel;
    private $SBFlipLibrary;

    /**
     * WithdrawalController constructor.
     */
    public function __construct()
    {
        $this->withdrawalModel = new Withdrawal();
        $this->userModel = new User();
        $this->balanceHistoryModel = new BalanceHistory();
        $this->SBFlipLibrary = new SlightlyBigFlip();
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

            // Update data
            $this->updateData($withdrawal);

            // Get latest data
            $withdrawal = $this->withdrawalModel->find($id);

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

            // Hit to SBFlip
            $sbfResponse = $this->SBFlipLibrary->requestDisbursement($withdrawal);
            if ($sbfResponse) {
                $this->withdrawalModel->update($withdrawal->id, [
                    'reference_id' => $sbfResponse->id,
                    'fee' => $sbfResponse->fee
                ]);
            }

            echo $this->success($withdrawal);

        } catch (Exception $e) {
            echo $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update effected data
     * - - -
     * @param $withdrawal
     */
    private function updateData($withdrawal)
    {
        // Check status to SBFlip
        $sbfResponse = $this->SBFlipLibrary->checkStatus($withdrawal);
        if ($sbfResponse) {
            $status = strtolower($sbfResponse->status);
            switch ($status) {
                case 'success':
                    // Update withdrawal data
                    $this->withdrawalModel->update($withdrawal->id, [
                        'status'        => $status,
                        'time_served'   => $sbfResponse->time_served,
                        'receipt'       => $sbfResponse->receipt,
                        'amount'        => $sbfResponse->amount,
                        'fee'           => $sbfResponse->fee
                    ]);

                    // Create balance history
                    $this->balanceHistoryModel->create([
                        'user_id'       => $withdrawal->user_id,
                        'reference_id'  => $withdrawal->id,
                        'type'          => BalanceHistory::WITHDRAWAL,
                        'amount'        => ($withdrawal->amount * -1)
                    ]);
                    break;
                case 'failed':
                    // Update withdrawal data
                    $this->withdrawalModel->update($withdrawal->id, [
                        'status'        => $status,
                        'time_served'   => $sbfResponse->time_served
                    ]);

                    // Update user balance
                    $user = $this->userModel->find($withdrawal->user_id);
                    $this->userModel->update($user->id, [
                        'balance' => (int) $user->balance + (int) $withdrawal->amount
                    ]);
                    break;
                default: break;
            }
        }
    }

}
