<?php
class Registro
{
    public function index()
    {
		$user = Apayus::lib("user")->current();
        	return array("username" => $user["name"]);
    }

    public function header()
    {

     return array(
			array("title" => "Nombre", "type" => "string", "field" => "nom"),
			array("title" => "Apellido", "type" => "string", "field" => "ape"),
			array("title"=>"Edad", "type"=>"string", "field"=>"edad"),
			array("title"=>"Ocupacion", "type"=>"date", "field"=>"ocupacion"),
			array("title"=>"Email", "type"=>"string", "field"=>"email"),
			array("title" => "Ingresos", "type" => "date", "field" => "ingresos"),
			array("title"=>"Fecha", "type"=>"date", "field"=>"date"),
			array("title"=>"Tform", "type"=>"string", "field"=>"tform")
        );
    }

    public function reload($requestIn)
    {	
        return $this->generateFilteredData($requestIn);
    }

    private function generateCondition($array)
    {
        $cont = 0;
        $condition = '';
        if (!empty($array)) {
            $condition = ' WHERE ';
            while ($cont != count($array)) {
                $condition .= $array[$cont]['field'] . ' ' . $array[$cont]['operator'] . "'" . $array[$cont]['value'] . "'";
                $cont++;
                if ($array[$cont] != null) {
                    $condition .= ' AND ';
                }
            }
        }
       return $condition;
    }

    public function generateFilteredData($request)
    {
        Apayus::lib("secretary")->setting($this->config["db"]);
        //Datos de entrada
        $arrayIn = isset($request["filters"]) ? $request["filters"] : array();
        $requestedpage = $request["page"];
        $quantity = $request["count"];
        //Fin datos de entrada
        $condition = $this->generateCondition($arrayIn);
        $arrayOut = array();
        //Datos de salida
        $filteredDataQuantity = Apayus::lib("secretary")->query('SELECT count(*) from registros ' . $condition . ';');
        $filteredDataQuantity = $filteredDataQuantity[0];

        $totalpage = ceil($filteredDataQuantity / $quantity);
        $currentpage = ($totalpage >= $requestedpage) ? $requestedpage : 1;
        $filteredData = Apayus::lib("secretary")->query('SELECT `nom`, `ape`, `edad`, `ocupacion`, `email`,`ingresos`, date(`date`), `tform` from registros ' . $condition . ' LIMIT ' . ($currentpage - 1) * $quantity . ',' . $quantity . ';');

        foreach ($filteredData as $data) {
            array_push($arrayOut, $data);
        }
        return array('data' => $arrayOut, 'page' => $currentpage, 'total' => $totalpage);
    }

	public function export2xls($request)
    {
		if(is_string($request["filters"])) $request["filters"] = json_decode(stripslashes($request["filters"]), true);
	
        Apayus::lib("secretary")->setting($this->config["db"]);
        $arrayIn = isset($request["filters"]) ? $request["filters"] : array();		
        $condition = $this->generateCondition($arrayIn);		
        $filteredData = Apayus::lib("secretary")->query('SELECT `nom`, `ape`, `ecivil`,`edad`, `ocupacion`, `email`,`ingresos` from registros ' . $condition .';');        		
		$xlsx = realpath(dirname(__FILE__)."/../tpl/template1.xlsx");
		Apayus::lib("export")->toEcxel($filteredData, $xlsx);
    }
}
