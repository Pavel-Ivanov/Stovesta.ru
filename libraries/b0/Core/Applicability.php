<?php
defined('_JEXEC') or die;
JImport('b0.Core.ApplicabilityKeys');

trait Applicability
{
	public $models;
	public $years;
	public $motors;
	public $drives;

	private function setModels($fields)
	{
		$this->models = $fields[ApplicabilityKeys::MODEL_KEY]->result ?? null;
	}

	private function setYears($fields)
	{
		$this->years = $fields[ApplicabilityKeys::YEAR_KEY]->result ?? null;
	}

	private function setMotors($fields)
	{
		$this->motors = $fields[ApplicabilityKeys::MOTOR_KEY]->result ?? null;
	}

	private function setDrives($fields)
	{
		$this->drives = $fields[ApplicabilityKeys::DRIVE_KEY]->result ?? null;
	}

	private function setApplicability($fields)
	{
		$this->setModels($fields);
		$this->setYears($fields);
		$this->setMotors($fields);
		$this->setDrives($fields);
	}

}