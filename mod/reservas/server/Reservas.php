<?php
class Reservas
{
    public function index()
    {
		$user = Apayus::lib("user")->current();
        	return array("username" => $user["name"]);
    }

    public function header()
    {
     return array(
            array("title" => "Nombre", "type" => "string", "field" => "nombretitular"),
            array("title" => "Teléfono", "type" => "string", "field" => "telefonotitular"),
            array("title" => "Email", "type" => "string", "field" => "emailtitular"),
            //array("title"=>"Cónyuge", "type"=>"string", "field"=>"nombreconyuge"),
            array("title"=>"Fecha de Reservación", "type"=>"date", "field"=>"fechareserva1"),
            //array("title" => "Fecha", "type" => "date", "field" => "fechareserva"),
            //array("title"=>"Hora", "type"=>"string", "field"=>"hora"),
            array("title" => "Vence", "type" => "datetime", "field" => "vencimiento"),
            array("title" => "Dirección", "type" => "string", "field" => "direccion"),
            array("title"=>"Mesa", "type"=>"string", "field"=>"mesa"),
            //array("title" => "Coordinador", "type" => "string", "field" => "nombrecoordinador"),
            //array("title"=>"Email Coordinador", "type"=>"string", "field"=>"emailcoordinador"),
            //array("title"=>"Comentario", "type"=>"string", "field"=>"comentario"),
            array("title" => "Fecha de Realización", "type" => "date", "field" => "date")
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
        $filteredDataQuantity = Apayus::lib("secretary")->query('SELECT count(*) from tbl_reservas ' . $condition . ';');
        $filteredDataQuantity = $filteredDataQuantity[0];

        $totalpage = ceil($filteredDataQuantity / $quantity);
        $currentpage = ($totalpage >= $requestedpage) ? $requestedpage : 1;
        //die('SELECT `nombretitular`, `telefonotitular`, `emailtitular`, `fechareserva`, `vencimiento`, `direccion`, `mesa`, `date` from tbl_reservas ' . $condition . ' LIMIT ' . ($currentpage - 1) * $quantity . ',' . $quantity . ';');
        $filteredData = Apayus::lib("secretary")->query('SELECT `nombretitular`, `telefonotitular`, `emailtitular`, `fechareserva`, `vencimiento`, `direccion`, `mesa`, `date` from tbl_reservas ' . $condition . ' LIMIT ' . ($currentpage - 1) * $quantity . ',' . $quantity . ';');

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
        $filteredData = Apayus::lib("secretary")->query('SELECT `id`, `nombretitular`, `telefonotitular`, `emailtitular`, `nombreconyuge`, `fechareserva1`, `hora`, `vencimiento`, `direccion`, `nombrecoordinador`, `date` from tbl_reservas ' . $condition .';');        		
		$xlsx = realpath(dirname(__FILE__)."/../tpl/template1.xlsx");
		Apayus::lib("export")->toEcxel($filteredData, $xlsx);
    }
}
