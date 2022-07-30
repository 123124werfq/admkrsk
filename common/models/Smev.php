<?php

namespace common\models;

use common\components\smevsoap\ResponseContentType;
use Yii;
use yii\base\Model;
use common\components\smevsoap\SMEVServiceAdapterService;
use common\components\smevsoap\ClientMessage;
use common\components\smevsoap\RequestMessageType;
use common\components\smevsoap\ResponseMessageType;
use common\components\smevsoap\RequestMetadataType;
use common\components\smevsoap\ResponseMetadataType;
use common\components\smevsoap\RequestContentType;
use common\components\smevsoap\Content;
use common\components\smevsoap\Status;
use common\components\smevsoap\MessagePrimaryContent;

class Smev extends Model
{
    //private $host = 'http://10.24.0.75:7575/ws?wsdl';


    private $testTemplate = '<?xml version=\"1.0\" encoding=\"UTF-8\"?>
             <tns:ClientMessage xsi:schemaLocation=\"urn://x-artefacts-smev-gov-ru/services/service-adapter/types smev-service-adapter-types.xsd\" xmlns:n1=\"http://www.altova.com/samplexml/other-namespace\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:tns=\"urn://x-artefacts-smev-gov-ru/services/service-adapter/types\">
               <tns:itSystem>240501</tns:itSystem>
               <tns:RequestMessage>
                  <tns:RequestMetadata>
                     <tns:clientId>3e83e83a-6a23-4908-b0d2-e3ad08fe2584</tns:clientId>
                     <tns:createGroupIdentity>
                        <tns:FRGUServiceCode>00000000000000000000</tns:FRGUServiceCode>
                        <tns:FRGUServiceDescription>00000000000000000000</tns:FRGUServiceDescription>
                     <tns:FRGUServiceRecipientDescription>00000000000000000000</tns:FRGUServiceRecipientDescription>
                     </tns:createGroupIdentity>
                     <tns:testMessage>true</tns:testMessage>
                  </tns:RequestMetadata>
                  <tns:RequestContent>
                     <tns:content>
                        <tns:MessagePrimaryContent>
                              <fssp:InquiryDocumentsResponse xmlns:c=\"urn://x-artifacts-fssp-ru/mvv/smev3/container/1.0.1\" xmlns:fssp=\"urn://x-artifacts-fssp-ru/mvv/smev3/inquiry-documents/1.0.1\">
                                <c:ID>038e7a1f-6b33-4843-9155-acf3e169afc8</c:ID>
                                <c:Date>2015-10-12T12:00:00</c:Date>
                                <c:SenderOrganizationCode>45388000</c:SenderOrganizationCode>
                                <c:SenderDepartmentCode>40</c:SenderDepartmentCode>
                                <c:ReceiverOrganizationCode>ФССП</c:ReceiverOrganizationCode>
                                <c:ReceiverDepartmentCode>69025</c:ReceiverDepartmentCode>
                                <c:Document>
                                  <c:Organization>45388000</c:Organization>
                                  <c:Department>40</c:Department>
                                  <c:ID>1177</c:ID>
                                  <c:IncomingDocKey>28251007260916</c:IncomingDocKey>
                                  <c:Type>Answer</c:Type>
                                  <c:DocumentDate>2015-10-12</c:DocumentDate>
                                  <c:DocumentNumber>1177</c:DocumentNumber>
                                  <c:DocumentCaseNumber>7407/14/69025-ИП</c:DocumentCaseNumber>
                                  <c:Filename>res_3avv6684-01fb-4n1c-9d9b-f1d2487edcdf.zip</c:Filename>
                                </c:Document>
                              </fssp:InquiryDocumentsResponse>
                        </tns:MessagePrimaryContent>
                     </tns:content>
                  </tns:RequestContent>
               </tns:RequestMessage>
            </tns:ClientMessage>';

    // эталонный запрос на регистрацию заявления
    private $testRequest = '
            <ns:ElkOrderRequest env="DEV" xmlns:ns="http://epgu.gosuslugi.ru/elk/order/3.1.0">
                <ns:CreateOrdersRequest>
                    <ns:orders>
                        <ns:order>
                            <ns:userId>1234567890</ns:userId>
                            <ns:eServiceCode>10000012285</ns:eServiceCode>
                            <ns:serviceTargetCode>10000569524</ns:serviceTargetCode>
                            <ns:userSelectedRegion>00000000000</ns:userSelectedRegion>
                            <ns:orderNumber>2022</ns:orderNumber>
                            <ns:requestDate>2022-05-10T10:49:45</ns:requestDate>
                            <ns:orderUrl>http://ext.system.com/orders/1</ns:orderUrl>
                            <ns:statusHistoryList> 
                                <ns:statusHistory>
                                    <ns:status>1</ns:status>
                                    <ns:statusExtId>21022</ns:statusExtId>
                                    <ns:statusDate>2017-09-10T10:49:45</ns:statusDate>
                                </ns:statusHistory>
                                <ns:statusHistory>
                                    <ns:status>3</ns:status>
                                    <ns:statusExtId>21072</ns:statusExtId>
                                    <ns:statusDate>2017-09-15T10:00:00</ns:statusDate>
                                    <ns:statusComment>Документы готовы</ns:statusComment>
                                    <ns:attachments>
                                        <ns:attachment>
                                            <ns:FSuuid>265bdb70-b991-11e7-ba7c-a4db30d23ddc</ns:FSuuid>
                                        </ns:attachment>
                                        <ns:attachment>
                                            <ns:FSuuid>887bdb70-b991-11e7-ba7c-a4db30d23ddc</ns:FSuuid>
                                        </ns:attachment>							
                                    </ns:attachments>
                                </ns:statusHistory>
                            </ns:statusHistoryList>
                        </ns:order>
                        <ns:order>
                            <ns:user>
                                 <ns:snils>127-941-306 66</ns:snils>
                                 <ns:lastName>Иванов</ns:lastName>
                                 <ns:firstName>Иван</ns:firstName>
                                 <ns:middleName>Иванович</ns:middleName>
                              </ns:user>
                            <ns:serviceTargetCode>10000569543</ns:serviceTargetCode>
                            <ns:userSelectedRegion>00000000000</ns:userSelectedRegion>
                            <ns:orderNumber>2027</ns:orderNumber>
                            <ns:requestDate>2017-09-10T15:43:45</ns:requestDate>
                            <ns:statusHistoryList> 
                                <ns:statusHistory>
                                    <ns:status>1</ns:status>
                                    <ns:statusDate>2017-09-10T15:43:45</ns:statusDate>
                                </ns:statusHistory>
                            </ns:statusHistoryList>
                        </ns:order>			
                    </ns:orders>
                </ns:CreateOrdersRequest>
            </ns:ElkOrderRequest>';

    private $testRequest_my = '
            <ns:ElkOrderRequest env="EPGU" xmlns:ns="http://epgu.gosuslugi.ru/elk/order/3.1.0">
                <ns:CreateOrdersRequest>
                    <ns:orders>
                        <ns:order>
                            <ns:userId>1000412965</ns:userId>
                            <ns:eServiceCode>10000012285</ns:eServiceCode>
                            <ns:serviceTargetCode>10000569524</ns:serviceTargetCode>
                            <ns:userSelectedRegion>00000000000</ns:userSelectedRegion>
                            <ns:orderNumber>9001</ns:orderNumber>
                            <ns:requestDate>2022-07-30T10:48:40</ns:requestDate>
                            <ns:orderUrl>http://ext.system.com/orders/1</ns:orderUrl>
                            <ns:statusHistoryList> 
                                <ns:statusHistory>
                                    <ns:status>1</ns:status>
                                    <ns:statusExtId>21022</ns:statusExtId>
                                    <ns:statusDate>2022-07-30T10:48:40</ns:statusDate>
                                </ns:statusHistory>
                                <ns:statusHistory>
                                    <ns:status>3</ns:status>
                                    <ns:statusExtId>21072</ns:statusExtId>
                                    <ns:statusDate>2022-07-30T10:52:40</ns:statusDate>
                                    <ns:statusComment>Документы готовы</ns:statusComment>
                                    <ns:attachments>
                                        <ns:attachment>
                                            <ns:FSuuid>265bdb70-b991-11e7-ba7c-a4db40d23ddc</ns:FSuuid>
                                        </ns:attachment>
                                        <ns:attachment>
                                            <ns:FSuuid>887bdb70-b991-11e7-ba7c-a4db40d23ddc</ns:FSuuid>
                                        </ns:attachment>							
                                    </ns:attachments>
                                </ns:statusHistory>
                            </ns:statusHistoryList>
                        </ns:order>
                        <ns:order>
                            <ns:user>
                                 <ns:snils>062-160-930 30</ns:snils>
                                 <ns:lastName>Богданов</ns:lastName>
                                 <ns:firstName>Константин</ns:firstName>
                                 <ns:middleName>Валериевич</ns:middleName>
                              </ns:user>
                            <ns:serviceTargetCode>10000569543</ns:serviceTargetCode>
                            <ns:userSelectedRegion>00000000000</ns:userSelectedRegion>
                            <ns:orderNumber>9001</ns:orderNumber>
                            <ns:requestDate>2022-07-30T10:48:40</ns:requestDate>
                            <ns:statusHistoryList> 
                                <ns:statusHistory>
                                    <ns:status>1</ns:status>
                                    <ns:statusDate>2022-07-30T10:48:40</ns:statusDate>
                                </ns:statusHistory>
                            </ns:statusHistoryList>
                        </ns:order>			
                    </ns:orders>
                </ns:CreateOrdersRequest>
            </ns:ElkOrderRequest>'; 

    private $testRequest_my2 = '
            <ns:ElkOrderRequest env="DEV" xmlns:ns="http://epgu.gosuslugi.ru/elk/order/3.1.0">
                <ns:CreateOrdersRequest>
                    <ns:orders>
                        <ns:order>
                            <ns:userId>1000412965</ns:userId>
                            <ns:eServiceCode>10000012285</ns:eServiceCode>
                            <ns:serviceTargetCode>10000569524</ns:serviceTargetCode>
                            <ns:userSelectedRegion>00000000000</ns:userSelectedRegion>
                            <ns:orderNumber>2022</ns:orderNumber>
                            <ns:requestDate>2022-05-10T10:49:45</ns:requestDate>
                            <ns:orderUrl>http://ext.system.com/orders/1</ns:orderUrl>
                            <ns:statusHistoryList> 
                                <ns:statusHistory>
                                    <ns:status>1</ns:status>
                                    <ns:statusExtId>21022</ns:statusExtId>
                                    <ns:statusDate>2017-09-10T10:49:45</ns:statusDate>
                                </ns:statusHistory>
                                <ns:statusHistory>
                                    <ns:status>3</ns:status>
                                    <ns:statusExtId>21072</ns:statusExtId>
                                    <ns:statusDate>2017-09-15T10:00:00</ns:statusDate>
                                    <ns:statusComment>Документы готовы</ns:statusComment>
                                    <ns:attachments>
                                        <ns:attachment>
                                            <ns:FSuuid>265bdb70-b991-11e7-ba7c-a4db30d23ddc</ns:FSuuid>
                                        </ns:attachment>
                                        <ns:attachment>
                                            <ns:FSuuid>887bdb70-b991-11e7-ba7c-a4db30d23ddc</ns:FSuuid>
                                        </ns:attachment>							
                                    </ns:attachments>
                                </ns:statusHistory>
                            </ns:statusHistoryList>
                        </ns:order>
                        <ns:order>
                            <ns:user>
                                <ns:snils>062-160-930 30</ns:snils>
                                <ns:lastName>Богданов</ns:lastName>
                                <ns:firstName>Константин</ns:firstName>
                                <ns:middleName>Валериевич</ns:middleName>
                            </ns:user>
                            <ns:serviceTargetCode>10000569543</ns:serviceTargetCode>
                            <ns:userSelectedRegion>00000000000</ns:userSelectedRegion>
                            <ns:orderNumber>2027</ns:orderNumber>
                            <ns:requestDate>2017-09-10T15:43:45</ns:requestDate>
                            <ns:statusHistoryList> 
                                <ns:statusHistory>
                                    <ns:status>1</ns:status>
                                    <ns:statusDate>2017-09-10T15:43:45</ns:statusDate>
                                </ns:statusHistory>
                            </ns:statusHistoryList>
                        </ns:order>			
                    </ns:orders>
                </ns:CreateOrdersRequest>
            </ns:ElkOrderRequest>';            

    // эталонный запрос на передачу статуса
    private $testRequest2 = '<?xml version="1.0" encoding="UTF-8"?>
            <ns:ElkOrderRequest env="EPGU" xmlns:ns="http://epgu.gosuslugi.ru/elk/order/3.2.0">
                <ns:UpdateOrdersRequest>
                    <ns:orders>
                        <ns:order>
                            <ns:elkOrderNumber>10000001</ns:elkOrderNumber>
                            <ns:statusHistoryList> 
                                <ns:statusHistory>
                                    <ns:status>1</ns:status>
                                    <ns:statusExtId>21022</ns:statusExtId>
                                    <ns:statusDate>2017-09-10T10:49:45</ns:statusDate>
                                </ns:statusHistory>
                                <ns:statusHistory>
                                    <ns:status>3</ns:status>
                                    <ns:statusExtId>21072</ns:statusExtId>
                                    <ns:statusDate>2017-09-15T10:00:00</ns:statusDate>
                                    <ns:statusComment>Документы готовы</ns:statusComment>
                                    <ns:attachments>
                                        <ns:attachment>
                                            <ns:FSuuid>92d337ca-6869-11e7-9b64-0242ac110012</ns:FSuuid>
                                        </ns:attachment>
                                        <ns:attachment>
                                            <ns:FSuuid>92d337ca-6869-11e7-9b64-0242ac110015</ns:FSuuid>
                                        </ns:attachment>                            
                                    </ns:attachments>                       
                                </ns:statusHistory>
                            </ns:statusHistoryList>
                        </ns:order>
                        <ns:order>
                            <ns:elkOrderNumber>10000002</ns:elkOrderNumber>
                            <ns:equeueEvent>
                                <ns:equeueInvitation>
                                    <ns:organizationData>
                                        <ns:organizationId>0000000000000000000</ns:organizationId>
                                    </ns:organizationData>  
                                    <ns:startDate>2019-08-11</ns:startDate>
                                    <ns:endDate>2019-12-11</ns:endDate>
                                </ns:equeueInvitation>   
                            </ns:equeueEvent>
                            <ns:statusHistoryList> 
                                <ns:statusHistory>
                                    <ns:status>1</ns:status>
                                    <ns:statusDate>2017-09-13T15:43:45</ns:statusDate>
                                </ns:statusHistory>
                                <ns:statusHistory>
                                    <ns:status>3</ns:status>
                                    <ns:statusDate>2017-09-14T15:43:45</ns:statusDate>
                                </ns:statusHistory>
                            </ns:statusHistoryList>
                        </ns:order>
                        <ns:order>
                            <ns:elkOrderNumber>10000003</ns:elkOrderNumber>
                            <ns:equeueEvent>
                                <ns:equeueClosed></ns:equeueClosed>   
                            </ns:equeueEvent>
                            <ns:statusHistoryList> 
                                <ns:statusHistory>
                                    <ns:status>1</ns:status>
                                    <ns:statusDate>2017-09-10T15:43:45</ns:statusDate>
                                </ns:statusHistory>
                                <ns:statusHistory>
                                    <ns:status>3</ns:status>
                                    <ns:statusDate>2017-09-14T15:43:45</ns:statusDate>
                                </ns:statusHistory>
                            </ns:statusHistoryList>
                        </ns:order>
                        <ns:order>
                            <ns:elkOrderNumber>10000004</ns:elkOrderNumber>
                            <ns:equeueEvent>
                                <ns:equeueInvitation>
                                    <ns:organizationData>
                                        <ns:organizationId>0000000000000000000</ns:organizationId>
                                    </ns:organizationData>  
                                    <ns:startDate>2019-08-15</ns:startDate>
                                    <ns:endDate>2019-09-15</ns:endDate>
                                </ns:equeueInvitation>   
                            </ns:equeueEvent>
                        </ns:order>         
                    </ns:orders>
                </ns:UpdateOrdersRequest>
            </ns:ElkOrderRequest>';

    // для ответа
    private $testResponse = '';


    public function connect()
    {
        $client = new SMEVServiceAdapterService;

        //var_dump($client->__getFunctions());
//die();
        return $client;
    }

    private function getGUID(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
            return $uuid;
        }
    } 

    public function testMessage()
    {
        $cl = $this->connect();

        //$clientId = 'a6fc378d-c555-4485-8841-3137e9337791';
        $clientId = $this->getGUID();

        $primaryContent     = new MessagePrimaryContent($this->testRequest);
        $content            = new Content($primaryContent, null, null);
        $reqCoontent        = new RequestContentType($content);
        $reqMeta            = new RequestMetadataType($clientId , null, null, null, null, true, null, null, null);
        $rq                 = new RequestMessageType('CreateOrdersRequest', $reqMeta, $reqCoontent);

        $respMeta               = new ResponseMetadataType('48135eb7-20d6-4dea-8b1b-9895d058eff5', $clientId );
        $primaryContentInput    = new MessagePrimaryContent($this->testResponse);
        $respInput              = new Content($primaryContentInput,null, null);
        $respStatus             = new Status(null, null, null);
        $respContent = new ResponseContentType($respInput, $respStatus);
        $resp = new ResponseMessageType('CreateOrdersResponse', $respMeta, $respContent);

//        $message = new ClientMessage(240501, $rq, $resp);
        $message = new ClientMessage(240501, $rq, null);

       // $cl->Send($message);

        $res = $cl->Send($message);
        var_dump($res);
    }

    public function testMessageEpgu()
    {
        $cl = $this->connect([], 'http://10.24.0.75:7575/ws?wsdl');

        $clientId = $this->getGUID();

        $primaryContent     = new MessagePrimaryContent($this->testRequest_m2y);
        $content            = new Content($primaryContent, null, null);
        $reqCoontent        = new RequestContentType($content);
        $reqMeta            = new RequestMetadataType($clientId , null, null, null, null, true, null, null, null);
        $rq                 = new RequestMessageType('CreateOrdersRequest', $reqMeta, $reqCoontent);

        $respMeta               = new ResponseMetadataType('48135eb7-20d6-4dea-8b1b-9895d058eff5', $clientId );
        $primaryContentInput    = new MessagePrimaryContent($this->testResponse);
        $respInput              = new Content($primaryContentInput,null, null);
        $respStatus             = new Status(null, null, null);
        $respContent = new ResponseContentType($respInput, $respStatus);
        $resp = new ResponseMessageType('CreateOrdersResponse', $respMeta, $respContent);

//        $message = new ClientMessage(240501, $rq, $resp);
        $message = new ClientMessage(240501, $rq, null);

       // $cl->Send($message);

        $res = $cl->Send($message);
        var_dump($res);
    }    

}