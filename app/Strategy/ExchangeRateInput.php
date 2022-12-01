<?php 

namespace App\Strategy;

use App\Contracts\InputInterfaceContract;

/**
   * Class ExchangeRateInput
   *
   * @package App\Strategy
   * @property array $outputData
   * @property string $format
   */

class ExchangeRateInput implements InputInterfaceContract
{
    /**
     * this is output data
     * @var array
     */
    private $outputData;

    /**
     * the format data set into this property
     * @var string
     */
    private $format;

    /**
     * constructor method
     * @param json|xml $data
     * @param array $contentType
     */
    public function __construct($data , $contentType)
    {
        $this->setFormat($contentType);
        $this->setInput($data);
    }

    /**
     * set format in $format property
     * @param array $contentType
     */
    public function setFormat($contentType)
    {
        $applicationType = explode(';' , $contentType[0]);
        $this->format = $applicationType[0];
    }

    /**
     * switch between xml|json format for output data
     * @param json|xml $data
     */
    public function setInput($data)
    {
        switch($this->format)
        {
            case 'application/xml' : 
                $this->xmlLoad($data);
                break;
            case 'application/json' : 
                $this->jsonLoad($data);
                break;
        }
    }

    /**
     * get output data
     */
    public function get()
    {
        return $this->outputData;
    }

    /**
     * fill output property
     * @param json $data
     */
    public function jsonLoad($data)
    {
        $this->outputData =  [];
        foreach(json_decode($data) as $value)
        {
            $this->outputData[] = [
                'data' => $value->data , 
                'cena' => $value->cena , 
            ];
        }
    }

    /**
     * fill output property
     * @param xml $data
     */
    public function xmlLoad($data)
    {
        $this->outputData =  [];
        foreach(simplexml_load_string($data) as $value)
        {
            $this->outputData[] = [
                'data' => $value->Data , 
                'cena' => $value->Cena , 
            ];
        }
    }
}
