<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190915180445UserAdEsiaMeta
 */
class M190915180445UserAdEsiaMeta extends Migration
{

/*
 *  Поля из ЕСИА:
 *  fullname, birthdate, gender, snils, inn,
id_doc, birthplace, medical_doc, residence_doc, email, mobile, c
ontacts, usr_org, usr_avt, org_shortname, org_fullname,
org_type, org_ogrn, org_inn, org_leg, org_kpp, org_ctts,
org_addrs
 *
 * Параметры из AD
 *
-msExchMailboxSecurityDescriptor
-msExchMailboxTemplateLink
-msExchPoliciesExcluded
-msExchRBACPolicyLink
-msExchRecipientDisplayType
-msExchRecipientTypeDetails
-msExchSafeSendersHash
-msExchTextMessagingState
-msExchUMDtmfMap
-msExchUserAccountControl
-msExchUserCulture
-msExchVersion
-msExchWhenMailboxCreated
-msNPAllowDialin
-msRTCSIP-DeploymentLocator
-msRTCSIP-FederationEnabled
-msRTCSIP-InternetAccessEnabled
-msRTCSIP-Line
-msRTCSIP-OptionFlags
-msRTCSIP-PrimaryHomeServer
-msRTCSIP-PrimaryUserAddress
-msRTCSIP-UserEnabled
-msRTCSIP-UserPolicies
-msRTCSIP-UserRoutingGroupId
Name
-nTSecurityDescriptor
-ObjectCategory
-ObjectClass
ObjectGUID
objectSid
physicalDeliveryOfficeName
primaryGroupID
-ProtectedFromAccidentalDeletion
-proxyAddresses
-pwdLastSet
sAMAccountName
sAMAccountType
-sDRightsEffective
-showInAddressBook
sn
streetAddress
telephoneNumber
title
-userAccountControl
-userCertificate
-userParameters
userPrincipalName
thumbnailPhoto
-uSNChanged
-uSNCreated
whenChanged
whenCreated
wWWHomePage
 *
 */

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('auth_ad_user', [
            'id_ad_user' => $this->primaryKey(),
            'id_user' => $this->integer(),

            'name' => $this->string(),
            'guid' => $this->string(),
            'sid' => $this->string(),
            'office_name' => $this->string(),
            'group_id' => $this->string(),
            'sam_acc_name' => $this->string(),
            'sam_acc_type' => $this->string(),
            'sn' => $this->string(),
            'street_address' => $this->string(),
            'telephone_number' => $this->string(),
            'title' => $this->string(),
            'principal' => $this->string(),
            'thumbnail' => $this->text(),
            'homepage' => $this->string(),
            'when_created' => $this->string(),
            'when_changed' => $this->string(),

            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);

        $this->createTable('auth_esia_user', [
            'id_esia_user' => $this->primaryKey(),
            'id_user' => $this->integer(),
            'is_org' => $this->integer()->defaultValue(0),

            'fullname' => $this->string(),
            'birthdate' => $this->string(),
            'gender' => $this->string(),
            'snils' => $this->string(),
            'inn' => $this->string(),
            'id_doc' => $this->string(),
            'birthplace' => $this->string(),
            'medical_doc' => $this->string(),
            'residence_doc' => $this->string(),
            'email' => $this->string(),
            'mobile' => $this->string(),
            'contacts' => $this->string(),
            'usr_org' => $this->string(),
            'usr_avt' => $this->string(),
            'org_shortname' => $this->string(),
            'org_fullname' => $this->string(),
            'org_type' => $this->string(),
            'org_ogrn' => $this->string(),
            'org_inn' => $this->string(),
            'org_leg' => $this->string(),
            'org_kpp' => $this->string(),
            'org_ctts' => $this->string(),
            'org_addrs' => $this->string(),

            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);


        $this->addColumn('user', 'id_esia_user', $this->integer());
        $this->addColumn('user', 'id_ad_user', $this->integer());
        $this->addColumn('user', 'fullname', $this->string());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('auth_ad_user');
        $this->dropTable('auth_esia_user');

        $this->dropColumn('user', 'id_esia_user');
        $this->dropColumn('user', 'id_ad_user');
        $this->dropColumn('user', 'fullname');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190915180445UserAdEsiaMeta cannot be reverted.\n";

        return false;
    }
    */
}
