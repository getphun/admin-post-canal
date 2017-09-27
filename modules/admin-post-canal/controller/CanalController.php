<?php
/**
 * Canal controller
 * @package admin-post-canal
 * @version 0.0.1
 * @upgrade true
 */

namespace AdminPostCanal\Controller;
use PostCanal\Model\PostCanal as PCanal;

class CanalController extends \AdminController
{
    private function _defaultParams(){
        return [
            'title'             => 'Post Canal',
            'nav_title'         => 'Post',
            'active_menu'       => 'post',
            'active_submenu'    => 'post-canal',
            'total'             => 0,
            'pagination'        => []
        ];
    }
    
    public function editAction(){
        if(!$this->user->login)
            return $this->show404();
        
        $id = $this->param->id;
        if(!$id && !$this->can_i->create_post_canal)
            return $this->show404();
        elseif($id && !$this->can_i->update_post_canal)
            return $this->show404();
        
        $old = null;
        $params = $this->_defaultParams();
        $params['title'] = 'Create New Post Canal';
        $params['ref'] = $this->req->getQuery('ref') ?? $this->router->to('adminPostCanal');
        
        if($id){
            $params['title'] = 'Edit Post Canal';
            $object = PCanal::get($id, false);
            if(!$object)
                return $this->show404();
            $old = clone $object;
        }else{
            $object = new \stdClass();
            $object->user = $this->user->id;
        }
        
        if(false === ($form=$this->form->validate('admin-post-canal', $object)))
            return $this->respond('post/canal/edit', $params);
        
        $object = object_replace($object, $form);
        
        $event = 'updated';
        
        if(!$id){
            $event = 'created';
            if(false === ($id = PCanal::create($object)))
                throw new \Exception(PCanal::lastError());
        }else{
            $object->updated = null;
            if(false === PCanal::set($object, $id))
                throw new \Exception(PCanal::lastError());
            
            // save slug changes
            if(isset($object->slug) && $object->slug != $old->slug && module_exists('slug-history'))
                $this->slug->create('post-canal', $id, $old->slug, $object->slug);
        }
        
        $this->fire('post-canal:'. $event, $object, $old);
        
        return $this->redirect($params['ref']);
    }
    
    public function filterAction(){
        if(!$this->user->login)
            return $this->show404();
        if(!$this->can_i->read_post_canal)
            return $this->show404();
        
        $q = $this->req->getQuery('q');
        if(!$q)
            return $this->ajax(['error'=>true, 'data'=>[]]);
        
        $canals = PCanal::get(['q'=>$q], 10, false, 'LENGTH(name) ASC');
        if(!$canals)
            return $this->ajax(['error'=>false, 'data'=>[]]);
        
        $result = array_column($canals, 'name', 'id');
        $this->ajax(['error'=>false, 'data'=>$result]);
    }
    
    public function indexAction(){
        if(!$this->user->login)
            return $this->loginFirst('adminLogin');
        if(!$this->can_i->read_post_canal)
            return $this->show404();
        
        $params = $this->_defaultParams();
        $params['reff']  = $this->req->url;
        $params['canals'] = [];
        
        $page = $this->req->getQuery('page', 1);
        $rpp  = 20;
        $cond = [];
        if($this->req->getQuery('q'))
            $cond['q'] = $this->req->getQuery('q');
        
        $canals = PCanal::get($cond, $rpp, $page, 'name ASC');
        if($canals)
            $params['canals'] = \Formatter::formatMany('post-canal', $canals, false, false);
        
        $params['total'] = $total = PCanal::count($cond);
        
        if($total > $rpp)
            $params['pagination'] = \calculate_pagination($page, $rpp, $total, 10, $cond);
        
        $this->form->setForm('admin-post-canal-index');
        
        return $this->respond('post/canal/index', $params);
    }
    
    public function removeAction(){
        if(!$this->user->login)
            return $this->show404();
        if(!$this->can_i->remove_post_canal)
            return $this->show404();
        
        $id = $this->param->id;
        $object = PCanal::get($id, false);
        if(!$object)
            return $this->show404();
        
        $this->fire('post-canal:deleted', $object);
        PCanal::remove($id);
        
        $ref = $this->req->getQuery('ref');
        if($ref)
            return $this->redirect($ref);
        
        return $this->redirectUrl('adminPostCanal');
    }
}