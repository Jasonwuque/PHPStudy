<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\User;
use app\admin\model\Section;
use app\admin\model\Teacher;
use app\admin\model\Author;
use app\admin\model\Resource;
use app\admin\model\Video;
use app\admin\model\Notify;
use app\admin\model\Head;
use app\admin\model\Words;
use think\Db;
use think\Session;

class Assess extends Controller
{
	//回收站管理
	
	//用户管理
	public function user(User $user)
	{
		if (!Session::get('name')) {
			
		 	return 	$this -> fetch('index/login');
		 	exit;
		}
		$list = Db::name('user') ->where("delete_time > 0") -> paginate(10);
		
		$page = $list -> render();
		
		return View('',compact('list','page'));
		
	}
	
	//恢复用户
	public function useredit($uid)
	{
		$user = new User;

		//$list = $user -> where('uid',$uid)
		
		$list = Db::name('user')
		->where('uid',$uid)
		->setField('delete_time', '0000-00-00 00:00:00');

		if ($list) {
			
			$this -> redirect('Assess/user');
			
		} else {
			
			$this -> error('恢复失败');
			
		}
	}
	//真删除用户
	public function userdelete()
	{
		$uid  = input('get.uid');
		$user = Db::name('user');

		
		$list = $user -> where("uid = $uid") -> delete();
		
		
		if ($list) {
			
			echo json_encode(1);

		} else {

			echo json_encode(2);
		}
		
		
	}
	
	//查询指定用户
	public function userSelect()
	{
		$user = Db::name('user');
		//查询指定用户
		$username = input('get.username');
		$list = $user ->where("delete_time > 0") -> where("username","like","%$username%") ->paginate(10);
		
		$page = $list -> render();
		
		if ($list) {
			
			return View('',compact('list','page'));
			
		} else {
			
			$this -> error('没有查到指定的用户');
			
		}
		
	}
	
	//大板块管理
	public function section()
	{
		//搜索出被软删除的数据
		
		$section = Db::name('section');
		
		$list = $section -> where("delete_time > 0") -> paginate(10);
		
		$page = $list -> render();
		
		if ($list) {
			
			return View('',compact('list','page'));
			
		} else {
			$this -> error('没有数据');
		}
	}
	
	//恢复被大板块删除的数据
	
	public function sectionEdite()
	{
		//$section = new Section();
		//$list = $section -> save(['delete_time' => 0],['sid' => $sid]);
		
		$sid = input('post.sid');

		$list = Db::name('section')
					-> where('sid',$sid)
					-> setField('delete_time',0);
	}
	
	//真删除大板块
	public function sectionDelete()
	{
		$sid = input('post.sid');
		Db::name('section') -> where('sid',$sid) -> delete();
	}
	
	//模糊匹配数据
	public function sectionSelect()
	{
		$section = Db::name('section');
		
		$sec = input('get.stopic');
		
		$list = $section -> where('delete_time > 0')-> where("stopic","like","%$sec%")  -> paginate(10);
		
		$page = $list -> render();
		
		if ($list) {
			
			return View('',compact('list','page'));
			
		} else {
			
			$this -> error('没有匹配到');
			
		}
		
	}
	
	//简介管理
	public function author()
	{
		$author = Db::name('author');
		
		$list = $author -> where('delete_time > 0') ->paginate(10);
		
		$page = $list -> render();
		
		if ($list) {
			
			return View('',compact('list','page'));
			
		} else {
			
			$this -> error('查询数据失败');
			
		}
		
		
		
	}
	
	//恢复简介数据
	
	public function authorEdite()
	{
		$aid = input('post.aid');

		$author = Db::name('author');
		
		$list = $author -> where('aid',$aid) -> update(['delete_time' => 0]);
		
		
	}
	
	//删除简介数据
	public function authorDelete()
	{
		$aid = input('post.aid');
		Db::name('author') -> where('aid',$aid) -> delete();
	}
	
	//资源管理
	public function resource()
	{
		$resource = Db::name('resource');
		//查找出被软删除的数据
		$list = $resource -> where('delete_time > 0') -> paginate(10);
		
		//分页
		$page = $list -> render();
		
		if ($list) {
			
			return View('',compact('list','page'));
			
		} else {
			
			$this -> error('查找资源失败');
			
		}
		
		
	}
	
	//删除资源
	public function resourceDelete()
	{
		$rid = input('post.rid');
		Db::name('resource') -> where('rid',$rid) -> delete();
	}
	//恢复资源
	public function resourceEdite($rid)
	{	
		$list = Db::name('resource')
				-> where('rid',$rid)
				-> setField('delete_time',0);

		if ($list) {
			
			$this -> redirect('Assess/resource');
			
		} else {
			
			$this -> error('数据恢复失败');
			
		}
	}
	//模糊查询资源
	public function resourceSelect()
	{
		
		$rname = input('get.rname');
		
		$resource = Db::name('resource');
		
		$list = $resource -> where('delete_time > 0') -> where('rname','like',"%$rname%") -> paginate(10); 
		
		$page = $list -> render();
		
		if ($list) {
			
			return View('',compact('list','page'));
			
		} else {
			
			$this -> error('没有匹配到所要查询的数据');
			
		}
	}

	//公告管理
	public function notice()
	{
		$notify = Db::name('notify');

		$list = $notify -> where('delete_time > 0') -> paginate(10);

		$page = $list -> render();

		if ($list) {
			
			return View('',compact('list','page'));


		} else {
			
			$this -> error('连接失败，查询数据失败');

		}
	}
	//真删除
	public function notifyDelete()
	{
		$nid = input('post.nid');


		Db::name('notify') ->where('nid',$nid) -> delete();
	}

	//恢复
	public function notifyEdit()
	{
		$nid = input('post.nid');

		$list = Db::name('notify')
				-> where('nid',$nid)
				-> setField('delete_time',0);
		

	}

	//公告的的模糊查询
	public function notifySelect()
	{
		$notice = Db::name('notify');
		$nname  = input('get.nname'); 

		$list = $notice -> where('delete_time > 0') -> where('nname','like',"%$nname%") -> paginate(10);

		$page = $list -> render();

		if ($list) {

			return View('',compact('list','page'));

		} else {

			$this -> error('没有匹配到所要查询的数据');

		}

	}

	//教师的查询
	public function teacher()
	{
		$list = Db::name('teacher') -> alias('T') -> where('T.delete_time > 0') -> join('__SECTION__ S','T.sid = S.sid') -> paginate(5);

		 $page = $list -> render();

		if ($list) {

			return View('',compact('list','page'));

		} else {

			$this -> error('查询教师失败');
		}

	}


	//教师的真删除
	public function tDelete()
	{
		$tid = input('post.tid');
		Db::name('teacher') -> where('tid',$tid) -> delete();
	}

	//老师板块的恢复
	public function tEdit()
	{
		//恢复老师板块
		

		$tid = input('post.tid');
		$list = Db::name('teacher')
				-> where('tid',$tid)
				-> setField('delete_time',0);

	}

	//教师的模糊查询
	public function teacherSelect()
	{

		$tname = input('get.tname');

		$list = Db::name('teacher') 
				-> alias('T')
				-> where('T.delete_time > 0')
				-> where('T.tname','like',"%$tname%")
				-> join('__SECTION__ S','T.sid = S.sid')
				-> paginate(5);

		$page = $list -> render();

		if ($list) {
			
			return View('teacher',compact('list','page'));

		} else {
			
			$this -> error('没有匹配到所要查询的数据');
		}
		

	}

	//视屏回收站
	public function video()
	{
		$list = Db::name('video')
				->alias('V')
				->join('__SECTION__ S','V.sid = S.sid')
				->join('__TEACHER__ T','V.tid = T.tid')
				->where('V.delete_time > 0')
				->field('V.create_time,V.update_time,V.vid,V.vname,V.vconnect,T.tname,S.stopic')
				->paginate(5);

		$page = $list -> render();

		if ($list) {
			
			return View('',compact('list','page'));

		} else {
			
			$this -> error('没有查询到数据');

		}
		
		
	}
	//恢复
	public function vEdit()
	{
		$vid = input('post.vid');
		$video = new Video;

		$list = Db::name('video') 
				-> where('vid',$vid) 
				-> update(['delete_time' => 0]);	
	}
	//删除
	public function vDelete()
	{
		$vid = input('post.vid');
		Db::name('video') -> where('vid',$vid) -> delete();
	}
	//查询
	public function videoSelect()
	{
		$vname = input('get.vname');

		$list  = Db::name('video')
				-> alias('V')
				-> join('__SECTION__ S','V.sid = S.sid')
				-> join('__TEACHER__ T','V.tid = T.tid')
				-> where('V.vname','like',"%$vname%")
				-> where('V.delete_time > 0')
				-> paginate(5);
		$page  = $list -> render();

		if ($list) {

			return View('video',compact('list','page'));

		} else {

			$this -> error('没有查询到匹配的数据');

		}

	}


	//标题管理
	public function head()
	{
		$list = Db::name('head')
				->alias('H')
				->where('H.delete_time > 0')
				->join('__SECTION__ S','H.sid = S.sid')
				->paginate(5);
		$page = $list -> render();

		if ($list) {
			
			return View('',compact('list','page'));

		} else {
			
			$this -> error('没有删除的标题');

		}
		

	}
	
	//标题恢复
	public function headEdit()
	{
		$hid  = input('post.hid');
		$list = Db::name('head') -> where('hid',$hid) ->update(['delete_time' => 0]);
		
	}

	//标题真删除
	public function headDelete()
	{
		$hid = input('post.hid');
		Db::name('head') -> where('hid',$hid) -> delete();

	}
	
	//查标题
	public function headSelect()
	{
		$htitle = input('post.htitle');

		$list = Db::name('head')
				->alias('H')
				->join('__SECTION__ S','H.sid = S.sid')
				->where('H.htitle','like',"%$htitle%")
				->where('H.delete_time > 0')
				->paginate(5);

		$page = $list -> render();

		if ($list) {
			
			return View('head',compact('list','page'));

		} else {
			
			$this -> error('没有匹配到所要查询的数据');
		}
		

	}

	//评论管理
	public function comment()
	{
		$comment = Db::name('words');

		$list = $comment -> where('delete_time > 0') -> paginate(10);

		$page = $list -> render();

		if ($list) {

			return View('',compact('list','page'));
		
		} else {
			$this -> error('没有被删除的数据');
		}

	}

	//删除评论
	public function checkDelete()
	{
		$id = input('post.id');

		Db::name('words') -> where('id',$id) -> delete();
	}

	//查寻指定删除的评论
	public function commentSelect()
	{
		$content = input('post.content');

		$list = Db::name('words')
				-> where('delete_time > 0')
				-> where('content','like',"%$content%") 
				-> paginate(10);

		$page = $list -> render();

		if ($list) {

			return View('comment',compact('list','page'));

		} else {

			$this -> error('没有查询到匹配的数据');

		}

	}

	//恢复评论
	public function checkRecover()
	{
		$id = input('post.id');

		Db::name('words') -> where('id',$id) -> update(['delete_time' => 0]);

	}

}



















