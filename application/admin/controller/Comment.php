<?php 
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Words;
use think\Db;

class Comment extends Controller
{
	//评论展示
	public function manage()
	{   
      
		$comment = new Words;
                
		$list = Db::name('words')->where('delete_time = 0') -> paginate(10);
             
		$page = $list -> render();

		if ($list) {

			return View('',compact('list','page'));

		} else {

			$this -> error('无人评论');

		}
	}

	//软删除评论
	public function checkDelete()
	{
		$id = input('post.id');
             
		Words::destroy($id);
               

	}

	//查询指定评论内容
	public function commentSelect()
	{

		$content = input('post.content');

		$comment = new Words;

		$list = $comment -> where('content','like',"%$content%") ->paginate(10);

		$page = $list -> render();

		if ($list) {

			return View('manage',compact('list','page'));

		} else {
			
			$this -> error('没有查询到指定的内容');
		}

	}

}