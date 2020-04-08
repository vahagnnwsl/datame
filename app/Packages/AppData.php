<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-19
 * Time: 23:10
 */

namespace App\Packages;


use Carbon\Carbon;

class AppData
{
    /**
     * @var string Фамилия, имя и отчество проверяемого лица
     */
    private $firstname;

    private $surname;

    private $lastname;

    /**
     * @var Carbon Дату рождения проверяемого лица
     */
    private $birthday;
    /**
     * @var string Серия и номер паспорта проверяемого лица
     */
    private $passportCode;
    /**
     * @var Carbon Дату выдачи паспорта проверяемого лица
     */
    private $dateOfIssue;

    /**
     * @var string Код подразделения выдачи паспорта
     */
    private $codeDepartment;

    private $inn;

    /**
     * @return mixed
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * @param mixed $inn
     * @return AppData
     */
    public function setInn($inn)
    {
        $this->inn = $inn;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return AppData
     */
    public function setFirstname(string $firstname): AppData
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     * @return AppData
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     * @return AppData
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getBirthday(): Carbon
    {
        return $this->birthday;
    }

    /**
     * @param Carbon $birthday
     * @return AppData
     */
    public function setBirthday(Carbon $birthday): AppData
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassportCode(): string
    {
        return $this->passportCode;
    }

    /**
     * @param string $passportCode
     * @return AppData
     */
    public function setPassportCode(string $passportCode): AppData
    {
        $this->passportCode = $passportCode;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getDateOfIssue(): Carbon
    {
        return $this->dateOfIssue;
    }

    /**
     * @param Carbon $dateOfIssue
     * @return AppData
     */
    public function setDateOfIssue(Carbon $dateOfIssue): AppData
    {
        $this->dateOfIssue = $dateOfIssue;
        return $this;
    }

    /**
     * @return string
     */
    public function getCodeDepartment(): string
    {
        return $this->codeDepartment;
    }

    /**
     * @param string $codeDepartment
     * @return AppData
     */
    public function setCodeDepartment(string $codeDepartment): AppData
    {
        $this->codeDepartment = $codeDepartment;
        return $this;
    }

    public function getFormattedPassportCode() {
        return substr($this->getPassportCode(), 0, 2)." ".substr($this->getPassportCode(), 2, 2)." ".substr($this->getPassportCode(), 4);
    }


}