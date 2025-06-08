<?php

namespace app\forms;

use app\models\LoanApplication;
use Throwable;
use yii\base\Model;
use yii\db\Exception;
use yii\db\StaleObjectException;

/**
 * Форма дял обработки заявок
 */
class ProcessorForm extends Model
{
    public int $delay;

    public function rules(): array
    {
        return [
            [['delay'], 'integer', 'min' => 0],
        ];
    }

    /**
     * Обрабатывает заявки
     *
     * @throws Exception
     * @throws Throwable
     */
    public function process(): bool
    {
        /** Проверка корректности входных данных*/
        if ($this->validate()) {
            /** @var LoanApplication[] $loanApplications */
            $query = LoanApplication::find()->where([
                'is', 'status', null
            ]);

            while ($loanApplication = $query->one()) {

                /** Заявка не валидна если уже есть подтвержденная заявка*/
                if (!$loanApplication->validate()) {
                    /** Пропускаем обработку и устанавливаем статут DECLINED*/
                    $loanApplication->status = LoanApplication::STATUS_DECLINED;
                } else {
                    /** Обрабатываем заявку и принимаем решение*/
                    sleep($this->delay);
                    $loanApplication->status = rand(1, 2) === 1 ? LoanApplication::STATUS_APPROVED : LoanApplication::STATUS_DECLINED;
                }
                try {
                    $loanApplication->save();
                } catch (StaleObjectException) {
                    /** Заявка обработана другим обработчиком*/
                    continue;
                }
            }
            return true;
        }
        return false;
    }
}