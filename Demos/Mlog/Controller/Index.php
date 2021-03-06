<?php
/**
 * @link https://github.com/MaGuowei/M
 * @copyright 2013 maguowei.com
 * @author Ma Guowei <imaguowei@gmail.com>
 */
namespace Mlog\Controller;

use M\Extend\Page;
use Mlog\Model\Post;
use Mlog\Model\User;

/**
 * Class Index
 * @package Mlog\Controller
 */
class Index extends Common
{
    /**
     * @var array
     */
    public $data = array(
        'controller' => 'Index',
        'title' => '首页',
        'nav' => array(
            '首页' => 'Index/index',
        ),
    );
    /**
     * 设置布局
     * @var string
     */
    protected $layout = 'main';

    /**
     *首页
     */
    public function index($id)
    {
        $Post = new Post();

        $Post->join('LEFT JOIN user ON post.author_id=user.u_id');

        $Page = new Page($Post);
        $this->assign('page', array($Page->getPage(),'Index/index'));

        $Post->order('post.top desc,post.p_id','desc');
        $post = $Post->limit($id?($id-1)*5:0,5)->select();

        $this->assign('post',$post);
        $this->display('Index/index');
    }

    /**
     * 登录
     */
    public function login()
    {
        if(isset($_POST['login']))
        {
            $User = new User();

            $User->get_Post();
            $result = $User->login();
            if($result[0])
            {
                $this->success($_SESSION['user']['username'].'登录成功',array('Index','index'));
            }
            else
            {
                $this->error($result[1]);
            }
        }
        else
        {
            $this->data['title'] = '请填写登陆信息';
            $this->data['nav'] = array('登录'=> 'Index/login');
            $this->display('Index/login');
        }
    }

    /**
     * 注册
     */
    public function reg()
    {
        if(isset($_POST['reg']))
        {
            $User = new User();

            $User->get_Post();

            if($User->isUserNameExist())
            {
                $this->error('用户名已经存在');
            }
            else if($User->password != md5($User->rePassword))
            {
                $this->error('输入的密码不一致');
            }
            else
            {
                $result = $User->save();

                if($result)
                {
                    $this->success('注册成功',array('Index','index'));
                }
                else
                {
                    $this->error('注册失败');
                }
            }
        }
        else
        {
            $this->data['title'] = '请认真填写注册信息';
            $this->data['nav'] = array('注册'=>'Index/reg');
            $this->display('Index/reg');
        }
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        session_destroy();
        $this->success('退出成功',array('Index','index'));
    }
}