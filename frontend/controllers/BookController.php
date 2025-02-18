<?php

namespace frontend\controllers;

use Yii;

use common\models\Book;
use common\models\User;

use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;


class BookController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'service',  'available', 'dates', 'intervals', 'proceed', 'book', 'reject'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    public function actionIndex($page)
    {
        $id_user = Yii::$app->user->id;

        $books = Book::find()->where(['id_user' => $id_user])->andWhere(['state' => '1'])->andWhere("(extract(epoch from to_timestamp(date, 'YYYY-MM-DD'))+ CAST(coalesce(time, '0') AS integer)*60) > extract(epoch from now())");
        $dataProvider = new ActiveDataProvider([
            'query' => $books
        ]);

        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'page' => $page
        ]);

    }


    public function actionBook($page)
    {
        //if(Yii::$app->user->isGuest)
        //    return $this->redirect('/login');

        $suo0 = new Book;
        $suo1 = new Book;

        //$tree0 = $suo0->getTree(0);
        //$tree1 = $suo1->getTree(1);

        $tree0 = unserialize('O:8:"stdClass":2:{s:9:"errorCode";i:0;s:6:"groups";a:3:{i:0;O:8:"stdClass":4:{s:2:"id";i:19;s:9:"parent_id";i:0;s:10:"operations";a:4:{i:0;O:8:"stdClass":4:{s:8:"alias_id";i:3;s:12:"operation_id";i:1;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:80:"Выдача разрешения на строительство объекта";}}s:8:"comments";a:0:{}}i:1;O:8:"stdClass":4:{s:8:"alias_id";i:5;s:12:"operation_id";i:7;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:92:"Выдача разрешений на ввод объектов в эксплуатацию";}}s:8:"comments";a:0:{}}i:2;O:8:"stdClass":4:{s:8:"alias_id";i:10;s:12:"operation_id";i:12;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:253:"Прием документов и выдача решений о переводе или об отказе в переводе жилого помещения в нежилое или нежилого помещения в жилое помещение";}}s:8:"comments";a:0:{}}i:3;O:8:"stdClass":4:{s:8:"alias_id";i:21;s:12:"operation_id";i:23;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:126:"Согласование переустройства и (или) перепланировки жилого помещения";}}s:8:"comments";a:0:{}}}s:6:"titles";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:36:"Градостроительство";}}}i:1;O:8:"stdClass":4:{s:2:"id";i:20;s:9:"parent_id";i:0;s:10:"operations";a:8:{i:0;O:8:"stdClass":4:{s:8:"alias_id";i:15;s:12:"operation_id";i:17;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:81:"Выдача архитектурно-планировочного задания";}}s:8:"comments";a:0:{}}i:1;O:8:"stdClass":4:{s:8:"alias_id";i:9;s:12:"operation_id";i:11;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:127:"Выдача разрешения на установку и эксплуатацию рекламной конструкции";}}s:8:"comments";a:0:{}}i:2;O:8:"stdClass":4:{s:8:"alias_id";i:8;s:12:"operation_id";i:10;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:161:"Выдача сведений из информационной системы обеспечения градостроительной деятельности";}}s:8:"comments";a:0:{}}i:3;O:8:"stdClass":4:{s:8:"alias_id";i:7;s:12:"operation_id";i:9;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:96:"Выдача градостроительного плана земельного участка";}}s:8:"comments";a:0:{}}i:4;O:8:"stdClass":4:{s:8:"alias_id";i:11;s:12:"operation_id";i:13;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:272:"Прием заявлений и выдача решения о разрешении на условно разрешенный вид использования  земельного участка или объекта капитального строительства";}}s:8:"comments";a:0:{}}i:5;O:8:"stdClass":4:{s:8:"alias_id";i:13;s:12:"operation_id";i:15;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:131:"Принятие решения об утверждении документации по планировке территории";}}s:8:"comments";a:0:{}}i:6;O:8:"stdClass":4:{s:8:"alias_id";i:14;s:12:"operation_id";i:16;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:127:"Принятие решения о подготовке документации по планировке территории";}}s:8:"comments";a:0:{}}i:7;O:8:"stdClass":4:{s:8:"alias_id";i:12;s:12:"operation_id";i:14;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:306:"Прием заявлений и выдача решения о разрешении на отклонение от предельных параметров разрешенного строительства, реконструкции объектов капитального строительства";}}s:8:"comments";a:0:{}}}s:6:"titles";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:22:"Архитектура";}}}i:2;O:8:"stdClass":4:{s:2:"id";i:0;s:9:"parent_id";i:0;s:10:"operations";a:0:{}s:6:"titles";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:4:"root";}}}}}');
        $tree1 = unserialize('O:8:"stdClass":2:{s:9:"errorCode";i:0;s:6:"groups";a:3:{i:0;O:8:"stdClass":4:{s:2:"id";i:16;s:9:"parent_id";i:0;s:10:"operations";a:20:{i:0;O:8:"stdClass":4:{s:8:"alias_id";i:13;s:12:"operation_id";i:15;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:173:"Предоставление земельного участка для строительства без проведения торгов (прием документов)";}}s:8:"comments";a:0:{}}i:1;O:8:"stdClass":4:{s:8:"alias_id";i:18;s:12:"operation_id";i:20;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:112:"Предоставлении земельного участка для ИЖС (прием документов)";}}s:8:"comments";a:0:{}}i:2;O:8:"stdClass":4:{s:8:"alias_id";i:12;s:12:"operation_id";i:14;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:294:"Предоставление земельного участка в собственность для ИЖС многодетным гражданам, имеющим место жительства на территории города Красноярска (прием документов)";}}s:8:"comments";a:0:{}}i:3;O:8:"stdClass":4:{s:8:"alias_id";i:25;s:12:"operation_id";i:27;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:188:"Утверждение схемы расположения земельного участка на кадастровом плане территории (прием документов)";}}s:8:"comments";a:0:{}}i:4;O:8:"stdClass":4:{s:8:"alias_id";i:10;s:12:"operation_id";i:12;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:486:"Предоставление в общую долевую собственность собственников земельных участков, расположенных в границах территории ведения гражданами садоводства или огородничества, земельного участка, относящегося к имуществу общего пользования, бесплатно (прием документов)";}}s:8:"comments";a:0:{}}i:5;O:8:"stdClass":4:{s:8:"alias_id";i:17;s:12:"operation_id";i:19;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:825:"Предоставление земельных участков, предназначенных для ведения садоводства, огородничества или дачного хозяйства, без проведения торгов в собственность бесплатно членам некоммерческих организаций, созданных до 1 января 2019 года для ведения садоводства, огородничества или дачного хозяйства, и членам садоводческих или огороднических некоммерческих товариществ, созданных путем реорганизации таких некоммерческих организаций (прием документов)";}}s:8:"comments";a:0:{}}i:6;O:8:"stdClass":4:{s:8:"alias_id";i:16;s:12:"operation_id";i:18;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:352:"Предоставление земельных участков в собственность за плату лицам, являющимся собственниками зданий, сооружений (помещений в них), расположенных на таких земельных участках (прием документов)";}}s:8:"comments";a:0:{}}i:7;O:8:"stdClass":4:{s:8:"alias_id";i:15;s:12:"operation_id";i:17;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:409:"Предоставление земельных или лесных участков в аренду лицам, являющимся правообладателями зданий, сооружений, помещений в них, расположенных на таких земельных или лесных участках, без проведения торгов (прием документов)";}}s:8:"comments";a:0:{}}i:8;O:8:"stdClass":4:{s:8:"alias_id";i:23;s:12:"operation_id";i:25;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:234:"Присвоение, изменение, аннулирование адресов объектам недвижимости в городе Красноярске (прием документов,  выдача результата)";}}s:8:"comments";a:0:{}}i:9;O:8:"stdClass":4:{s:8:"alias_id";i:8;s:12:"operation_id";i:10;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:154:"Предварительное согласование предоставления земельного участка (прием документов)";}}s:8:"comments";a:0:{}}i:10;O:8:"stdClass":4:{s:8:"alias_id";i:9;s:12:"operation_id";i:11;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:168:"Предварительное согласование предоставления земельного участка для ИЖС (прием документов)";}}s:8:"comments";a:0:{}}i:11;O:8:"stdClass":4:{s:8:"alias_id";i:14;s:12:"operation_id";i:16;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:195:"Предоставление земельного участка, находящегося  в постоянное (бессрочное) пользование (прием документов)";}}s:8:"comments";a:0:{}}i:12;O:8:"stdClass":4:{s:8:"alias_id";i:11;s:12:"operation_id";i:13;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:151:"Предоставление земельного участка в безвозмездное пользование (прием документов)";}}s:8:"comments";a:0:{}}i:13;O:8:"stdClass":4:{s:8:"alias_id";i:22;s:12:"operation_id";i:24;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:256:"Принятие решения о проведении аукциона по продаже земельного участка или заключения договора аренды земельного участка (прием документов)";}}s:8:"comments";a:0:{}}i:14;O:8:"stdClass":4:{s:8:"alias_id";i:19;s:12:"operation_id";i:21;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:343:"Прекращение права постоянного (бессрочного) пользования земельным участком или права пожизненного наследуемого владения земельным участком в связи с отказом от права (прием документов)";}}s:8:"comments";a:0:{}}i:15;O:8:"stdClass":4:{s:8:"alias_id";i:7;s:12:"operation_id";i:9;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:271:"Перераспределение земель и (или) земельных участков, находящихся в государственной или муниципальной собственности, между собой (прием документов)";}}s:8:"comments";a:0:{}}i:16;O:8:"stdClass":4:{s:8:"alias_id";i:6;s:12:"operation_id";i:8;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:357:"Перераспределение земель и (или) земельных участков, находящихся в государственной или муниципальной собственности, и земельных участков, находящихся в частной собственности (прием документов)";}}s:8:"comments";a:0:{}}i:17;O:8:"stdClass":4:{s:8:"alias_id";i:4;s:12:"operation_id";i:6;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:367:"Выдача разрешения на размещение объектов, размещение которых может осуществляться на землях или земельных участках без редоставления земельных участков и установления сервитутов (прием документов)";}}s:8:"comments";a:0:{}}i:18;O:8:"stdClass":4:{s:8:"alias_id";i:26;s:12:"operation_id";i:28;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:113:"Проведение муниципальной экспертизы проектов освоения лесов";}}s:8:"comments";a:0:{}}i:19;O:8:"stdClass":4:{s:8:"alias_id";i:5;s:12:"operation_id";i:7;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:75:"Выдача результата предоставления услуги";}}s:8:"comments";a:0:{}}}s:6:"titles";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:47:"Департамент горимущества";}}}i:1;O:8:"stdClass":4:{s:2:"id";i:17;s:9:"parent_id";i:0;s:10:"operations";a:2:{i:0;O:8:"stdClass":4:{s:8:"alias_id";i:2;s:12:"operation_id";i:2;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:38:"Выдача документов ЦН";}}s:8:"comments";a:0:{}}i:1;O:8:"stdClass":4:{s:8:"alias_id";i:21;s:12:"operation_id";i:23;s:5:"names";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:36:"Прием документов ЦН";}}s:8:"comments";a:0:{}}}s:6:"titles";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:42:"МКУ Центр недвижимости";}}}i:2;O:8:"stdClass":4:{s:2:"id";i:0;s:9:"parent_id";i:0;s:10:"operations";a:0:{}s:6:"titles";a:1:{i:0;O:8:"stdClass":2:{s:4:"lang";s:2:"ru";s:4:"text";s:4:"root";}}}}}');

        $commonTree = [];

        foreach ([$tree0->groups, $tree1->groups] as $tree)
            foreach ($tree as $group)
            {
                $ops = [];
                foreach ($group->operations as $operation)
                    $ops[$operation->operation_id] = $operation->names[0]->text;

                if(count($ops))
                    $commonTree[$group->id.":".$group->titles[0]->text] = $ops;
            }

        $user = User::findOne(Yii::$app->user->id);

//        var_dump($commonTree); die();

        return $this->render('index', [
            'esiauser' => $user->getEsiainfo()->one(),
            'commontree' => $commonTree,
            'page' => $page
        ]);
    }

    public function actionAvailable()
    {
        $serv = (int)$_POST['service'];
        $type = (int)$_POST['type'];

        switch ($type){
            case 19:
            case 20:
                $conn = 0; break;
            default:
                $conn = 1;
        }

        $b0 = new Book;
        $cn = $b0->connect($conn);
        $res = $b0->dateAvailable($serv);

        $output = [];

        if(!is_array($res))
            return json_encode($output);


        foreach ($res as $timeslot)
        {
            if($timeslot->intervals->errorCode == 0)
                $output[] = $timeslot->date;
        }

        return json_encode($output);
    }

    public function actionIntervals()
    {
        $serv = (int)$_POST['service'];
        $date = (string)$_POST['datetext'];
        $type = (int)$_POST['type'];

        switch ($type){
            case 19:
            case 20:
                $conn = 0; break;
            default:
                $conn = 1;
        }

        $b0 = new Book;
        $cn = $b0->connect($conn);
        $res = $b0->freeIntervals($serv, $date);

        $output = [];

        if(isset($res->combinations))
            foreach($res->combinations as $comb)
                $output[] = $comb->operations[0]->start;

        return json_encode($output);
    }


    public function actionProceed()
    {
        if(!isset($_POST['type']))
            return false;

        $type = (int)$_POST['type'];
        $op_id = (int)$_POST["service_$type"];
        $date = $_POST['bookdate'];
        $time = (int)$_POST['time'];

        $dateparts = explode(".",$date);
        $date = $dateparts[2]."-".$dateparts[1]."-".$dateparts[0];

        switch ($type){
            case 19:
            case 20:
                $conn = 0; break;
            default:
                $conn = 1;
        }

        $b = new Book();
        $b->connect($conn);
        $reserve = $b->reserveTime($op_id, $date, $time);

        //var_dump($reserve);die();

        if($reserve)
            return $this->render('proceed');
        else
            return $this->render('proceederror');

    }


    public function actionReject()
    {
        $pin = $_GET['code'];
        $id_user = Yii::$app->user->id;

        $b0 = new Book;
//        $cn = $b0->connect(0);
        $cn = $b0->connect(1);
        $res = $b0->deleteTime($pin);

        if($res)
            $this->redirect('/personal/book');

    }

}
