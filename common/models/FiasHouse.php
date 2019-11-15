<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "fias_house".
 *
 * @property string $buildnum
 * @property string $enddate
 * @property string $housenum
 * @property string $ifnsfl
 * @property string $ifnsul
 * @property string $okato
 * @property string $oktmo
 * @property string $postalcode
 * @property string $startdate
 * @property string $strucnum
 * @property string $terrifnsfl
 * @property string $terrifnsul
 * @property string $updatedate
 * @property string $cadnum
 * @property int $eststatus
 * @property int $statstatus
 * @property int $strstatus
 * @property int $counter
 * @property int $divtype
 * @property string $aoguid
 * @property string $houseguid
 * @property string $houseid
 * @property string $normdoc
 * @property string $breadcrumbsLabel
 * @property string $pageTitle
 * @property string $house
 * @property string $build
 * @property string $struc
 * @property string $houseName
 * @property string $fullName
 *
 * @property FiasAddrObj $addrObj
 */
class FiasHouse extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fias_house';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['enddate', 'startdate', 'updatedate'], 'safe'],
            [['eststatus', 'statstatus', 'strstatus', 'counter', 'divtype'], 'default', 'value' => null],
            [['eststatus', 'statstatus', 'strstatus', 'counter', 'divtype'], 'integer'],
            [['aoguid', 'houseguid', 'houseid', 'normdoc'], 'string'],
            [['houseguid'], 'required'],
            [['buildnum', 'strucnum'], 'string', 'max' => 10],
            [['housenum'], 'string', 'max' => 20],
            [['ifnsfl', 'ifnsul', 'terrifnsfl', 'terrifnsul'], 'string', 'max' => 4],
            [['okato', 'oktmo'], 'string', 'max' => 11],
            [['postalcode'], 'string', 'max' => 6],
            [['cadnum'], 'string', 'max' => 100],
            [['houseguid'], 'unique'],
            [['aoguid'], 'exist', 'skipOnError' => true, 'targetClass' => FiasAddrObj::class, 'targetAttribute' => ['aoguid' => 'aoguid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'buildnum' => 'Buildnum',
            'enddate' => 'Enddate',
            'housenum' => 'Housenum',
            'ifnsfl' => 'Ifnsfl',
            'ifnsul' => 'Ifnsul',
            'okato' => 'Okato',
            'oktmo' => 'Oktmo',
            'postalcode' => 'Postalcode',
            'startdate' => 'Startdate',
            'strucnum' => 'Strucnum',
            'terrifnsfl' => 'Terrifnsfl',
            'terrifnsul' => 'Terrifnsul',
            'updatedate' => 'Updatedate',
            'cadnum' => 'Cadnum',
            'eststatus' => 'Eststatus',
            'statstatus' => 'Statstatus',
            'strstatus' => 'Strstatus',
            'counter' => 'Counter',
            'divtype' => 'Divtype',
            'aoguid' => 'Aoguid',
            'houseguid' => 'Houseguid',
            'houseid' => 'Houseid',
            'normdoc' => 'Normdoc',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddrObj()
    {
        return $this->hasOne(FiasAddrObj::class, ['aoguid' => 'aoguid']);
    }

    /**
     * @return string
     */
    public function getBreadcrumbsLabel()
    {
        return 'Дома';
    }

    /**
     * @return string
     */
    public function getPageTitle()
    {
        return $this->houseName;
    }

    /**
     * @return string
     */
    public function getHouse()
    {
        if (empty($this->housenum)) {
            return null;
        }

        switch ($this->eststatus) {
            case 1:
                $prefix = 'влд. ';
                break;
            case 2:
                $prefix = 'д. ';
                break;
            case 5:
                $prefix = 'зд. ';
                break;
            default:
                $prefix = '';
                break;
        }

        return $this->housenum ? $prefix . $this->housenum : null;
    }

    /**
     * @return string
     */
    public function getBuild()
    {
        if (empty($this->buildnum)) {
            return null;
        }

        $prefix = $this->buildnum ? 'корп. ' : '';

        return $this->buildnum ? $prefix . $this->buildnum : null;
    }

    /**
     * @return string
     */
    public function getStruc()
    {
        if (empty($this->strucnum)) {
            return null;
        }

        $prefix = $this->buildnum ? 'соор. ' : 'стр. ';

        return $this->strucnum ? ($prefix . $this->strucnum) : null;
    }

    /**
     * @return string
     */
    public function getHouseName()
    {
        return $this->house . ($this->build ? ' ' . $this->build : '') . ($this->struc ? ' ' . $this->struc : '');
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        $district = District::getDistrictNameByOKATO($this->okato);

        return ($this->addrObj ? $this->addrObj->getFullName($district) . ', ' : '') . $this->pageTitle;
    }
}
