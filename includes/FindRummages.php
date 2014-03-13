<?php

class FindRummages extends MySQLi {
	
	private function createWhere($column,$values){
		foreach($values as $k=>$v)
			$values[$k] = sprintf("'%s'",$this->real_escape_string($v));
		
		$where = $column." IN (".implode(",",$values).")";
		
		return $where;
	}
	
	public function getRummages($params){
		$data = array();
		$where = "";
		if($this->ping()){
			if(!empty($params['where'])){
				$where = "AND ";
				foreach($params['where'] as $k=>$v)
					$where .= $this->createWhere($k,$v);
			}
			$query = sprintf("SELECT * FROM rummage WHERE PubCode = '%s' ".$where,$this->real_escape_string($params['PubCode']));
			$result = $this->query($query);
			while($row = $result->fetch_assoc()){
				$data[$row['ID']] = array(
						"street"=>$row['Street'],
						"city"=>$row['City'],
						"state"=>$row['State'],
						"zip"=>$row['Zip'],
						"lat"=>$row['Lat'],
						"lon"=>$row['Lon']
					);
			}
		}
		return $data;
	}
	
	public function testQuery($params){
		$where = "";
		if($this->ping()){
			if(!empty($params['where'])){
				foreach($params['where'] as $k=>$v)
					$where .= $this->createWhere($k,$v);
				
				$query = sprintf("SELECT * FROM rummage WHERE PubCode = '%s' AND ".$where,$this->real_escape_string($params['PubCode']));
			}
			else{
				$query = sprintf("SELECT * FROM rummage WHERE PubCode = '%s' ",$this->real_escape_string($params['PubCode']));
			}
		}
		return $query;
	}
	
}

?>