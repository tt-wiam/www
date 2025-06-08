<?php

namespace app\forms;

use app\models\LoanApplication;
use yii\base\Model;
use yii\db\Exception;

/**
 * Форма дял принятия новых заявок
 */
class RequestsForm extends Model
{
    public int $user_id;
    public int $amount;
    public int $term;

    public function rules(): array
    {
        return [
            [['user_id', 'amount', 'term'], 'required'],
            [['user_id', 'amount', 'term'], 'integer'],
        ];
    }

    /**
     * Принимает новые заявки
     */
    public function createLoanApplication(): int|false
    {
        /** Проверка корректности входных данных*/
        if ($this->validate()) {
            $loanApplication = new LoanApplication([
                'user_id' => $this->user_id,
                'amount' => $this->amount,
                'term' => $this->term,
            ]);

            try {
                /** Заявка не валидна если уже есть подтвержденная заявка*/
                if ($loanApplication->save()) {
                    return $loanApplication->id;
                }
                $this->addErrors($loanApplication->errors);
            } catch (Exception) {
                $this->addError('loan_application', 'Error creating loan application');
            }
        }
        return false;
    }

}