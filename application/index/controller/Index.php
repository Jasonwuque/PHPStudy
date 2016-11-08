<?php

namespace app\index\controller;

use think\Controller;
use think\Image;
use think\Db;
use app\index\model\User;
use app\index\model\Section;
use app\index\model\Notify;
use app\index\model\Head;
use app\index\model\Author;
use app\index\model\Resource;
use app\index\model\Teacher;
use app\index\model\Words;
use think\Request;
use think\Session;

class Index extends Controller {

    public function index() {

        $data = Section::all();
        Session::set('section', $data);
        $this->assign('data', Session::get('section'));

        $content = Notify::where('delete_time=0')->select();
        $this->assign('content', $content);
        return $this->fetch('index');
    }

    public function first($nid,$page=1,$pagesize=5) {
        if (session('username')) {
            $data = Notify::where('nid', $nid)->where('delete_time = 0')->select();

            $this->assign('haha', $data);

            $this->assign('data', Session::get('section'));
            
//            $list = Words::order('create_time desc')->paginate(5);
//            $page = $list->render();
//            $this->assign('list', $list);
//            $this->assign('page', $page);
            $db = model('Words');
            $recordnum = $db->where('delete_time=0')->count();
            $pagenum = $recordnum / $pagesize;
    
            //如果不能整除，则自动加1页
            if($pagenum  !== (int)$pagenum){
                $pagenum = (int) $pagenum+1;
            }
           
             $data = $db->page($page,$pagesize)->where('delete_time = 0')->order('create_time desc')->select();
             
            $this->data = $data;
            //$this->pagenum = $pagenum;
            $this->page = $page;
            $this->pagesize = $pagesize;

            $this->assign('pagenum',$pagenum);
            $this->assign('pagesize', $pagesize);
            $this->assign('list', $data);
            $this->assign('page', $page);
 
            return $this->fetch();

        } else {

            $this->redirect('/login');
        }
    }
  
    public function showFisrtComment($page, $pagesize)
    {
        //dump($page);echo '<hr />';echo $pagesize;die;
        $db = model('Words');
        $recordnum = $db->where('delete_time=0')->count();
        $pagenum = $recordnum / $pagesize;

        //如果不能整除，则自动加1页
        if($pagenum  !== (int)$pagenum){
            $pagenum = (int) $pagenum+1;
        }
        
        $data = $db->page($page,$pagesize)->where('delete_time = 0')->order('create_time desc')->select();
        $this->data = $data;
            //$this->pagenum = $pagenum;
            $this->page = $page;
            $this->pagesize = $pagesize;

            $this->assign('pagenum',$pagenum);
            $this->assign('pagesize', $pagesize);
            $this->assign('data', $data);
            $this->assign('page', $page);
 
        return $this->fetch('fc');
    }
    

    public function show($sid) {
        if (session('username')) {
            $data = Head::where('sid', $sid)->where('delete_time = 0')->select();
            $this->assign('data_head', $data);

            $data_author = Author::where('delete_time = 0')->select();
            $this->assign('data_author', $data_author);


            $data_resource = Resource::where('delete_time = 0')->select();
            $this->assign('data_resource', $data_resource);


            $data_teacher = Db::name('teacher')->where('delete_time=0')->where('sid' , $sid)->paginate(2);
            $page = $data_teacher->render();
            $this->assign('data_teacher', $data_teacher);
            $this->assign('page', $page);

            $this->assign('data', Session::get('section'));

            return $this->fetch();
        } else {

            $this->redirect('/login');
        }
    }

    public function soft() {
        if (session('username')) {
            $data_resource = Resource::where('delete_time = 0')->select();
            $this->assign('data_resource', $data_resource);

            $this->assign('data', Session::get('section'));
            return $this->fetch();
        } else {

            $this->redirect('/login');
        }
    }

}
