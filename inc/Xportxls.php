<?php
    class Xportxls{
        private $data = array();
        private $stringTable;
        public function __construct ($info,$export){
            if($export== true){
                header("Content-type: application/vnd.ms-excel.xls");
                header("Content-Disposition: attachment;filename=".date("d_m_Y").".xls");
            }           
            $this->data = $info;
        }
        public function genString($border){
            $size = count($this->data[0]);
			if($border == true){
				$this->stringTable  = "<table border=1>";	
			}
			else{
				$this->stringTable  = "<table border=0>";
			}
            $count=0;
            //print_r($this->data);
            foreach($this->data as $info){
                if($count==0){
                    $this->stringTable .= "<tr style='font-weight: bold; background-color: #eee;'>";
                }
                else{
                    $this->stringTable .= "<tr>";
                }

                foreach ($info as $v) {
                    $this->stringTable .='<td>';
                    $this->stringTable .= $v;
                    $this->stringTable .='</td>';
                }
                $this->stringTable .= "</tr>";
                $count++;
            }           
            $this->stringTable .= "</table>";
            echo $this->stringTable;
        }
       
    }
	/*way to use*/
	/*$array[0] = array("id"=>"id","name"=>"Name","nation"=>"Country","ocupation"=>"ocupation");
    $array[1] = array("id"=>"2","name"=>"Juan Perez","nation"=>"GT");
    $array[2] = array("id"=>"3","name"=>"Pedro Partos","nation"=>"GT","aa"=>"12314");
    $array[3] = array("id"=>"4","name"=>"Peter Parker","nation"=>"GT","aa"=>"12314");

    $obj= new Xportxls($array,false);
    $obj->genString(true); */
	
    
?>