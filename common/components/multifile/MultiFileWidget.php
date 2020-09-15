<?php

namespace common\components\multifile;

use yii\base\Widget;

class MultiFileWidget extends Widget
{
	public $model;

	//public $attribute;

	public $single = false;

	public $showPreview = false;

	public $relation;

	public $records = [];

	public $grouptype;

	public $showAuthor = false;

	public $template=2;

	public $extensions = [];


	public function run()
	{
		$records = [];

		if (!empty($this->records))
		{
			foreach ($this->records as $key => $data)
				if (!empty($data))
					$records[] = $data->attributes;
		}
		else
		{
			foreach ($this->model->getMediaRecords($this->relation) as $key => $data)
			{
				if (!empty($data))
				{
					$records[$key] = $data->attributes;

					if ($data->isNewRecord)
						$records[$key]['file_path'] = $data->file_path;
					else
					{
						if (is_array($data))
						{
							echo $this->relation;
							die();
						}

						$records[$key]['file_path'] = $data->getFilePath();
						$records[$key]['download'] = $data->getUrl();
						$records[$key]['size'] = round($data->size/1024/1024,3).'MB';
						if ($data->isImage())
							$records[$key]['preview'] =  $data->showThumb(['w'=>300]);
						$records[$key]['id'] = $data->getPrimaryKey();
					}
				}
			}
		}

		return $this->render('file_upload',[
			'model'=>$this->model,
			'records'=>$records,
			'POST_relation_name'=>\yii\helpers\StringHelper::basename(get_class($this->model)).'_'.$this->relation,
			'single'=>(int)$this->single,
			'group'=>$this->grouptype,
			'showAuthor'=>(int)$this->showAuthor,
			'extensions'=>$this->extensions,
			'showPreview'=>(int)$this->showPreview,
			'template'=>$this->template
		]);
	}
}