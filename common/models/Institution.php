<?php

namespace common\models;

use common\modules\log\behaviors\LogBehavior;
use common\traits\MetaTrait;
use DateTime;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "db_institution".
 *
 * @property int $id_institution
 * @property int $id_record
 * @property int $status
 * @property string $description
 * @property string $comment
 * @property string $bus_id
 * @property bool $is_updating
 * @property int $last_update
 * @property string $fullname
 * @property string $shortname
 * @property string $type
 * @property string $founder
 * @property string $okved
 * @property string $okved_code
 * @property string $okved_name
 * @property string $ppo
 * @property string $ppo_oktmo_name
 * @property string $ppo_oktmo_code
 * @property string $ppo_okato_name
 * @property string $ppo_okato_code
 * @property string $okpo
 * @property string $okopf_name
 * @property string $okopf_code
 * @property string $okfs_name
 * @property string $okfs_code
 * @property string $oktmo_name
 * @property string $oktmo_code
 * @property string $okato_name
 * @property string $okato_code
 * @property string $address_zip
 * @property string $address_subject
 * @property string $address_region
 * @property string $address_locality
 * @property string $address_street
 * @property string $address_building
 * @property string $address_latitude
 * @property string $address_longitude
 * @property string $vgu_name
 * @property string $vgu_code
 * @property string $inn
 * @property string $kpp
 * @property string $ogrn
 * @property string $phone
 * @property string $email
 * @property string $website
 * @property string $manager_position
 * @property string $manager_firstname
 * @property string $manager_middlename
 * @property string $manager_lastname
 * @property string $version
 * @property int $modified_at
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 * @property string $statusName
 * @property string $typeName
 * @property string $address
 * @property string $manager
 *
 * @property Document[] $documents
 * @property CollectionRecord $record
 */
class Institution extends \yii\db\ActiveRecord
{
    use MetaTrait;

    const VERBOSE_NAME = 'Организация';
    const VERBOSE_NAME_PLURAL = 'Организации';
    const TITLE_ATTRIBUTE = 'shortname';

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_DELETED = 3;

    const TYPE_STANDALONE = '10';
    const TYPE_BUDGETARY = '03';
    const TYPE_BREECH = '08';
    const TYPE_PRIVATE = 'private';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_institution';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bus_id'], 'unique'],
            [['bus_id', 'description'], 'string'],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['is_updating'], 'default', 'value' => true],
            [['type'], 'default', 'value' => self::TYPE_PRIVATE],
            [['last_update', 'modified_at'], 'default', 'value' => null],
            [['id_institution', 'id_record', 'status', 'last_update', 'modified_at'], 'integer'],
            [['is_updating'], 'boolean'],
            [
                [
                    'comment', 'fullname', 'shortname', 'type', 'okved_code', 'okved_name', 'ppo', 'ppo_oktmo_name',
                    'ppo_oktmo_code', 'ppo_okato_name', 'ppo_okato_code', 'okpo', 'okopf_name', 'okopf_code',
                    'okfs_name', 'okfs_code', 'oktmo_name', 'oktmo_code', 'okato_name', 'okato_code', 'address_zip',
                    'address_subject', 'address_region', 'address_locality', 'address_street', 'address_building',
                    'address_latitude', 'address_longitude', 'vgu_name', 'vgu_code', 'inn', 'kpp', 'ogrn', 'phone',
                    'email', 'website', 'manager_position', 'manager_firstname', 'manager_middlename',
                    'manager_lastname', 'version',
                ],
                'string', 'max' => 255,
            ],
            [['founder', 'okved'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_institution' => '#',
            'id_record' => 'ID записи в колекции',
            'status' => 'Статус',
            'description' => 'Описание',
            'comment' => 'Комментарий',
            'bus_id' => 'bus.gov.ru Id',
            'is_updating' => 'Обновлять с bus.gov.ru',
            'last_update' => 'Последнее обновление',
            'fullname' => 'Название',
            'shortname' => 'Короткое название',
            'type' => 'Тип',
            'founder' => 'Учредители',
            'okved' => 'Иные виды деятельности по ОКВЭД',
            'okved_name' => 'Основные виды деятельности по ОКВЭД',
            'okved_code' => 'ОКВЕД код',
            'ppo' => 'ППО',
            'ppo_oktmo_name' => 'ППО ОКТМО название',
            'ppo_oktmo_code' => 'ППО ОКТМО код',
            'ppo_okato_name' => 'ППО ОКАТО',
            'ppo_okato_code' => 'ППО ОКАТО код',
            'okpo' => 'ОКПО',
            'okopf_name' => 'Тип учреждения (по ОКОПФ)',
            'okopf_code' => 'ОКОПФ код',
            'okfs_name' => 'Вид собственности (по ОКФС)',
            'okfs_code' => 'ОКФС код',
            'oktmo_name' => 'ОКТМО (город)',
            'oktmo_code' => 'ОКТМО код',
            'okato_name' => 'ОКАТО (район в городе)',
            'okato_code' => 'ОКАТО код',
            'address' => 'Адрес',
            'address_zip' => 'Индекс',
            'address_subject' => 'Субьект',
            'address_region' => 'Регион',
            'address_locality' => 'Город',
            'address_street' => 'Улица',
            'address_building' => 'Дом',
            'address_latitude' => 'Широта',
            'address_longitude' => 'Долгота',
            'vgu_name' => 'Вид учреждения',
            'vgu_code' => 'Вид учреждения код',
            'inn' => 'ИНН',
            'kpp' => 'КПП',
            'ogrn' => 'ОГРН',
            'phone' => 'Телефон',
            'email' => 'Электронная почта',
            'website' => 'Сайт',
            'manager' => 'Руководитель',
            'manager_position' => 'Должность руководителя',
            'manager_firstname' => 'Имя руководителя',
            'manager_middlename' => 'Отчество руководителя',
            'manager_lastname' => 'Фамилия руководителя',
            'version' => 'Версия',
            'modified_at' => 'Дата изменения на bus.gov.ru',
            'created_at' => 'Создано',
            'created_by' => 'Создал',
            'updated_at' => 'Обновлено',
            'updated_by' => 'Обновил',
            'deleted_at' => 'Удалено',
            'deleted_by' => 'Удалил',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'ts' => TimestampBehavior::class,
            'ba' => BlameableBehavior::class,
            'log' => LogBehavior::class,
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::class, ['id_institution' => 'id_institution']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecord()
    {
        return $this->hasOne(CollectionRecord::class, ['id_record' => 'id_record']);
    }

    /**
     * Возвращает массив статусов
     *
     * @return array
     */
    public static function getStatusNames()
    {
        return [
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_INACTIVE => 'Скрыт',
            self::STATUS_DELETED => 'Удален',
        ];
    }

    /**
     * Возвращает название статуса
     *
     * @return string
     */
    public function getStatusName()
    {
        $statuses = self::getStatusNames();

        if ($statuses[$this->status]) {
            return $statuses[$this->status];
        }

        return null;
    }

    /**
     * Возвращает массив типов
     *
     * @return array
     */
    public static function getTypeNames()
    {
        return [
            self::TYPE_STANDALONE => 'Автономное учреждение',
            self::TYPE_BUDGETARY => 'Бюджетное учреждение',
            self::TYPE_BREECH => 'Казенное учреждение',
            self::TYPE_PRIVATE => 'Частная собственность',
        ];
    }

    /**
     * Возвращает название типа
     *
     * @return string
     */
    public function getTypeName()
    {
        $types = self::getTypeNames();

        if ($types[$this->type]) {
            return $types[$this->type];
        }

        return null;
    }

    /**
     * Возвращает полный адрес
     *
     * @return string
     */
    public function getAddress()
    {
        return ($this->address_zip ? $this->address_zip . ', ' : null)
            . ($this->address_subject ? $this->address_subject . ', ' : null)
            . ($this->address_region ? $this->address_region . ', ' : null)
            . ($this->address_locality ? $this->address_locality . ', ' : null)
            . ($this->address_street ? $this->address_street . ', ' : null)
            . ($this->address_building ? $this->address_building : null);
    }

    /**
     * Возвращает руководителя
     *
     * @return string
     */
    public function getManager()
    {
        return $this->manager_position . ', '
            . $this->manager_firstname . ' '
            . $this->manager_middlename . ' '
            . $this->manager_lastname;
    }

    /**
     * @param $file
     * @return Institution|bool
     * @throws \Exception
     */
    public static function updateOrCreate($file)
    {
        $result = self::parseXml($file);

        if ($result['bus_id'] && in_array($result['oktmo_code'], [4701000001, 4701000])) {
            if (($institution = Institution::findOne(['bus_id' => $result['bus_id']])) === null) {
                $institution = new Institution();
            } else {
                if (!$institution->is_updating) {
                    return false;
                }
            }

            $institution->attributes = $result;

            if ($institution->save()) {
                return $institution;
            } else {
                print_r([
                    'errors' => $institution->errors,
                ]);
            }
        }

        return false;
    }

    /**
     * @param string $file
     * @return array
     * @throws \Exception
     */
    private static function parseXml($file) {
        $xmlfile = file_get_contents($file);
        $xml = simplexml_load_string($xmlfile);

        $data = [];
        foreach ($xml->children('http://bus.gov.ru/external/1')->body->position->children() as $element) {
            switch ($element->getName()) {
                case 'positionId':
                    $data['positionId'] = (string) ($element ?? null);
                    break;
                case 'changeDate':
                    $data['changeDate'] = (string) ($element ?? null);
                    break;
                case 'placer':
                    $data['placer'] = [
                        'regNum' => (string) ($element->regNum ?? null),
                        'fullName' => (string) ($element->fullName ?? null),
                        'inn' => (string) ($element->inn ?? null),
                        'kpp' => (string) ($element->kpp ?? null),
                    ];
                    break;
                case 'initiator':
                    $data['initiator'] = [
                        'regNum' => (string) ($element->regNum ?? null),
                        'fullName' => (string) ($element->fullName ?? null),
                        'inn' => (string) ($element->inn ?? null),
                        'kpp' => (string) ($element->kpp ?? null),
                    ];
                    break;
                case 'versionNumber':
                    $data['versionNumber'] = (string) ($element ?? null);
                    break;
                case 'main':
                    $data['main'] = [
                        'shortName' => (string) ($element->shortName ?? null),
                        'ogrn' => (string) ($element->ogrn ?? null),
                        'rbs' => [
                            'regNum' => (string) ($element->rbs->regNum ?? null),
                            'fullName' => (string) ($element->rbs->fullName ?? null),
                        ],
                        'grbs' => [
                            'regNum' => (string) ($element->grbs->regNum ?? null),
                            'fullName' => (string) ($element->grbs->fullName ?? null),
                        ],
                        'orgType' => (string) ($element->orgType ?? null),
                        'special' => (string) ($element->special ?? null),
                        'classifier' => [
                            'okfs' => [
                                'code' => (string) ($element->classifier->okfs->code ?? null),
                                'name' => (string) ($element->classifier->okfs->name ?? null),
                            ],
                            'okopf' => [
                                'code' => (string) ($element->classifier->okopf->code ?? null),
                                'name' => (string) ($element->classifier->okopf->name ?? null),
                            ],
                            'fullName' => (string) ($element->classifier->okpo ?? null),
                            'oktmo' => [
                                'code' => (string) ($element->classifier->oktmo->code ?? null),
                                'name' => (string) ($element->classifier->oktmo->name ?? null),
                            ],
                        ],
                        'complexAddress' => [
                            'coordinates' => [
                                'longitude' => (string) ($element->complexAddress->coordinates->longitude ?? null),
                                'latitude' => (string) ($element->complexAddress->coordinates->latitude ?? null),
                            ],
                        ],
                    ];

                    if ($element->complexAddress->address) {
                        $data['main']['complexAddress']['address'] = [
                            'zip' => (string) ($element->complexAddress->address->zip ?? null),
                            'subject' => [
                                'code' => (string) ($element->complexAddress->address->subject->code ?? null),
                                'name' => (string) ($element->complexAddress->address->subject->name ?? null),
                            ],
                            'region' => [
                                'code' => (string) ($element->complexAddress->address->region->code ?? null),
                                'name' => (string) ($element->complexAddress->address->region->name ?? null),
                            ],
                            'locality' => [
                                'code' => (string) ($element->complexAddress->address->locality->code ?? null),
                                'name' => (string) ($element->complexAddress->address->locality->name ?? null),
                            ],
                            'street' => [
                                'code' => (string) ($element->complexAddress->address->street->code ?? null),
                                'name' => (string) ($element->complexAddress->address->street->name ?? null),
                            ],
                            'building' => (string) ($element->complexAddress->address->building ?? null),
                        ];
                    } elseif ($element->complexAddress->complexAddress->address) {
                        $data['main']['complexAddress']['address'] = [
                            'zip' => (string) ($element->complexAddress->complexAddress->address->zip ?? null),
                            'subject' => [
                                'code' => (string) ($element->complexAddress->complexAddress->address->subject->code ?? null),
                                'name' => (string) ($element->complexAddress->complexAddress->address->subject->name ?? null),
                            ],
                            'region' => [
                                'code' => (string) ($element->complexAddress->complexAddress->address->region->code ?? null),
                                'name' => (string) ($element->complexAddress->complexAddress->address->region->name ?? null),
                            ],
                            'locality' => [
                                'code' => (string) ($element->complexAddress->complexAddress->address->locality->code ?? null),
                                'name' => (string) ($element->complexAddress->complexAddress->address->locality->name ?? null),
                            ],
                            'street' => [
                                'code' => (string) ($element->complexAddress->complexAddress->address->street->code ?? null),
                                'name' => (string) ($element->complexAddress->complexAddress->address->street->name ?? null),
                            ],
                            'building' => (string) ($element->complexAddress->complexAddress->address->building ?? null),
                        ];
                    }

                    foreach ($element->classifier->children() as $subElement) {
                        if ($subElement->getName() == 'okved') {
                            $data['main']['classifier']['okved'][] = [
                                'code' => (string) ($subElement->code ?? null),
                                'name' => (string) ($subElement->name ?? null),
                                'type' => (string) ($subElement->type ?? null),
                            ];
                        }
                    }
                    break;
                case 'additional':
                    $data['additional'] = [
                        'institutionType' => [
                            'code' => (string) ($element->institutionType->code ?? null),
                            'name' => (string) ($element->institutionType->name ?? null),
                        ],
                        'ppo' => [
                            'name' => (string) ($element->ppo->name ?? null),
                            'oktmo' => [
                                'code' => (string) ($element->ppo->oktmo->code ?? null),
                                'name' => (string) ($element->ppo->oktmo->name ?? null),
                            ],
                            'okato' => [
                                'code' => (string) ($element->ppo->okato->code ?? null),
                                'name' => (string) ($element->ppo->okato->name ?? null),
                            ],
                        ],
                        'phone' => (string) ($element->phone ?? null),
                        'www' => (string) ($element->www ?? null),
                        'section' => (string) ($element->section ?? null),
                        'okato' => [
                            'code' => (string) ($element->okato->code ?? null),
                            'name' => (string) ($element->okato->name ?? null),
                        ],
                        'eMail' => (string) ($element->eMail ?? null),
                    ];

                    foreach ($element->children() as $subElement) {
                        if ($subElement->getName() == 'activity') {
                            $data['additional']['activity']['okved'][] = [
                                'code' => (string) ($subElement->okved->code ?? null),
                                'name' => (string) ($subElement->okved->name ?? null),
                                'type' => (string) ($subElement->okved->type ?? null),
                            ];
                        }
                    }
                    break;
                case 'other':
                    $data['other'] = [
                        'chief' => [
                            'lastName' => (string) ($element->chief->lastName ?? null),
                            'firstName' => (string) ($element->chief->firstName ?? null),
                            'middleName' => (string) ($element->chief->middleName ?? null),
                            'position' => (string) ($element->chief->position ?? null),
                        ],
                        'ogrnData' => (string) ($element->ogrnData ?? null),
                    ];

                    foreach ($element->children() as $subElement) {
                        if ($subElement->getName() == 'founder') {
                            $data['other']['founder'][] = [
                                'regNum' => (string) ($subElement->regNum ?? null),
                                'fullName' => (string) ($subElement->fullName ?? null),
                            ];
                        }
                    }
                    break;
                case 'document':
                    $data['documents'][] = [
                        'name' => (string) ($element->name ?? null),
                        'date' => (string) ($element->date ?? null),
                        'url' => (string) ($element->url ?? null),
                        'type' => (string) ($element->type ?? null),
                    ];
                    break;
                default:
                    echo '-------------------------------- NOT PARSE ELEMENT --------------------------------' . PHP_EOL;
                    print_r([
                        'file' => $file,
                        'element' => $element->getName(),
                        'content' => $element,
                    ]);
                    break;
            }
        }

        $result = [
            'bus_id' => $data['positionId'] ?? null,
            'fullname' => $data['initiator']['fullName'] ?? null,
            'shortname' => $data['main']['shortName'] ?? null,
            'type' => $data['main']['orgType'] ?? null,
            'ppo' => $data['additional']['ppo']['name'] ?? null,
            'ppo_oktmo_name' => $data['additional']['ppo']['oktmo']['name'] ?? null,
            'ppo_oktmo_code' => $data['additional']['ppo']['oktmo']['code'] ?? null,
            'ppo_okato_name' => $data['additional']['ppo']['okato']['name'] ?? null,
            'ppo_okato_code' => $data['additional']['ppo']['okato']['code'] ?? null,
            'okpo' => $data['main']['classifier']['fullName'] ?? null,
            'okopf_name' => $data['main']['classifier']['okopf']['name'] ?? null,
            'okopf_code' => $data['main']['classifier']['okopf']['code'] ?? null,
            'okfs_name' => $data['main']['classifier']['okfs']['name'] ?? null,
            'okfs_code' => $data['main']['classifier']['okfs']['code'] ?? null,
            'oktmo_name' => $data['main']['classifier']['oktmo']['name'] ?? null,
            'oktmo_code' => $data['main']['classifier']['oktmo']['code'] ?? null,
            'okato_name' => $data['additional']['okato']['name'] ?? null,
            'okato_code' => $data['additional']['okato']['code'] ?? null,
            'address_zip' => $data['main']['complexAddress']['address']['zip'] ?? null,
            'address_subject' => $data['main']['complexAddress']['address']['subject']['name'] ?? null,
            'address_region' => $data['main']['complexAddress']['address']['region']['name'] ?? null,
            'address_locality' => $data['main']['complexAddress']['address']['locality']['name'] ?? null,
            'address_street' => $data['main']['complexAddress']['address']['street']['name'] ?? null,
            'address_building' => $data['main']['complexAddress']['address']['building'] ?? null,
            'address_latitude' => $data['main']['complexAddress']['coordinates']['latitude'] ?? null,
            'address_longitude' => $data['main']['complexAddress']['coordinates']['longitude'] ?? null,
            'vgu_name' => $data['additional']['institutionType']['name'] ?? null,
            'vgu_code' => $data['additional']['institutionType']['code'] ?? null,
            'inn' => $data['initiator']['inn'] ?? null,
            'kpp' => $data['initiator']['kpp'] ?? null,
            'ogrn' => $data['main']['ogrn'] ?? null,
            'phone' => $data['additional']['phone'] ?? null,
            'email' => $data['additional']['eMail'] ?? null,
            'website' => $data['additional']['www'] ?? null,
            'manager_position' => $data['other']['chief']['position'] ?? null,
            'manager_firstname' => $data['other']['chief']['firstName'] ?? null,
            'manager_middlename' => $data['other']['chief']['middleName'] ?? null,
            'manager_lastname' => $data['other']['chief']['lastName'] ?? null,
            'version' => $data['versionNumber'] ?? null,
            'modified_at' => (new DateTime($data['changeDate']))->format('U') ?? null,
            'last_update' => time(),
        ];

        $okveds = [];
        if (isset($data['additional']['activity']['okved']) && count($data['additional']['activity']['okved'])) {
            foreach ($data['additional']['activity']['okved'] as $okved) {
                if ($okved['type'] == 'C') {
                    $main_okved = [
                        'code' => $okved['code'] ?? null,
                        'name' => $okved['name'] ?? null,
                    ];
                } else {
                    $okveds[] = [
                        'code' => $okved['code'] ?? null,
                        'name' => $okved['name'] ?? null,
                    ];
                }
            }
        }

        if (isset($main_okved)) {
            $result['okved_code'] = $main_okved['code'] ?? null;
            $result['okved_name'] = $main_okved['name'] ?? null;
        }

        if ($okveds) {
            $result['okved'] = $okveds;
        }

        if (isset($data['other']['founder']) && count($data['other']['founder'])) {
            foreach ($data['other']['founder'] as $founder) {
                $result['founder'][] = [
                    'regnum' => $founder['regNum'] ?? null,
                    'fullname' => $founder['fullName'] ?? null,
                ];
            }
        }

        return $result;
    }
}
