<?php

namespace common\models;

use common\traits\MetaTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "fias_addrobj".
 *
 * @property string $areacode
 * @property string $autocode
 * @property string $citycode
 * @property string $code
 * @property string $enddate
 * @property string $formalname
 * @property string $ifnsfl
 * @property string $ifnsul
 * @property string $offname
 * @property string $okato
 * @property string $oktmo
 * @property string $placecode
 * @property string $plaincode
 * @property string $postalcode
 * @property string $regioncode
 * @property string $shortname
 * @property string $startdate
 * @property string $streetcode
 * @property string $terrifnsfl
 * @property string $terrifnsul
 * @property string $updatedate
 * @property string $ctarcode
 * @property string $extrcode
 * @property string $sextcode
 * @property string $plancode
 * @property string $cadnum
 * @property string $divtype
 * @property int $actstatus
 * @property string $aoguid
 * @property string $aoid
 * @property int $aolevel
 * @property int $centstatus
 * @property int $currstatus
 * @property int $livestatus
 * @property string $nextid
 * @property string $normdoc
 * @property int $operstatus
 * @property string $parentguid
 * @property string $previd
 * @property string $addressName
 * @property string $fullName
 *
 * @property FiasAddrObj $parent
 * @property FiasAddrObj[] $parents
 * @property FiasAddrObj[] $addresses
 * @property FiasHouse[] $houses
 */
class FiasAddrObj extends \yii\db\ActiveRecord
{
    use MetaTrait;

    const VERBOSE_NAME = 'Адрес';
    const VERBOSE_NAME_PLURAL = 'Адреса';
    const TITLE_ATTRIBUTE = 'addressName';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fias_addrobj';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['enddate', 'startdate', 'updatedate'], 'safe'],
            [['actstatus', 'aolevel', 'centstatus', 'currstatus', 'livestatus', 'operstatus', 'divtype'], 'default', 'value' => null],
            [['actstatus', 'aolevel', 'centstatus', 'currstatus', 'livestatus', 'operstatus', 'divtype'], 'integer'],
            [['aoguid'], 'required'],
            [['aoguid', 'aoid', 'nextid', 'normdoc', 'parentguid', 'previd'], 'string'],
            [['areacode', 'citycode', 'placecode', 'ctarcode', 'sextcode'], 'string', 'max' => 3],
            [['autocode'], 'string', 'max' => 1],
            [['code'], 'string', 'max' => 17],
            [['formalname', 'offname'], 'string', 'max' => 120],
            [['ifnsfl', 'ifnsul', 'streetcode', 'terrifnsfl', 'terrifnsul', 'extrcode', 'plancode'], 'string', 'max' => 4],
            [['okato', 'oktmo'], 'string', 'max' => 11],
            [['plaincode'], 'string', 'max' => 15],
            [['postalcode'], 'string', 'max' => 6],
            [['regioncode'], 'string', 'max' => 2],
            [['shortname'], 'string', 'max' => 10],
            [['cadnum'], 'string', 'max' => 100],
            [['aoguid'], 'unique'],
//            [['parentguid'], 'exist', 'skipOnError' => true, 'targetClass' => FiasAddrObj::class, 'targetAttribute' => ['parentguid' => 'aoguid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'areacode' => 'Areacode',
            'autocode' => 'Autocode',
            'citycode' => 'Citycode',
            'code' => 'Code',
            'enddate' => 'Enddate',
            'formalname' => 'Formalname',
            'ifnsfl' => 'Ifnsfl',
            'ifnsul' => 'Ifnsul',
            'offname' => 'Offname',
            'okato' => 'Okato',
            'oktmo' => 'Oktmo',
            'placecode' => 'Placecode',
            'plaincode' => 'Plaincode',
            'postalcode' => 'Postalcode',
            'regioncode' => 'Regioncode',
            'shortname' => 'Shortname',
            'startdate' => 'Startdate',
            'streetcode' => 'Streetcode',
            'terrifnsfl' => 'Terrifnsfl',
            'terrifnsul' => 'Terrifnsul',
            'updatedate' => 'Updatedate',
            'ctarcode' => 'Ctarcode',
            'extrcode' => 'Extrcode',
            'sextcode' => 'Sextcode',
            'plancode' => 'Plancode',
            'cadnum' => 'Cadnum',
            'divtype' => 'Divtype',
            'actstatus' => 'Actstatus',
            'aoguid' => 'Aoguid',
            'aoid' => 'Aoid',
            'aolevel' => 'Aolevel',
            'centstatus' => 'Centstatus',
            'currstatus' => 'Currstatus',
            'livestatus' => 'Livestatus',
            'nextid' => 'Nextid',
            'normdoc' => 'Normdoc',
            'operstatus' => 'Operstatus',
            'parentguid' => 'Parentguid',
            'previd' => 'Previd',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(FiasAddrObj::class, ['aoguid' => 'parentguid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(FiasAddrObj::class, ['parentguid' => 'aoguid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHouses()
    {
        return $this->hasMany(FiasHouse::class, ['aoguid' => 'aoguid']);
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function getParents()
    {
        $command = Yii::$app->db->createCommand("WITH RECURSIVE child_to_parents AS (
            SELECT fias_addrobj.* FROM fias_addrobj
                WHERE aoguid = '{$this->parentguid}'
            UNION ALL
            SELECT fias_addrobj.* FROM fias_addrobj, child_to_parents
                WHERE fias_addrobj.aoguid = child_to_parents.parentguid
                    AND fias_addrobj.currstatus = 0
            )
            SELECT * FROM child_to_parents ORDER BY aolevel;");

        $parents = [];
        foreach ($command->queryAll() as $key => $row) {
            $parents[$key] = new self($row);
        }

        return $parents;
    }

    /**
     * @return string
     */
    public function getAddressName()
    {
        if (in_array($this->aolevel, [1, 3])) {
            return $this->formalname . ' ' . $this->shortname;
        }

        return $this->shortname . ' ' . $this->formalname;
    }

    /**
     * @param string $district
     * @return string
     */
    public function getFullName($district)
    {
        $parents = $this->parents;

        $fullName = '';

        if ($parents) {
            foreach ($parents as $parent) {
                $fullName = ($fullName ? $fullName . ', ' : '') . $parent->addressName;

                if ($district && $parent->aolevel == 4) {
                    $fullName .= ', ' . $district;
                }
            }
        }

        return ($fullName ? $fullName . ', ' : '') . $this->addressName;
    }
}
