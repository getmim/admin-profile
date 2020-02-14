<?php
/**
 * ProfileController
 * @package admin-profile
 * @version 0.0.1
 */

namespace AdminProfile\Controller;

use LibFormatter\Library\Formatter;
use LibForm\Library\Form;
use LibForm\Library\Combiner;
use LibPagination\Library\Paginator;
use Profile\Model\Profile;

class ProfileController extends \Admin\Controller
{
    private function getParams(string $title): array{
        return [
            '_meta' => [
                'title' => $title,
                'menus' => ['profile']
            ],
            'subtitle' => $title,
            'pages' => null
        ];
    }

    public function accountAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->manage_profile_account)
            return $this->show404();

        $profile = (object)[];

        $id = $this->req->param->id;
        $profile = Profile::getOne(['id'=>$id]);
        if(!$profile)
            return $this->show404();
        $params = $this->getParams('Edit Profile Account');
        $params['saved'] = false;

        $form_name = module_exists('profile-auth')
            ? 'admin.profile.account-password'
            : 'admin.profile.account';

        $form              = new Form($form_name);
        $params['form']    = $form;
        $params['profile'] = $profile;
        
        if(!($valid = $form->validate($profile)) || !$form->csrfTest('noob'))
            return $this->resp('profile/account', $params);

        if(isset($valid->password)){
            if($valid->password){
                $valid->password = password_hash($valid->password, PASSWORD_DEFAULT);
            }else{
                unset($valid->password);
            }
        }

        if(!Profile::set((array)$valid, ['id'=>$id]))
            deb(Profile::lastError());

        // add the log
        $this->addLog([
            'user'   => $this->user->id,
            'object' => $id,
            'parent' => 0,
            'method' => 2,
            'type'   => 'profile',
            'original' => $profile,
            'changes'  => $valid
        ]);

        $params['saved'] = true;
        $this->resp('profile/account', $params);
    }

    public function contactAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->manage_profile)
            return $this->show404();

        $profile = (object)[];

        $id = $this->req->param->id;
        $profile = Profile::getOne(['id'=>$id]);
        if(!$profile)
            return $this->show404();
        $params = $this->getParams('Edit Profile Contact');
        $params['saved'] = false;

        $form              = new Form('admin.profile.contact');
        $params['form']    = $form;
        $params['profile'] = $profile;

        $c_opts = [
            'contact' => [null, null, 'json']
        ];

        $combiner = new Combiner($id, $c_opts, 'profile');
        $profile  = $combiner->prepare($profile);
        
        if(!($valid = $form->validate($profile)) || !$form->csrfTest('noob'))
            return $this->resp('profile/contact', $params);

        $valid = $combiner->finalize($valid);

        if(!Profile::set((array)$valid, ['id'=>$id]))
            deb(Profile::lastError());

        // add the log
        $this->addLog([
            'user'   => $this->user->id,
            'object' => $id,
            'parent' => 0,
            'method' => 2,
            'type'   => 'profile',
            'original' => $profile,
            'changes'  => $valid
        ]);

        $params['saved'] = true;
        $this->resp('profile/contact', $params);
    }

    public function createAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->manage_profile)
            return $this->show404();

        $profile = (object)[];
        $params  = $this->getParams('Create New Profile');

        $form              = new Form('admin.profile.create');
        $params['form']    = $form;

        $c_opts = [];

        $combiner = new Combiner(0, $c_opts, 'profile');
        $profile  = $combiner->prepare($profile);

        $params['opts'] = $combiner->getOptions();
        
        if(!($valid = $form->validate($profile)) || !$form->csrfTest('noob'))
            return $this->resp('profile/create', $params);
        
        $valid = $combiner->finalize($valid);

        $valid->educations = '[]';
        $valid->profession = '[]';
        $valid->contact    = '[]';
        $valid->socials    = '[]';

        $valid->user = $this->user->id;
        if(!($id = Profile::create((array)$valid)))
            deb(Profile::lastError());

        // add the log
        $this->addLog([
            'user'   => $this->user->id,
            'object' => $id,
            'parent' => 0,
            'method' => 1,
            'type'   => 'profile',
            'original' => $profile,
            'changes'  => $valid
        ]);

        $next = $this->router->to('adminProfileEditEducation', ['id'=>$id]);
        $this->res->redirect($next);
    }

    public function educationAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->manage_profile)
            return $this->show404();

        $profile = (object)[];

        $id = $this->req->param->id;
        $profile = Profile::getOne(['id'=>$id]);
        if(!$profile)
            return $this->show404();
        $params = $this->getParams('Edit Profile Education');
        $params['saved'] = false;

        $form              = new Form('admin.profile.education');
        $params['form']    = $form;
        $params['profile'] = $profile;

        $params['lform']   = new Form('admin.profile.education.local');
        
        if(!($valid = $form->validate($profile)) || !$form->csrfTest('noob'))
            return $this->resp('profile/education', $params);
        
        if(!Profile::set((array)$valid, ['id'=>$id]))
            deb(Profile::lastError());

        // add the log
        $this->addLog([
            'user'   => $this->user->id,
            'object' => $id,
            'parent' => 0,
            'method' => 2,
            'type'   => 'profile',
            'original' => $profile,
            'changes'  => $valid
        ]);

        $params['saved'] = true;
        $this->resp('profile/education', $params);
    }

    public function indexAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->manage_profile)
            return $this->show404();

        $cond = $pcond = [];
        if($q = $this->req->getQuery('q'))
            $pcond['q'] = $cond['q'] = $q;

        list($page, $rpp) = $this->req->getPager(25, 50);

        $profiles = Profile::get($cond, $rpp, $page, ['fullname'=>true]) ?? [];
        if($profiles)
            $profiles = Formatter::formatMany('profile', $profiles, ['user']);

        $params             = $this->getParams('Profiles');
        $params['profiles'] = $profiles;
        $params['form']     = new Form('admin.profile.index');

        $params['form']->validate( (object)$this->req->get() );

        // pagination
        $params['total'] = $total = Profile::count($cond);
        if($total > $rpp){
            $params['pages'] = new Paginator(
                $this->router->to('adminProfile'),
                $total,
                $page,
                $rpp,
                10,
                $pcond
            );
        }

        $this->resp('profile/index', $params);
    }

    public function profileAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->manage_profile)
            return $this->show404();

        $profile = (object)[];

        $id = $this->req->param->id;
        $profile = Profile::getOne(['id'=>$id]);
        if(!$profile)
            return $this->show404();
        $params = $this->getParams('Edit Profile Details');
        $params['saved'] = false;

        $form              = new Form('admin.profile.profile');
        $params['form']    = $form;
        $params['profile'] = $profile;
        
        if(!($valid = $form->validate($profile)) || !$form->csrfTest('noob'))
            return $this->resp('profile/profile', $params);

        if(!Profile::set((array)$valid, ['id'=>$id]))
            deb(Profile::lastError());

        // add the log
        $this->addLog([
            'user'   => $this->user->id,
            'object' => $id,
            'parent' => 0,
            'method' => 2,
            'type'   => 'profile',
            'original' => $profile,
            'changes'  => $valid
        ]);

        $params['saved'] = true;
        $this->resp('profile/profile', $params);
    }

    public function professionAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->manage_profile)
            return $this->show404();

        $profile = (object)[];

        $id = $this->req->param->id;
        $profile = Profile::getOne(['id'=>$id]);
        if(!$profile)
            return $this->show404();
        $params = $this->getParams('Edit Profile Profession');
        $params['saved'] = false;

        $form              = new Form('admin.profile.profession');
        $params['form']    = $form;
        $params['profile'] = $profile;

        $params['lform']   = new Form('admin.profile.profession.local');
        
        if(!($valid = $form->validate($profile)) || !$form->csrfTest('noob'))
            return $this->resp('profile/profession', $params);
        
        if(!Profile::set((array)$valid, ['id'=>$id]))
            deb(Profile::lastError());

        // add the log
        $this->addLog([
            'user'   => $this->user->id,
            'object' => $id,
            'parent' => 0,
            'method' => 2,
            'type'   => 'profile',
            'original' => $profile,
            'changes'  => $valid
        ]);

        $params['saved'] = true;
        $this->resp('profile/profession', $params);
    }

    public function removeAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->manage_profile)
            return $this->show404();

        $id     = $this->req->param->id;
        $profile  = Profile::getOne(['id'=>$id]);
        $next   = $this->router->to('adminProfile');
        $form   = new Form('admin.profile.index');

        if(!$form->csrfTest('noob'))
            return $this->res->redirect($next);

        // add the log
        $this->addLog([
            'user'   => $this->user->id,
            'object' => $id,
            'parent' => 0,
            'method' => 3,
            'type'   => 'profile',
            'original' => $profile,
            'changes'  => null
        ]);

        Profile::remove(['id'=>$id]);

        $this->res->redirect($next);
    }

    public function socialAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->manage_profile)
            return $this->show404();

        $profile = (object)[];

        $id = $this->req->param->id;
        $profile = Profile::getOne(['id'=>$id]);
        if(!$profile)
            return $this->show404();
        $params = $this->getParams('Edit Profile Socials');
        $params['saved'] = false;

        $form              = new Form('admin.profile.social');
        $params['form']    = $form;
        $params['profile'] = $profile;

        $params['lform']   = new Form('admin.profile.social.local');
        
        if(!($valid = $form->validate($profile)) || !$form->csrfTest('noob'))
            return $this->resp('profile/social', $params);
        
        if(!Profile::set((array)$valid, ['id'=>$id]))
            deb(Profile::lastError());

        // add the log
        $this->addLog([
            'user'   => $this->user->id,
            'object' => $id,
            'parent' => 0,
            'method' => 2,
            'type'   => 'profile',
            'original' => $profile,
            'changes'  => $valid
        ]);

        $params['saved'] = true;
        $this->resp('profile/social', $params);
    }
}