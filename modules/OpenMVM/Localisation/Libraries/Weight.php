<?php

namespace Modules\OpenMVM\Localisation\Libraries;

class Weight
{
	private $weights = array();

	public function __construct()
	{
		// Load Database
		$this->db = \Config\Database::connect();
		// Get weight classes
		$builder = $this->db->table('weight_class');
		$builder->select('*');
		$builder->join('weight_class_description', 'weight_class_description.weight_class_id = weight_class.weight_class_id');

		$query = $builder->get();

		foreach ($query->getResult() as $result) {
			$this->weights[$result->weight_class_id] = array(
				'weight_class_id' => $result->weight_class_id,
				'title' => $result->title,
				'unit' => $result->unit,
				'value' => $result->value,
			);
		}
	}

	public function convert($value, $from, $to) {
		if ($from == $to) {
			return $value;
		}

		if (isset($this->weights[$from])) {
			$from = $this->weights[$from]['value'];
		} else {
			$from = 1;
		}

		if (isset($this->weights[$to])) {
			$to = $this->weights[$to]['value'];
		} else {
			$to = 1;
		}

		return $value * ($to / $from);
	}

	public function format($value, $weight_class_id, $decimal_point = '.', $thousand_point = ',') {
		if (isset($this->weights[$weight_class_id])) {
			return number_format($value, 2, $decimal_point, $thousand_point) . $this->weights[$weight_class_id]['unit'];
		} else {
			return number_format($value, 2, $decimal_point, $thousand_point);
		}
	}

	public function getUnit($weight_class_id) {
		if (isset($this->weights[$weight_class_id])) {
			return $this->weights[$weight_class_id]['unit'];
		} else {
			return '';
		}
	}
}