<?php

namespace App\Domain\Patients\Actions;

use App\Domain\Patients\DTO\StorePatientDTO;
use App\Domain\Patients\Models\Patient;
use Exception;
use Illuminate\Support\Facades\DB;

class StorePatientAction
{
    /**
     * @param StorePatientDTO $dto
     * @return Patient
     * @throws Exception
     */
    public function execute(StorePatientDTO $dto): Patient
    {
        DB::beginTransaction();
        try {
            $patient = new Patient();
            $patient->user_id = 1;
            $patient->agent_id = $dto->getAgentId();
            $patient->full_name = $dto->getFullName();
            $patient->workplace = $dto->getWorkplace();
            $patient->birthday = $dto->getBirthday();
            $patient->province_city = $dto->getProvinceCity();
            $patient->address = $dto->getAddress();
            $patient->job = $dto->getJob();
            $patient->phone = $dto->getPhone();
            $patient->description = $dto->getDescription();

            if ($dto->getAvatar()) {
                $file = $dto->getAvatar();
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('images/patients/', $filename);
                $patient->avatar = $filename;
            }

            $patient->save();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $patient;
    }
}
