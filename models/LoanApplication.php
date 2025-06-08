<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "loan_application".
 *
 * @property int $id
 * @property int $user_id
 * @property int $amount
 * @property int $term
 * @property string|null $status
 */
class LoanApplication extends \yii\db\ActiveRecord
{

    const STATUS_APPROVED = 'approved';
    const STATUS_DECLINED = 'declined';

    public static function tableName(): string
    {
        return 'loan_application';
    }

    public function optimisticLock(): string
    {
        return 'optimistic_lock';
    }

    public function rules(): array
    {
        return [
            [['user_id', 'amount', 'term'], 'required'],
            [['user_id', 'amount', 'term', 'optimistic_lock'], 'integer'],
            [['user_id'], 'approvedLoanValidation'],
            [['status'], 'string'],
            [['status'], 'in', 'range' => array_keys(self::optsStatus())],
            [['status'], 'default', 'value' => null],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'amount' => 'Amount',
            'term' => 'Term',
            'status' => 'Status',
        ];
    }


    public static function optsStatus(): array
    {
        return [
            self::STATUS_APPROVED => 'approved',
            self::STATUS_DECLINED => 'declined',
        ];
    }

    public function displayStatus(): ?string
    {
        return $this->status ? self::optsStatus()[$this->status] : null;
    }

    public function approvedLoanValidation(string $attribute): void
    {
        $isApproved = (bool)(static::findOne([
            'user_id' => $this->user_id,
            'status' => self::STATUS_APPROVED,
        ]));

        if ($isApproved) {
            $this->addError($attribute, 'Loan application already approved.');
        }
    }
}
