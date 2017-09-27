<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\HRPerson;
use App\hr_people;
use App\autoRensponder;
use App\HelpDesk;
use App\system_email_setup;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;

class HelpdeskSystemEmailSetupCronController extends Controller
{
    public function execute(){

    		$systems = operator::orderBy('id', 'asc')->get();

    		$autoRensponder = DB::table('auto_rensponder')->orderBy('id', 'asc')->get()->first();
   			$employees = HRPerson::where('status', 1)->get();

		    // $rHelpdesks = DB::table('system_email_setup')
		    // 			->select('system_email_setup.*')
		    // 			->where('auto_processemails', 1)
		    // 			->orderBy('id', 'asc')
		    // 			->get()
		    // 			->first();

          $rHelpdesks = system_email_setup::where('auto_processemails', 1)->get()->first();
   			

   			//$rHelpdesks = Database::query("select id,email,server,username,password,auto_responder,system_type,signature from helpdesk_helpdesks where auto_process = 1 ");

   			// 

  while ($aHelpdesk = $rHelpdesks
{
               $hostname = $aHelpdesk['server_name'];
               $username = $aHelpdesk['username'];
               $password = $aHelpdesk['password'];
               $iHelpdeskInboxID = $aHelpdesk['helpdesk_id'];
               
               if (strlen($hostname) > 32)
               {
                              $inbox = @imap_open($hostname,$username,$password,OP_SILENT);
                              if (!$inbox) continue;
                              $iAmount = imap_num_msg($inbox);

                              for ($msgid=$iAmount; $msgid>0; $msgid--)
                              {
                                    //---- Begin: Fetch Subject, From address & body from email
                                             $result = imap_fetch_overview($inbox,$msgid);

                                             if (!isset($result[0])) 
                                             {
                                                            print "$aHelpdesk[username]: $msgid <br>";
                                                            continue;
                                             }
                                             $overview = $result[0];
                                             if (stristr($overview->from,'<'))
                                             {
                                                            $sFrom = substr(stristr($overview->from,'<'),1);
                                                            $sFrom = substr($sFrom,0,strpos($sFrom,'>'));
                                             }
                                             else $sFrom = $overview->from;
                                             $sFrom = str_replace("'",'',$sFrom);
                                             $sFrom = str_replace("\"",'',$sFrom);
                                             $email = $sFrom;   

                                             //this part
                                             $aEmailDomain = array();
                                             $aEmailDomain = explode("@",$sFrom);

                                             //print "$iHelpdeskInboxID >> ";
                                           //  $rDomains = database::query("select helpdesk_id from helpdesk_domains where lower(domain) = lower('$aEmailDomain[1]')");

                                             
										$rDomainss = system_email_setup::select('helpdesk_id')->where(lower('email_address'), $aEmailDomain[1])->get()->first();
										                    #convert to lower case 
										           $rDomains =  strtolower($rDomainss);

										           #compare with array
										          // $rDomains = $aEmailDomain[1];

                                             // if ($aDomain = $rDomains->fetchrow()) $iHelpdeskID = $aDomain['helpdesk_id'];
                                             // else $iHelpdeskID = $iHelpdeskInboxID;
                                             
                                             //print "HelpdeskID for $sFrom set to $iHelpdeskID<br>";
                                             // $aHelpdesk = database::query("select * from helpdesk_helpdesks where id=$iHelpdeskID ")->fetchrow();
                                             	 $aHelpdesk = HelpDesk_setup::where('helpdesk_id' , $rDomainss)->get()->first();

                                             // $sTicketTask = "Ticket";
                                             // if (!empty($aHelpdesk['ticket_name'])) $sTicketTask = $aHelpdesk['ticket_name'];
                                             

                                             // $aDayStart = explode(':',$aHelpdesk['officehours_start']);
                                             // if (!isset($aDayStart[1])) $iDayStart = 28800; # 08:00
                                             // else $iDayStart = ($aDayStart[0] + ($aDayStart[1]/60)) * 3600;
                                             // $aDayEnd = explode(':',$aHelpdesk['officehours_end']);
                                             // if (!isset($aDayEnd[1])) $iDayEnd = 61200; # 17:00
                                             // else $iDayEnd = ($aDayEnd[0] + ($aDayEnd[1]/60)) * 3600;
                                             // $iTimeLogged = strtotime(date("1970-01-01 H:i",$iToday)) + 7200; # GMT +2
                                             // if (getWorkingDays(date("Y-m-d",$iToday),date("Y-m-d",$iToday)) == 0 || $iTimeLogged < $iDayStart || $iTimeLogged > $iDayEnd) $iAfterHours = 1;
                                             // else $iAfterHours = 0;
                                                            
                                             if (isset($overview->subject)) $sSubject = $overview->subject;
                                             else $sSubject = "No Subject";
                                             if ( $sSubject == '' ) $sSubject = "No Subject";
                                             $sSubject = str_replace("'",'',$sSubject);
                                             $sSubject = str_replace("\"",'',$sSubject);
                              
                                             $struct = imap_fetchstructure($inbox,$msgid);
                                             if (isset($struct->parts[0]->parts))              
                                             {
                                                            $sBody = imap_fetchbody($inbox,$msgid,"1.1.1");
                                                            if (empty($sBody)) $sBody = imap_fetchbody($inbox,$msgid,"1.1");
                                             }
                                             else $sBody = imap_fetchbody($inbox,$msgid,1);
                                             $sBody = html_entity_decode(html_entity_decode($sBody));
                                             $sBody = quoted_printable_decode($sBody);
                                             $sBody = strip_tags($sBody);
                                             
                                             //---- Begin: Process email into helpdesk
                                             $sContact = '';
                                             $iContactID = $iHrID = 0;
                                             
                                             // $rContactsActive = Database::query("select active from security_modules where title = 'Contacts'");
                                             // if ($aContactsActive = $rContactsActive->fetchrow())
                                             //                if ($aContactsActive['active'] == TRUE)
                                             //                {
                                             //                               $rContact = Database::query("select ccon.id, ccom.name from contacts_contacts ccon left join contacts_company ccom  on (ccon.company_id = ccom.id) where lower(ccon.email) = lower('$email')");
                                             //                               if ($aContact = $rContact->fetchrow())
                                             //                               {
                                             //                                              $sContact = $aContact['name'];
                                             //                                              $iContactID = $aContact['id'];
                                             //                               }
                                             //                }
                                             $rContact = Database::query("select id, firstname || ' ' || surname as fullname from hr_person where lower(email)= lower('$email')");
                                             
                                             if ($aContact = $rContact->fetchrow())
                                             {
                                                            $sContact = $aContact['fullname'];
                                                            $iHrID = $aContact['id'];
                                             }
                                             if (empty($sContact)) $sContact = $sFrom;
                                             if (!empty($sContact))
                                             {                                            
                                                     $sContact = htmlentities($sContact,ENT_QUOTES);
                                                      $aData = array();
                                                       preg_match("/\[#[0-9]*\]/",$sSubject,$aData);
                                                            
                                                      if (!isset($_REQUEST['testing']))
                                                            {
                                                               if (empty($aData) && $aHelpdesk['only_replies'] == 0)
                                                                     {
                                                                        //-------- Begin: Extract Keywords assigned to operators into $aOperatorKeywords
                                                                         if (!empty($iHelpdeskID))
                                                                              {
                                                                               $aOperatorKeywords = array();
                                                                               $rKeywords = database::query("select user_id,keyword,notify from helpdesk_operator_keywords where helpdesk_id=$iHelpdeskID");
                                                                                       while ($aKeywords = $rKeywords->fetchrow())
                                                                                         {
                                                                                           $iUserID = $aKeywords['user_id'];
                                                                                           $aKeywords['keyword'] = str_replace(" ",'',$aKeywords['keyword']);
                                                                                           $aOperatorKeywords[strtolower($aKeywords['keyword'])] = array();
                                                                                           $aOperatorKeywords[strtolower($aKeywords['keyword'])][0] = $iUserID;
                                                                                           $aOperatorKeywords[strtolower($aKeywords['keyword'])][1] = $aKeywords['notify'];
                                                                                                         }
                                                                                          }
                                                                                          //-------- End: Extract Keywords assigned to operators into $aOperatorKeywords
                                                            
                                                                                          //-------- Begin: Extract Keywords assigned to categories into $aCategoryKeywords
                                                                                          if (!empty($iHelpdeskID))
                                                                                          {
                                                                                               $aCategoryKeywords = array();
                                                                                               $rKeywords = database::query("select id,keywords from helpdesk_request_cat where helpdesk_id=$iHelpdeskID");
                                                                                                 while ($aKeywords = $rKeywords->fetchrow())
                                                                                                     {
                                                                                                         if (!empty($aKeywords['keywords']))
                                                                                                            {
                                                                                                             $aKeywords['keywords'] = str_replace(" ",'',$aKeywords['keywords']);
                                                                                                                  $x = explode(",",$aKeywords['keywords']);
                                                                                                                     foreach ($x as $value)
                                                                                                                       $aCategoryKeywords[strtolower($value)] = $aKeywords['id'];
                                                                                                             }
                                                                                                         }
                                                                                          }
                                                                                          //-------- End: Extract Keywords assigned to categories into $aCategoryKeywords
                                                                                          
                                                                                          //-------- Begin: Extract Keywords assigned to Types into $aTypeKeywords
                                                                                      if (!empty($iHelpdeskID))
                                                                                        {
                                                                                            $aTypeKeywords = array();
                                                                                                $rKeywords = database::query("select id,keywords from helpdesk_request_type where helpdesk_id=$iHelpdeskID");
                                                                                                         while ($aKeywords = $rKeywords->fetchrow())
                                                                                                         {
                                                                                                            if (!empty($aKeywords['keywords']))
                                                                                                                 {
                                                                                                                  $aKeywords['keywords'] = str_replace(" ",'',$aKeywords['keywords']);
                                                                                                                     $x = explode(",",$aKeywords['keywords']);
                                                                                                                        foreach ($x as $value)
                                                                                                                          $aTypeKeywords[strtolower($value)] = $aKeywords['id'];
                                                                                                              }
                                                                                                      }
                                                                                          }
                                                                                          //-------- End: Extract Keywords assigned to Types into $aTypeKeywords
                                                                                          
                                                                                          $junk = array();
                                                                                          $x = array();
                                                                                          $iMaxOperator = 0;
                                                                                          $iKeyOperator[0] = 0;
                                                                                          $sKeyword = '';
                                                                                          $iHighestKeyword = 0;
                                                                                          if (!empty($aOperatorKeywords))
                                                                                          {
                                                                                                         foreach ($aOperatorKeywords as $key => $value)
                                                                                                         {
                                                                                                                        if (!isset($x[$value[0]])) $x[$value[0]] = 0;
                                                                                                                        $iMatch = preg_match_all("/.?".strtolower($key).".?/",strtolower($sSubject),$junk);
                                                                                                                        $x[$value[0]] = $x[$value[0]] + $iMatch;
                                                                                                                        if ($iMatch > $iHighestKeyword && $value[1])
                                                                                                                        {
                                                                                                                                       $iHighestKeyword = $iMatch;
                                                                                                                                       $sKeyword = $key;
                                                                                                                        }
                                                                                                         }
                                                                                                         
                                                                                                         if (max($x) == 0)
                                                                                                         foreach ($aOperatorKeywords as $key => $value)
                                                                                                         {
                                                                                                                        $iMatch = preg_match_all("/.?".strtolower($key).".?/",strtolower($sBody),$junk);
                                                                                                                        $x[$value[0]] = $x[$value[0]] + $iMatch;
                                                                                                                        if ($iMatch > $iHighestKeyword && $value[1])
                                                                                                                        {
                                                                                                                                       $iHighestKeyword = $iMatch;
                                                                                                                                       $sKeyword = $key;
                                                                                                                        }
                                                                                                         }
                                                                                                         
                                                                                                         $iMaxOperator = max($x);
                                                                                                         if ($iMaxOperator > 0) $iKeyOperator = array_keys($x,$iMaxOperator);
                                                                                          }
                                                                                          
                                                                                          $y = array();
                                                                                          $iMaxCategory = 0;
                                                                                          $iKeyCategory[0] = 0;
                                                                                          if (!empty($aCategoryKeywords))
                                                                                          {
                                                                                                         foreach ($aCategoryKeywords as $key => $value)
                                                                                                         {
                                                                                                                        if (!isset($y[$value])) $y[$value] = 0;
                                                                                                                        $y[$value] = $y[$value] + preg_match_all("/.?".strtolower($key).".?/",strtolower($sSubject),$junk);
                                                                                                         }
                                                                                                         if (max($y) == 0)
                                                                                                         foreach ($aCategoryKeywords as $key => $value)
                                                                                                                        $y[$value] = $y[$value] + preg_match_all("/.?".strtolower($key).".?/",strtolower($sBody),$junk);
                                                                                                         
                                                                                                         $iMaxCategory = max($y);
                                                                                                         if ($iMaxCategory > 0) $iKeyCategory = array_keys($y,$iMaxCategory);
                                                                                          }
                                                                                          
                                                                                          $z = array();
                                                                                          $iMaxType = 0;
                                                                                          $iKeyType[0] = 0;
                                                                                          if (!empty($aTypeKeywords))
                                                                                          {
                                                                                                         foreach ($aTypeKeywords as $key => $value)
                                                                                                         {
                                                                                                                        if (!isset($z[$value])) $z[$value] = 0;
                                                                                                                        $z[$value] = $z[$value] + preg_match_all("/.?".strtolower($key).".?/",strtolower($sSubject),$junk);
                                                                                                         }
                                                                                                         if (max($z) == 0)
                                                                                                         foreach ($aTypeKeywords as $key => $value)
                                                                                                                        $z[$value] = $z[$value] + preg_match_all("/.?".strtolower($key).".?/",strtolower($sBody),$junk);
                                                                                                         
                                                                                                         $iMaxType = max($z);
                                                                                                         if ($iMaxType > 0) $iKeyType = array_keys($z,$iMaxType);
                                                                                          }
                                                                                          //Add Ticket to Helpdesk
                                                                                          if ($aHelpdesk['system_type']) $iNotification = 0;
                                                                                          else $iNotification = 1;
                                                                                          $sql = "insert into helpdesk_process (in_date,out_date,helpdesk_id,status,classification,priority,email,contact,subject,assigned_to,category_id,type_id,notification, customer_id, hr_id) ";
                                                                                          $sql .= "values ('$iToday','$iToday',$iHelpdeskID,1,2,1,'$email','$sContact','$sSubject',$iKeyOperator[0],$iKeyCategory[0],$iKeyType[0],$iNotification,$iContactID,$iHrID)";
                                                                                          Database::query($sql);
                                                                           
                                                                                          //Add audit trail to Ticket
                                                                                          $rProcess = Database::query("select currval('helpdesk_process_id_seq'::regclass) as id");
                                                                                          $aProcess = $rProcess -> fetchrow();
                                                                                          $sBodySQL = pg_escape_string($sBody);
                                                                                          Database::query("insert into helpdesk_responses (process_id,type,contact,date_time,text) values ($aProcess[id],1,'System','$iToday','$sBodySQL')");
                                                                                          Database::query("delete from helpdesk_responses where text like '%xmlns%'");
                                                                                          $sTicket = $aProcess['id'];
                                                                                          
                                                                                          if(empty($aHelpdesk['system_type']))
                                                                                          {
                                                                                                         //Send email
                                                                                                         $sql = "select count(*) as queue from helpdesk_process where status=1 and helpdesk_id=$iHelpdeskID";
                                                                                                         $rs = Database::query($sql);
                                                                                                         $r = $rs->fetchrow();
                                                                                                         $queue = $r['queue'];
                                                                                                         $auto_responder = $aHelpdesk['auto_responder'];
                                                                                                         $auto_responder .= "\n\n ----------- Original Request ------------ \n\n" . $sBody;
                                                                                                         $auto_responder = str_replace('#TICKET',$aProcess['id'],$auto_responder);
                                                                                                         $auto_responder = str_replace('#QUEUE',$queue,$auto_responder);
                                                                                                         $auto_responder = str_replace('&#13;&#10;',"\n",$auto_responder);
                                                                                                         $auto_responder = html_entity_decode($auto_responder);
                                                                                                         $auto_responder = str_replace("&#039;","'",$auto_responder);
                                                                                                         //mail($email,'[#'.$aProcess['id'].']: '.$sSubject,$auto_responder,"From:".$aHelpdesk['email']);
                                                                                          }
                                                                                          
                                                                                          if ($sKeyword != '')
                                                                                          {
                                                                                                         $iUserID = $aOperatorKeywords[$sKeyword][0];
                                                                                                         $rUser = database::query("select email from security_users where id = $iUserID");
                                                                                                         if ($rUser->numrows() > 0)
                                                                                                         {
                                                                                                                        $aUser = $rUser->fetchrow();
                                                                                                                        $sSubject = "$sTicketTask [#$aProcess[id]] requires your attention";
                                                                                                                        $sMessage = "$sTicketTask [#$aProcess[id]] has been created and requires your attention. The $sTicketTask request follows below:\n\n".$sBody;
                                                                                                                        //mail($aUser['email'],$sSubject,$sMessage,"From:".$aHelpdesk['email']);                                                                                        
                                                                                                         }
                                                                                          }
                                                                                          
                                                                                          $rUserKeywords = database::query("select hun.keyword, hun.user_id, su.firstname, su.email from helpdesk_user_notifications hun left join security_users su on (su.id = hun.user_id) where helpdesk_id = $iHelpdeskID");
                                                                                          while ($aUserKeyword = $rUserKeywords->fetchrow())
                                                                                          {
                                                                                                         $sKeyword = $aUserKeyword['keyword'];
                                                                                                         if(stristr($sBody,$sKeyword) || stristr($sSubject,$sKeyword))
                                                                                                         {
                                                                                                                        $sSubject = "$sTicketTask [#$aProcess[id]] contains keyword $sKeyword";
                                                                                                                        $sMessage = <<<HTML
Hi $aUserKeyword[firstname],

$sTicketTask [#$aProcess[id]] has been created and contains the keyword $sKeyword. According to setup, you should be notified when a $sTicketTask contains this keyword. The $sTicketTask request follows below:

$sBody 			
   	
   }

}
