<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\User;
use common\models\AdUser;

class AdImportController extends Controller
{
    private function mydap_start($username,$password,$host,$port=389) {
        global $mydap;
        if(isset($mydap)) die('Error, LDAP connection already established');

        // Connect to AD
        $mydap = ldap_connect($host,$port) or die('Error connecting to LDAP');

        ldap_set_option($mydap,LDAP_OPT_PROTOCOL_VERSION,3);
        @ldap_bind($mydap,$username,$password) or die('Error binding to LDAP: '.ldap_error($mydap));

        return true;
    }

    private function mydap_end() {
        global $mydap;
        if(!isset($mydap)) die('Error, no LDAP connection established');

        // Close existing LDAP connection
        ldap_unbind($mydap);
    }

    private function mydap_attributes($user_dn,$keep=false) {
        global $mydap;
        if(!isset($mydap)) die('Error, no LDAP connection established');
        if(empty($user_dn)) die('Error, no LDAP user specified');

        // Disable pagination setting, not needed for individual attribute queries
        ldap_control_paged_result($mydap,1);

        // Query user attributes
        $results = (($keep) ? ldap_search($mydap,$user_dn,'cn=*',$keep) : ldap_search($mydap,$user_dn,'cn=*'))
        or die('Error searching LDAP: '.ldap_error($mydap));

        $attributes = ldap_get_entries($mydap,$results);

        // Return attributes list
        if(isset($attributes[0])) return $attributes[0];
        else return array();
    }

    private function mydap_members($object_dn,$object_class='g') {
        global $mydap;
        if(!isset($mydap)) die('Error, no LDAP connection established');
        if(empty($object_dn)) die('Error, no LDAP object specified');

        // Determine class of object we are dealing with

        // Groups, use range to overcome LDAP attribute limit
        if($object_class == 'g') {
            $output = array();
            $range_size = 1500;
            $range_start = 0;
            $range_end = $range_size - 1;
            $range_stop = false;

            do {
                // Query Group members
                $results = ldap_search($mydap,$object_dn,'cn=*',array("member;range=$range_start-$range_end")) or die('Error searching LDAP: '.ldap_error($mydap));
                $members = ldap_get_entries($mydap,$results);

                $member_base = false;

                // Determine array key of the member results

                // If array key matches the format of range=$range_start-* we are at the end of the results
                if(isset($members[0]["member;range=$range_start-*"])) {
                    // Set flag to break the do loop
                    $range_stop = true;
                    // Establish the key of this last segment
                    $member_base = $members[0]["member;range=$range_start-*"];

                    // Otherwise establish the key of this next segment
                } elseif(isset($members[0]["member;range=$range_start-$range_end"]))
                    $member_base = $members[0]["member;range=$range_start-$range_end"];

                if($member_base && isset($member_base['count']) && $member_base['count'] != 0) {
                    // Remove 'count' element from array
                    array_shift($member_base);

                    // Append this segment of members to output
                    $output = array_merge($output,$member_base);
                } else $range_stop = true;

                if(!$range_stop) {
                    // Advance range
                    $range_start = $range_end + 1;
                    $range_end = $range_end + $range_size;
                }
            } while($range_stop == false);

            // Containers and Organizational Units, use pagination to overcome SizeLimit
        } elseif($object_class == 'c' || $object_class == "o") {

            $pagesize = 1000;
            $counter = "";
            do {
                ldap_control_paged_result($mydap,$pagesize,true,$counter);

                // Query Container or Organizational Unit members
                $results = ldap_search($mydap,$object_dn,'objectClass=user',array('sn')) or die('Error searching LDAP: '.ldap_error($mydap));
                $members = ldap_get_entries($mydap, $results);

                // Remove 'count' element from array
                array_shift($members);

                // Pull the 'dn' from each result, append to output
                foreach($members as $e) $output[] = $e['dn'];

                ldap_control_paged_result_response($mydap,$results,$counter);
            } while($counter !== null && $counter != "");

            // Invalid object_class specified
        } else die("Invalid mydap_member object_class, must be c, g, or o");

        // Return alphabetized member list
        sort($output);
        return $output;
    }

    public function actionIndex()
    {

        $this->mydap_start(
            'web_user@admkrsk.ru', // Active Directory search user
            'PaO5q#3ows', // Active Directory search user password
            '10.24.0.7' // Active Directory server
        );

        //$members1 = $this->mydap_members('OU=_DA,DC=admkrsk,DC=ru','c');
        $members2 = $this->mydap_members('OU=_Органы и территориальные подразделения,DC=admkrsk,DC=ru','c');

        $members = $members2;
        //$members = array_merge($members1, $members2);

        if(!$members) die('No members found, make sure you are specifying the correct object_class');
        $keep = array('samaccountname','email','employeeID', 'name', 'company', 'department', 'description', 'title',  'givenname', 'mobilephone', 'othertelephone', 'city', 'phone', 'displayname', 'objectsid', 'office', 'fax');

        $i = 1; // For counting our output
        foreach($members as $m) {

            $attr = $this->mydap_attributes($m,$keep);
//var_dump($attr); die();
            $company = isset($attr['company'][0]) ? $attr['company'][0] : "[no company]";

            if($company == "[no company]")
            {
                echo "[no company] - SKIPPED\n";
                continue;
            }

            if(!isset($attr['email'][0]))
            {
                echo "[no email] - SKIPPED\n";
                continue;
            }

            $alreadyUser = AdUser::find()->where(['email' => $attr['email'][0]])->one();

            if($alreadyUser)
            {
                echo $alreadyUser->name ." [already there]  - SKIPPED\n";
                continue;
            }

            $aduser = new AdUser();
            $aduser->sn = isset($attr['samaccountname'][0]) ? $attr['samaccountname'][0] : null;
            $aduser->name = isset($attr['name'][0]) ? $attr['name'][0] : null;
            $aduser->city = isset($attr['city'][0]) ? $attr['city'][0] : null;
            $aduser->company = isset($attr['company'][0]) ? $attr['company'][0] : null;
            $aduser->department = isset($attr['department'][0]) ? $attr['department'][0] : null;
            $aduser->description = isset($attr['description'][0]) ? $attr['description'][0] : null;
            $aduser->title = isset($attr['title'][0]) ? $attr['title'][0] : null;
            $aduser->displayname = isset($attr['displayname'][0]) ? $attr['displayname'][0] : null;
            $aduser->email = isset($attr['email'][0]) ? $attr['email'][0] : null;
            $aduser->fax = isset($attr['fax'][0]) ? $attr['fax'][0] : null;
            $aduser->office = isset($attr['office'][0]) ? $attr['office'][0] : null;
            $aduser->phone = isset($attr['phone'][0]) ? $attr['phone'][0] : null;
            $aduser->sid = implode(unpack('C*', $attr['objectsid'][0]));

            $checkaduser = AdUser::find()->where("sn = '{$aduser->sn}'")->count();

            if($checkaduser)
            {
                echo $i . ". " . $aduser->displayname . " - SKIPPED\n";
                continue;
            }

            $aduser->save();

            $user = new User();
            $user->email = ($aduser->sn).'@admkrsk.ru';
            $user->username = $aduser->sn;
            $user->setPassword($aduser->sid);
            $user->generateAuthKey();
            $user->status = User::STATUS_ACTIVE;
            $user->id_ad_user = $aduser->id_ad_user;

            echo $i . ". " . $aduser->displayname;
            if($user->save())
                echo " - SAVED\n";
            else
                echo " - FAILED\n";

            $i++;
        }

        $this->mydap_end();

    }
}