<?php

class Calc {


    protected function setData(array $file):array
    {
        $tmpName = $file['fileCsv']['tmp_name'];
        $arrayData = [];

        if(($handle = fopen($tmpName, 'r')) !== FALSE) {

            $rows = array_map('str_getcsv', file($tmpName));
            $header = array_shift($rows);
            $csv = array();
            $currTypes = [];
            $listVatNum = [];
            $listDocNum = [];
            foreach ($rows as $row) {
                //change cvs file to assoc array
                if (key_exists($row[1], $csv)) {
                    array_push($csv[$row[1]], array_combine($header, $row));
                }
                else
                {
                    $csv[$row[1]]= [array_combine($header, $row)];
                }

                //get all currency to check if we have all rates inputs
                if (!in_array($row[5], $currTypes))
                {
                    array_push($currTypes, $row[5]);
                }


                //get all vatNums for check
                if (!in_array($row[1], $listVatNum))
                {
                    array_push($listVatNum, $row[1]);
                }


                //get all doc number relations to check if we have misplaced relations
                if (!key_exists($row[1], $listDocNum))
                {
                    $listDocNum += [
                        $row[1] => [
                            $row[2] => $row[4]],
                    ];
                }
                else
                {
                    $listDocNum[$row[1]] += [
                        $row[2] => $row[4],
                    ];
                }
          }

          fclose($handle);

          $arrayData = [
              'csvArray'  =>  $csv,
              'currTypes' => $currTypes,
              'listVatNum'    =>  $listVatNum,
              'listDocNum'    =>  $listDocNum,
          ];
        }

        return $arrayData;
    }

    protected function getTotal($data, $currencies, $outputType, $vatNum):array
    {
        $customerList = [];

        if (!empty($vatNum))
        {
            $invoices = $data[$vatNum];
            $customerList = $this->getCustomerTotal($invoices, $currencies, $outputType, $customerList);
        }
        else
        {
            foreach ($data as $vatNum => $invoices)
            {

               $customerList = $this->getCustomerTotal($invoices, $currencies, $outputType, $customerList);
            }
        }

        return $customerList;
    }

    private function getCustomerTotal($invoices, $currencies, $outputType, $customerList)
    {
        $listDoc = [];
        $customerName = $invoices[0]['Customer'];
        for ($i= 0; $i < count($invoices); $i++)
        {
            $inv = $invoices[$i];

            switch ($inv['Type'])
            {
                case '1':
                    $listDoc += [
                        $inv['Document number'] => $inv['Total'] * $currencies[$inv['Currency']]
                    ];
                    break;
                case '2':
                    $listDoc[$inv['Parent document']] -= $inv['Total'] * $currencies[$inv['Currency']];
                    break;
                case '3':
                    $listDoc[$inv['Parent document']] += $inv['Total'] * $currencies[$inv['Currency']];
                    break;
            }
        }

        foreach ($listDoc as $docNum => $total)
        {
            $total = $total * $currencies[$outputType];
            array_push($customerList, $customerName . ' [' . $docNum . '] - ' . $total . ' ' . $outputType);
        }

        return $customerList;
    }

}