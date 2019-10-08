<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Auth;
use App\Role;
use App\FunnyjunkUser;
use Posttwo\FunnyJunk\FunnyJunk;
use Posttwo\FunnyJunk\User;
use Cache;

class FJUserController extends \App\Http\Controllers\Controller
{
    public function getBasicUserByUsername($username)
    {
	    if(Auth::user()->cannot('mod.isAMod'))
                abort(403);
        $response = Cache::remember('fjapi.getBasicUserByUsername.' . $username, 60, function() use($username){
            $user = new User();
            $user->set(array('username' => $username));
            $user->populate();

            $response['username'] = $user->username;
            $response['group_name'] = $user->group_name;
            $response['max_level'] = $user->level;
            $response['content_level'] = (int)filter_var($user->rank_info->currentContentLabel, FILTER_SANITIZE_NUMBER_INT);
            $response['comment_level'] = (int)filter_var($user->rank_info->currentCommentLabel, FILTER_SANITIZE_NUMBER_INT);
			logger(Auth::user()->nickname . " requested basic user info for " . $username);
            return $response;
        });
        
        return $response;
    }

    public function getModUserByUsername($username)
    {
	    if(Auth::user()->cannot('mod.isAMod'))
		abort(403);
        $response = Cache::remember('fjapi.getModUserByUsername.' . $username, 10, function() use($username){
            $user = new User();
            $user->set(array('username' => $username));
            $user->populate();

            $response['username'] = $user->username;
            $response['userId'] = $user->userId;
            $response['joined'] = $user->joined;
            $response['last_online'] = $user->last_online;
            $response['contributor_account'] = $user->contributor_account;
            $response['role_description'] = $user->role_description;
            $response['has_oc_item'] = $user->has_oc_item;
            $response['ban_history'] = $user->ban_history;
			logger(Auth::user()->nickname . " requested mod user info for " . $username);
            return $response;
        });
        
        return $response;
    }

    public function getUserFJMemeInfoByFJUsername($username){
        $users = FunnyjunkUser::where('username', $username)->get();
        $return = [];
        foreach($users as $user){
            $user = $user->user;
            $r = $this->getUserFJMemeInfo($user);
            $return[] = $r;
            logger(Auth::user()->nickname . " requested FJMeme info for " . $username);
        }
        return $return;
    }

    public function getUserFJMemeInfoByID($id)
    {
        $user = \App\User::findOrFail($id);
        return $this->getUserFJMemeInfo($user);
    }

    protected function getUserFJMemeInfo($user)
    {
        $avatar = $user->avatar;
        if($avatar != null){
            try{
                if(get_headers($avatar, 1)[0] == 'HTTP/1.1 404 Not Found')
                    $avatar = 'https://new2.fjcdn.com/site/funnyjunk/images/def_avatar.gif';
            }catch(Exception $e){
                $avatar = 'https://new2.fjcdn.com/site/funnyjunk/images/def_avatar.gif';
                logger()->error("Error in get_headers", ["user" => $user]);
            }
        } else {
            $avatar = 'https://new2.fjcdn.com/site/funnyjunk/images/def_avatar.gif';
        }
        $r['user'] = $user;
        $r['user']['avatar'] = $avatar;
        $r['user']['email'] = $user->fjuser->username . '@users.fjme.me';
        $r['user']['fjuser'] = $user->fjuser;
        $r['user']['roles'] = $user->permissions;
        logger(Auth::user()->nickname . " requested FJMeme info for " . $user->fjuser->username);
        return $r;
    }

    public function revokeModeratorPermissionByFJUsername($username){
        $user = FunnyjunkUser::where('username', $username)->firstOrFail()->user;
        return $this->revokeModeratorPermission($user);
    }

    public function revokeModeratorPermissionByID($id)
    {
        $user = \App\User::findOrFail($id);
        return $this->revokeModeratorPermission($user);
    }

    protected function revokeModeratorPermission($user)
    {
        logger(Auth::user()->nickname . " revoked moderator permissions for " . $user->fjuser->username);
        $return = [];
        $returnText = "Starting Revoke for" . $user->fjuser->username;
        $return['user'] = $user;
        $user->revokePermissionTo('mod.isAMod');
        $user->revokePermissionTo('mod.isExec');
        $user->revokePermissionTo('mod.complaintsResponder');
        $user->revokePermissionTo('mod.ratingReviewer');
        $returnText .="Revoked mod.isAMox and mod.isExec ";
        //Revoke Notes Token
        try{
            $returnText .="Trying to revoke Notes Token ";
            $options = array(
                'http' => array(
                  'ignore_errors' => true,
                  'header' => "Content-Type: application/json\r\n"
                  )
              );
            $context  = stream_context_create($options);
            $t = file_get_contents('http://fjmod.posttwo.pt/token/no' . env("NOTE_API") . "?mod=" . $user->fjuser->username, false, $context);
            $return['notestoken'] = $t;
        }catch(Exception $e){
            $returnText .="Failed ";
            logger()->error($e);
        }
        //Inform Jettom
        
        try{
            $returnText .="Posting on Discord\n";
            $slack = new \App\Slack;
            $slack->target = 'mod-social';
            $slack->username =   "Clown Fiesta";
            $slack->text     =   null;
            $slack->avatar   =   'https://i.imgur.com/Yllnij1.png';
            $slack->title    = '';
            $slack->text     = ':warning: <@' . $user->discord_id . '> User Demodded, please remove <@198325775370420224>';
            $slack->embedFields = ['FJUser' => $user->fjuser->username, 'Discord' =>  $user->nickname];
            \Notification::send($slack, new \App\Notifications\ModNotify(null));
        } catch (Exception $e){
            $returnText .="Failed\n";
            logger()->error($e);
        }

        //Get Moodle User
        /*$returnText .="Building moodle user ";
        $results = $this->getMoodleUser($user->fjuser->username);
        $return['moodleuser'] = $results;
        //Suspend Mod Moodle Account
        $returnText .="Suspending moodle user ";
        $this->suspendMoodleUser($results);
        $return['debug'] = $returnText;*/
        return $return;
    }

    public function giveUserAccessToOAuthByFJUsername($username){
		logger(Auth::user()->nickname . " Granted OAuth Access " . $username);
        $user = FunnyjunkUser::where('username', $username)->firstOrFail()->user;
        $user->givePermissionTo('user.canUseFJMemeForSingleSignOn');
        return ["success" => true];
    }

    public function giveUserAccessToOAuthByID($id){
        $user = \App\User::findOrFail($id);
        $username = $user->fjuser->username;
        logger(Auth::user()->nickname . " Granted OAuth Access " . $username);
        $user->givePermissionTo('user.canUseFJMemeForSingleSignOn');
        return ["success" => true];
    }

    public function giveUserAccessToReviewByID($id){
        $user = \App\User::findOrFail($id);
        $username = $user->fjuser->username;
        logger(Auth::user()->nickname . " Granted Rating Review Access " . $username);
        $user->givePermissionTo('mod.ratingReviewer');
        return ["success" => true];
    }

    public function revokeUserAccessToOAuthByFJUsername($username){
		logger(Auth::user()->nickname . " Revoked OAuth Access " . $username);
        $user = FunnyjunkUser::where('username', $username)->firstOrFail()->user;
        $user->revokePermissionTo('user.canUseFJMemeForSingleSignOn');

        //$results = $this->getMoodleUser($username);
        //$this->suspendMoodleUser($results);
        return ["success" => true];
    }

    public function revokeUserAccessToOAuthByID($id){
        $user = \App\User::findOrFail($id);
        $username = $user->fjuser->username;
        logger(Auth::user()->nickname . " Revoked OAuth Access " . $username);
        $user->revokePermissionTo('user.canUseFJMemeForSingleSignOn');
        //$results = $this->getMoodleUser($username);
        //$this->suspendMoodleUser($results);
        return ["success" => true];
    }
    
    public function nukeFJMemeUserByFJUsername($username){
        logger(Auth::user()->nickname . " Nuked FJMeme of " . $username);
        $user = FunnyjunkUser::where('username', $username)->firstOrFail()->user;
        $user->fjuser()->delete();
        $user->syncPermissions([]);
        return ["success" => true];
    }

    public function nukeFJMemeUserByID($id){
        $user = \App\User::findOrFail($id);
        $username = $user->fjuser->username;
        logger(Auth::user()->nickname . " Nuked FJMeme of " . $username);
        $user->fjuser()->delete();
        $user->syncPermissions([]);
        return ["success" => true];
    }



    protected function getMoodleUser($username){
        $postdata = http_build_query(
			array('criteria[0][key]' => 'lastname',
			      'criteria[0][value]' => $username)
		);
		$opts = array('http' =>
    			array(
        		 'method'  => 'POST',
        		 'header'  => 'Content-type: application/x-www-form-urlencoded',
        		 'content' => $postdata
    			)
        );
        $context = stream_context_create($opts);
		$results = file_get_contents('https://edu.fjme.me/webservice/rest/server.php?wstoken='.env("MOODLE_TOKEN").'&wsfunction=core_user_get_users&moodlewsrestformat=json', false, $context);
        $results = json_decode($results, true);
        return $results;
    }

    protected function suspendMoodleUser($results){
        $postdata = http_build_query(
            array('users[0][id]' => $results['users'][0]['id'], 'users[0][suspended]' => 1)
        );
        $opts = array('http' =>
                    array(
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postdata
                        )
            );

        $context = stream_context_create($opts);
        $results = file_get_contents('https://edu.fjme.me/webservice/rest/server.php?wstoken='.env("MOODLE_TOKEN").'&wsfunction=core_user_update_users&moodlewsrestformat=json', false, $context);
        
    }
}
