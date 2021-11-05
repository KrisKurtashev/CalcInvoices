<?php

class CalcContr extends Calc
{
    private $fileArray;
    private $outputType;
    private $vatNumber;
    private $currencies;
    private $dataArray; // Contains  'csvArray', 'currTypes', 'listVatNum', 'listDocNum'

    public function __construct(array $fileArray, string $outputType, string $vatNumber, array $currencies)
    {
        $this->fileArray =  $fileArray;
        $this->outputType =  $outputType;
        $this->vatNumber =  $vatNumber;
        $this->currencies =  $currencies;
        $this->dataArray = $this->setData($this->fileArray);
    }

    public function getTotalList( $vatNum = ''):array
    {
        if ($this->emptyInput() == false)
        {
            //echo "Empty input!";
            header("location: ../index.php?error=emptyinput");
            exit();
        }

        if ($this->haveAllCurrencies() == false)
        {
            //echo "Missing Currency!";
            header("location: ../index.php?error=missingcurrency");
            exit();
        }

        $haveMissingRelation = $this->wrongDocRelations();
        if (!empty($haveMissingRelation))
        {
            //echo "Missing Currency!";
            header("location: ../index.php?error=" . $haveMissingRelation);
            exit();
        }
        $haveMissingRelation = null;

        return $this->getTotal($this->dataArray['csvArray'], $this->currencies, $this->outputType, $vatNum);
    }

    private function  wrongDocRelations():string
    {
        $result = '';

        foreach ($this->dataArray['listDocNum'] as $vatNum => $listRelations)
        {
            foreach ($listRelations as $docNum => $parentDoc)
            {
                if (!key_exists($parentDoc, $listRelations )  && !empty($parentDoc))
                {

                    $result .= 'NoParentDoc' . $parentDoc . 'inVatNum' . $vatNum;
                }
            }
        }

        return $result;
    }


    private function  haveAllCurrencies():bool
    {
        $result = true;

        if (count($this->currencies) !== count($this->dataArray['currTypes']))
        {
            $result = false;
            return $result;
        }

        foreach ($this->currencies as $key => $value)
        {
            if (!in_array($key, $this->dataArray['currTypes']))
            {
                $result = false;
            }
        }

        return $result;
    }

    private function emptyInput(): bool
    {
        $result = true;

        if (empty($this->outputType) || empty($this->fileArray) || empty($this->currencies))
        {
            $result = false;
        }

        return $result;
    }
}